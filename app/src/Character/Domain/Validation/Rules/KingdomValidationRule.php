<?php

namespace App\Character\Domain\Validation\Rules;

use App\Character\Domain\Character;
use App\Character\Domain\Validation\ValidationRule;
use App\Character\Domain\Validation\ValidationResult;

class KingdomValidationRule implements ValidationRule
{
    private const VALID_KINGDOMS = [
        'GONDOR',
        'ROHAN',
        'MORDOR',
        'RIVENDELL',
        'SHIRE',
        'MIRKWOOD',
        'AINUR'
    ];

    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();
        $kingdom = $character->getKingdom();

        if (empty(trim($kingdom))) {
            $result->addError('kingdom', 'El reino no puede estar vacío');
            return $result;
        }

        if (!in_array(strtoupper($kingdom), self::VALID_KINGDOMS)) {
            $result->addError('kingdom', 'El reino debe ser uno de los siguientes: ' . implode(', ', self::VALID_KINGDOMS));
        }

        return $result;
    }
} 