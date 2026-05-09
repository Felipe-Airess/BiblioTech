<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D+/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('CPF inválido. Por favor, informe um CPF válido.');
            return;
        }

        $digits = array_map('intval', str_split($cpf));

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($c = 0; $c < $t; $c++) {
                $sum += $digits[$c] * (($t + 1) - $c);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ($digits[$t] !== $digit) {
                $fail('CPF inválido. Por favor, informe um CPF válido.');
                return;
            }
        }
    }
}
