<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'                 => ['required', 'email'],
            'otp'                   => ['required', 'digits:6'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // o campo "password_confirmation" é validado implícita­mente pelo rule "confirmed"
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'O campo email é obrigatório.',
            'email.email'       => 'Informe um email válido.',
            'otp.required'      => 'O código OTP é obrigatório.',
            'otp.digits'        => 'O código OTP deve ter exatamente 6 dígitos.',
            'password.required' => 'A nova senha é obrigatória.',
            'password.min'      => 'A nova senha deve ter ao menos 8 caracteres.',
            'password.confirmed'=> 'A confirmação da senha não corresponde.',
        ];
    }
}