<?php

namespace App\Character\Domain\Validation\Rules;

use App\Character\Domain\Character;
use App\Character\Domain\Validation\ValidationRule;
use App\Character\Domain\Validation\ValidationResult;

class NameValidationRule implements ValidationRule
{
    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();
        $name = $character->getName();

        if (empty(trim($name))) {
            $result->addError('name', 'El nombre no puede estar vacío');
        }

        if (strlen($name) > 100) {
            $result->addError('name', 'El nombre no puede tener más de 100 caracteres');
        }

        return $result;
    }
} 