<?php

namespace App\Character\Domain\Validation;

use App\Character\Domain\Character;

interface CharacterValidator
{
    /**
     * Valida un personaje y retorna el resultado de la validación
     */
    public function validate(Character $character): ValidationResult;
} 