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

                <p>Clique no botão abaixo.</p>

                <a href='$url'>
                    Confirmar Email
                </a>
            "

            
        ]);
        
    }
}