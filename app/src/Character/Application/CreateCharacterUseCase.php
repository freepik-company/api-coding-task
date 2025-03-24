<?php

namespace App\Character\Application;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;

class CreateCharacterUseCase
{
    public function __construct(private CharacterRepository $repository)
    {
    }

    public function execute(
        string $name,
        string $birthDate,
        string $kingdom,
        int $equipmentId,
        int $factionId
    ): Character
    {   

        // Validation for name
        if (empty(trim($name))){
            throw new \InvalidArgumentException('Name is required');
        }

        // Validation for birthDate
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)){
            throw new \InvalidArgumentException('Invalid birth date format. Use YYYY-MM-DD');
        }

        // Validation for kingdom
        if (empty(trim($kingdom))){
            throw new \InvalidArgumentException('Kingdom is required');
        }

        // Validation for equipmentId
        if ($equipmentId <= 0){
            throw new \InvalidArgumentException('Invalid equipment ID');
        }

        // Validation for factionId
        if ($factionId <= 0){
            throw new \InvalidArgumentException('Invalid faction ID');
        }

        // Create character
        $character = new Character();
        $character->setName($name);
        $character->setBirthDate($birthDate);
        $character->setKingdom($kingdom);
        $character->setEquipmentId($equipmentId);
        $character->setFactionId($factionId);

        return $this->repository->save($character);
    }
}// TODO: Validar y sanitizar cualquier input del usuario