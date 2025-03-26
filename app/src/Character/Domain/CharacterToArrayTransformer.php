<?php

namespace App\Character\Domain;

use App\Character\Domain\Character;

class MySQLCharacterToArrayTransformer{

    public static function transform(Character $character): array
    {
        $data = [
            'name' => $character->getName(),
            'birth-date' => $character->getBirthDate(),
            'kingdom' => $character->getKingdom(),
            'equipment-id' => $character->getEquipmentId(),
            'faction-id' => $character->getFactionId()
        ];

        if(null !== $character->getId()){
            $data['id'] = $character->getId();
        }

        return $data;
    }
    
}