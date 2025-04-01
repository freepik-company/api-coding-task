<?php

namespace App\Character\Application;

/**
 * This use case is used to update a character
 */

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;

class UpdateCharacterUseCase
{
    public function __construct(
        private CharacterRepository $repository
    ){

    }

    public function execute(
        UpdateCharacterUseCaseRequest $request
    ): Character
    {
        $oldcharacter = $this->repository->find($request->getId());

        if (!$oldcharacter) {
            throw new \Exception('Character not found');
        }

        $updatedcharacter = new Character(
            name: $request->getName(),
            birth_date: $request->getBirthDate(),
            kingdom: $request->getKingdom(),
            equipment_id: $request->getEquipmentId(),
            faction_id: $request->getFactionId(),
            id: $oldcharacter->getId()
        );

        return $this->repository->save($updatedcharacter);
    }
}