<?php

namespace App\Character\Domain\Validation\Rules;

use App\Character\Domain\Character;
use App\Character\Domain\Validation\ValidationRule;
use App\Character\Domain\Validation\ValidationResult;

class BirthDateValidationRule implements ValidationRule
{
    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();
        $birthDate = $character->getBirthDate();

        if (empty($birthDate)) {
            $result->addError('birth_date', 'La fecha de nacimiento no puede estar vacía');
            return $result;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $birthDate);
        if (!$date || $date->format('Y-m-d') !== $birthDate) {
            $result->addError('birth_date', 'La fecha de nacimiento debe tener el formato YYYY-MM-DD');
        }

        // Validar que la fecha no sea futura
        if ($date && $date > new \DateTime()) {
            $result->addError('birth_date', 'La fecha de nacimiento no puede ser futura');
        }

        return $result;
    }
} 