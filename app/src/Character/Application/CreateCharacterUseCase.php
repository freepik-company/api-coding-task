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

        // Create character
        $character = new Character();
        $character->setName($name);
        $character->setBirthDate($birthDate);
        $character->setKingdom($kingdom);
        $character->setEquipmentId($equipmentId);
        $character->setFactionId($factionId);

        return $this->repository->save($character);
    }
}