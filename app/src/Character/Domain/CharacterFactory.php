<?php

namespace App\Character\Domain;

class CharacterFactory{
 
    public static function build(
        string $name,
        string $birthDate,
        string $kingdom,
        int $equipmentId,
        int $factionId,
        ?int $id = null // Optional ID, use null by default
    ): Character{
        return new Character(
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId,
            $id
        );
    }

}