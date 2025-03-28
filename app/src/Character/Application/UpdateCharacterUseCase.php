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
        string $name,
        string $birthDate,
        string $kingdom,
        int $equipmentId,
        int $factionId,
        int $id
    ): Character
    {
        $oldcharacter = $this->repository->find($id);


/* How I'm using semantics constructor I can't use the update method
I need to create a new character with the new values
*/

        if (!$oldcharacter) {
            throw new \Exception('Character not found');
        }

        $updatedcharacter = new Character(
            name: $name,
            birth_date: $birthDate,
            kingdom: $kingdom,
            equipment_id: $equipmentId,
            faction_id: $factionId,
            id: $oldcharacter->getId()
        );

        return $this->repository->save($updatedcharacter);
    }
}