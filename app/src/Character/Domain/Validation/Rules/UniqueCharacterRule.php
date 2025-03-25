<?php

namespace App\Character\Domain\Validation\Rules;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Domain\Validation\ValidationRule;
use App\Character\Domain\Validation\ValidationResult;

class UniqueCharacterRule implements ValidationRule
{
    public function __construct(private CharacterRepository $repository)
    {
    }

    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();
        $name = $character->getName();

        // Buscar si existe un personaje con el mismo nombre
        $existingCharacter = $this->repository->findByName($name);
        
        if ($existingCharacter !== null) {
            $result->addError('name', 'Ya existe un personaje con este nombre');
        }

        return $result;
    }
} 