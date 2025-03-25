<?php

namespace App\Character\Domain\Validation\Rules;

use App\Character\Domain\Character;
use App\Character\Domain\Validation\ValidationRule;
use App\Character\Domain\Validation\ValidationResult;

class EquipmentValidationRule implements ValidationRule
{
    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();
        $equipmentId = $character->getEquipmentId();

        if ($equipmentId <= 0) {
            $result->addError('equipment_id', 'El ID del equipamiento debe ser mayor que 0');
        }

        return $result;
    }
} 