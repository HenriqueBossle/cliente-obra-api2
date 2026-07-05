<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordOtpReset;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controle de recuperação de senha via código OTP.
 *
 * Todas as respostas são JSON padronizadas e não divulgam se o e‑mail
 * informado existe no sistema, atendendo ao requisito de privacidade.
 */
class PasswordResetController extends Controller
{
    /**
     * Envia um código OTP de 6 dígitos para o e‑mail informado.
     *
     * @param  ForgotPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');

        // Tenta encontrar o usuário, mas continue o fluxo mesmo que não exista.
        $user = User::where('email', $email)->first();

        // Gerar OTP apenas se houver usuário – caso contrário, gera
        // um valor fictício apenas para “gastar” tempo similar.
        if ($user) {
            // 1. gerar código de 6 dígitos
            $otp = random_int(100000, 999999);

            // 2. hash + validade 15 min
            $hash = Hash::make((string) $otp);
            $expiresAt = Carbon::now()->addMinutes(15);

            // 3. Invalida código anterior (unique email ⇒ updateOrCreate)
            PasswordOtpReset::updateOrCreate(
                ['email' => $email],
                ['otp_hash' => $hash, 'expires_at' => $expiresAt]
            );

            // 4. Envia e‑mail usando a estrutura Brevo já existente.
            //    Assume‑se que já exista um Mailable “OtpMail” que aceita o OTP.
            //    Se o projeto utiliza um service wrapper, basta trocar a chamada.
            BrevoService::sendOtp($email, $otp);
        } else {
            // Simula pequeno delay para evitar diferenciação de tempo.
            usleep(200_000);
        }

        return response()->json(
            ['message' => 'Se o email estiver cadastrado, um código de recuperação será enviado.'],
            Response::HTTP_OK
        );
    }

    /**
     * Reseta a senha usando o OTP enviado.
     *
     * @param  ResetPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $email    = $request->input('email');
        $otpInput = $request->input('otp');
        $newPass  = $request->input('password');

        // 1. Busca o usuário – caso não exista, retornamos mensagem genérica.
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->invalidResponse();
        }

        // 2. Busca registro OTP válido.
        $otpRecord = PasswordOtpReset::where('email', $email)
            ->valid()
            ->first();

        if (!$otpRecord) {
            return $this->invalidResponse();
        }


        // 3. Verifica expiração (fallback caso o escopo falhe)
        if ($otpRecord->isExpired()) {

        $otpRecord->delete();
            return $this->invalidResponse();
        }

        // 4. Verifica o código.
        if (!Hash::check((string) $otpInput, $otpRecord->otp_hash)) {
            return $this->invalidResponse();
        }

        // 5. Tudo OK – atualiza a senha do usuário.
        $user->password = Hash::make($newPass);
        $user->save();

        // 6. Remove o OTP usado.
        $otpRecord->delete();

        return response()->json(
            ['message' => 'Senha alterada com sucesso.'],
            Response::HTTP_OK
        );
    } 

    /**
     * Resposta JSON uniforme para falhas de validação de OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidResponse()
    {
        return response()->json(
            ['message' => 'Código inválido ou expirado.'],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}