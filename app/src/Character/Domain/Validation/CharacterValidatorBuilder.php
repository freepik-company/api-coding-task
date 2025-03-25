<?php

namespace App\Character\Domain\Validation;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;

class CharacterValidatorBuilder implements CharacterValidator
{
    private array $rules = [];

    public function __construct(private CharacterRepository $repository)
    {
    }

    public function mustHaveValidName(): self
    {
        $this->rules[] = new Rules\NameValidationRule();
        return $this;
    }

    public function mustHaveValidBirthDate(): self
    {
        $this->rules[] = new Rules\BirthDateValidationRule();
        return $this;
    }

    public function mustHaveValidKingdom(): self
    {
        $this->rules[] = new Rules\KingdomValidationRule();
        return $this;
    }

    public function mustHaveValidEquipment(): self
    {
        $this->rules[] = new Rules\EquipmentValidationRule();
        return $this;
    }

    public function mustBeUnique(): self
    {
        $this->rules[] = new Rules\UniqueCharacterRule($this->repository);
        return $this;
    }

    public function validate(Character $character): ValidationResult
    {
        $result = new ValidationResult();

        foreach ($this->rules as $rule) {
            $ruleResult = $rule->validate($character);
            $result->merge($ruleResult);
        }

        return $result;
    }
} 