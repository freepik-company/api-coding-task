<?php

namespace App\Test\Character\Application\MotherObject;

use App\Character\Application\UpdateCharacterUseCaseRequest;

class UpdateCharacterUseCaseRequestMotherObject
{
    public static function valid(): UpdateCharacterUseCaseRequest
    {
        return new UpdateCharacterUseCaseRequest(
            name: 'John Doe',
            birthDate: '1990-01-01',
            kingdom: 'Kingdom of Spain',
            equipmentId: 1,
            factionId: 1,
            id: 1
        );
    }
}
