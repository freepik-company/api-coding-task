<?php

namespace App\Character\Application;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Domain\Validation\CharacterValidatorBuilder;

class CreateCharacterUseCase
{
    public function __construct(
        private CharacterRepository $repository,
        private CharacterValidatorBuilder $validatorBuilder
    ) {
    }

    public function execute(
        string $name,
        string $birthDate,
        string $kingdom,
        int $equipmentId,
        int $factionId
    ): Character
    {   
        // Create character
        $character = new Character(
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
        );

        // Validate character
        $validationResult = $this->validatorBuilder
            ->mustHaveValidName()
            ->mustHaveValidBirthDate()
            ->mustHaveValidKingdom()
            ->mustHaveValidEquipment()
            ->mustBeUnique()
            ->validate($character);

        if (!$validationResult->isValid()) {
            throw new \InvalidArgumentException(json_encode($validationResult->getErrors()));
        }

        return $this->repository->save($character);
    }
}