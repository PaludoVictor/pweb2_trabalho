<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$/', (string) $value)) {
            $fail('Informe um CPF válido.');
            return;
        }

        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('Informe um CPF válido.');
            return;
        }

        for ($length = 9; $length < 11; $length++) {
            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $cpf[$i] * ($length + 1 - $i);
            }

            $digit = ($sum * 10) % 11;
            if ($digit === 10) {
                $digit = 0;
            }

            if ($digit !== (int) $cpf[$length]) {
                $fail('Informe um CPF válido.');
                return;
            }
        }
    }
}
