<?php

namespace App\Character\Domain\Validation;

use App\Character\Domain\Character;

interface ValidationRule
{
    /**
     * Valida una regla específica del personaje
     */
    public function validate(Character $character): ValidationResult;
} 