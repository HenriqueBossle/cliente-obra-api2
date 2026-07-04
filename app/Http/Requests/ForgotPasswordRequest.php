<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Permitir que a requisição seja usada.
     */
    public function authorize(): bool
    {
        return true; // controle de autorização já é feito nas rotas / middleware
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**
     * Mensagens customizadas (opcional).
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O campo email é obrigatório.',
            'email.email'    => 'Informe um email válido.',
        ];
    }
}