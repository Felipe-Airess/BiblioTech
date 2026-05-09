<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhonePrefix implements ValidationRule
{
    /**
     * Prefixos telefônicos válidos no Brasil (DDD).
     */
    private const VALID_DDDS = [
        '11','12','13','14','15','16','17','18','19',
        '21','22','24','27','28',
        '31','32','33','34','35','37','38',
        '41','42','43','44','45','46','47','48','49',
        '51','53','54','55',
        '61','62','63','64','65','66','67','68','69',
        '71','73','74','75','77','79',
        '81','82','83','84','85','86','87','88','89',
        '91','92','93','94','95','96','97','98','99',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = preg_replace('/\D+/', '', (string) $value);

        if (strlen($phone) < 10 || strlen($phone) > 11) {
            $fail('Telefone inválido. Informe um DDD e número válidos.');
            return;
        }

        $ddd = substr($phone, 0, 2);

        if (! in_array($ddd, self::VALID_DDDS, true)) {
            $fail('Telefone inválido. Informe um DDD válido.');
        }
    }
}
