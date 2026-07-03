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
                'name' => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],

            'to' => [
                [
                    'email' => $email,
                    'name' => $name,
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
}