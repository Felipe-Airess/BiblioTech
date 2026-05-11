<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RealisticDate implements ValidationRule
{
    public function __construct(private readonly string $type)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', (string) $value);
        } catch (\Throwable) {
            $date = null;
        }

        if (! $date || $date->format('Y-m-d') !== (string) $value) {
            $fail('Informe uma data válida.');
            return;
        }

        [$min, $max, $message] = match ($this->type) {
            'member_birth' => [
                today()->subYears(120)->startOfDay(),
                today()->subYears(5)->endOfDay(),
                'A data de nascimento precisa indicar uma idade entre 5 e 120 anos.',
            ],
            'author_birth' => [
                Carbon::create(1000, 1, 1)->startOfDay(),
                today()->endOfDay(),
                'A data de nascimento do autor não pode ser futura ou anterior ao ano 1000.',
            ],
            'book_publication' => [
                Carbon::create(1450, 1, 1)->startOfDay(),
                today()->endOfDay(),
                'A data de publicação não pode ser futura ou anterior ao ano 1450.',
            ],
            'period' => [
                Carbon::create(2000, 1, 1)->startOfDay(),
                today()->endOfDay(),
                'A data do período precisa estar entre 01/01/2000 e hoje.',
            ],
            default => [
                Carbon::create(1900, 1, 1)->startOfDay(),
                today()->endOfDay(),
                'Informe uma data realista.',
            ],
        };

        if ($date->lessThan($min) || $date->greaterThan($max)) {
            $fail($message);
        }
    }
}
