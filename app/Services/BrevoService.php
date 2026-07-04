<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoService
{
    public static function sendVerificationEmail(
        string $email,
        string $name,
        string $url
    ) {

        return Http::withHeaders([
            'api-key' => env('BREVO_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [

            'sender' => [
              
                'email' => env('MAIL_FROM_ADDRESS'),
            ],

            'to' => [
                [
                    'name' => $name,
                    'email' => $email,
                ]
            ],

            'subject' => 'Confirme seu e-mail',

            'htmlContent' => "
                <h2>Bem-vindo!</h2>

                <h3>Muito Obrigado por se cadastrar no ClienteObra</h3>

                <p>Clique no botão para confirmar seu e-mail.</p>

                <p>Se você não solicitou este cadastro, ignore este e-mail.</p>

                <a href='$url'>
                    Confirmar Email
                </a>

                <p>© 2026 ClienteObra. Todos os direitos reservados.</p>
            "

            
        ]);
        
    }

     public static function sendOtp(
        string $email,
        string $opt
    ) {

        return Http::withHeaders([
            'api-key' => env('BREVO_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [

            'sender' => [
                'name' => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],

            'to' => [
                [
                    'email' => $email
                ]
            ],

            'subject' => 'Solicitação de nova senha',

            'htmlContent' => "
                <h2>Atenção você solicitou uma nova senha</h2>

                <p>Insira o codigo abaixo no site para recuperar a senha</p>

                <p>$opt</p>

                <p>O codigo expira em 15 minutos</p>
                    
                <p>Se você não solicitou isso ignore esse email</p>

                <p>© 2026 ClienteObra. Todos os direitos reservados.</p>
            "

            
        ]);
        
    }
}