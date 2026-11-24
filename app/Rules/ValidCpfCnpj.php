<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpfCnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove pontos, traços, barras e outros caracteres
        $documento = preg_replace('/\D/', '', (string) $value);

        if (strlen($documento) === 11) {
            if (!$this->validarCpf($documento)) {
                $fail('O CPF informado é inválido.');
            }

            return;
        }

        if (strlen($documento) === 14) {
            if (!$this->validarCnpj($documento)) {
                $fail('O CNPJ informado é inválido.');
            }

            return;
        }

        $fail('Informe um CPF ou CNPJ válido.');
    }

    private function validarCpf(string $cpf): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        $base = substr($cpf, 0, 9);

        // Primeiro dígito verificador
        $pesos = [10, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;

        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $base[$i] * $pesos[$i];
        }

        $resto = $soma % 11;

        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        // Segundo dígito verificador
        $base .= $digito1;

        $pesos = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;

        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $base[$i] * $pesos[$i];
        }

        $resto = $soma % 11;

        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        $digitosCalculados = $digito1 . $digito2;
        $digitosInformados = substr($cpf, 9, 2);

        return $digitosCalculados === $digitosInformados;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $base = substr($cnpj, 0, 12);

        // Primeiro dígito verificador
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;

        for ($i = 0; $i < 12; $i++) {
            $soma += (int) $base[$i] * $pesos[$i];
        }

        $resto = $soma % 11;

        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        // Segundo dígito verificador
        $base .= $digito1;

        $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;

        for ($i = 0; $i < 13; $i++) {
            $soma += (int) $base[$i] * $pesos[$i];
        }

        $resto = $soma % 11;

        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        $digitosCalculados = $digito1 . $digito2;
        $digitosInformados = substr($cnpj, 12, 2);

        return $digitosCalculados === $digitosInformados;
    }
}
