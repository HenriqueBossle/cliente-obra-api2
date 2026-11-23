<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    /**
     * Exibe os dados do perfil do usuário autenticado.
     */
    public function show(Request $request)
    {
        // Retorna os dados do usuário logado em formato JSON
        return response()->json([
            'status' => true,
            'user' => $request->user() // ou auth()->user()
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        $requestedEmail = $validated['email'];
        $emailChanged = $requestedEmail !== $user->email;

        unset($validated['email']);
        $user->update($validated);

        if ($emailChanged) {
            $user->forceFill([
                'pending_email' => $requestedEmail,
            ])->save();

            $verificationUrl = URL::temporarySignedRoute(
                'verification.pending-email',
                now()->addMinutes(60),
                [
                    'id' => $user->id,
                    'hash' => sha1($requestedEmail),
                ]
            );

            BrevoService::sendVerificationEmail(
                $requestedEmail,
                $user->name,
                $verificationUrl
            )->throw();
        }

        return response()->json([
            'message' => $user->pending_email
                ? 'Perfil atualizado. Verifique o novo e-mail para confirmar a alteração.'
                : 'Perfil atualizado com sucesso',
            'user' => $user->fresh(),
        ]);
    }

    public function verifyPendingEmail(int $id, string $hash)
    {
        $user = User::find($id);
        $pendingEmail = $user?->pending_email;

        if (
            ! $pendingEmail
            || ! hash_equals(sha1($pendingEmail), $hash)
        ) {
            return response()->json([
                'message' => 'Link inválido ou expirado.',
            ], 403);
        }

        if (User::where('email', $pendingEmail)->whereKeyNot($user->getKey())->exists()) {
            return response()->json([
                'message' => 'Este e-mail não está mais disponível.',
            ], 409);
        }

        $user->forceFill([
            'email' => $pendingEmail,
            'pending_email' => null,
            'email_verified_at' => now(),
        ])->save();

        event(new Verified($user));

        return response()->json([
            'message' => 'E-mail alterado e verificado com sucesso.',
        ]);
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Senha alterada com sucesso'
        ]);
    }

    /**
     * Exclui a conta do usuário autenticado.
     */


    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = $request->user();

        // Revoga tokens (Sanctum)
        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Conta excluída com sucesso.'
        ], 200);
    }
}
