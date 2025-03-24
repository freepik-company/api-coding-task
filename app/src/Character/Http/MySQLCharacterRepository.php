<?php

namespace App\Character\Infrastructure;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use PDO;

class MySQLCharacterRepository implements CharacterRepository
{
    public function __construct(private PDO $pdo)
    {

    }
}

public funtion find(int $id): ?Character{
    $stmt = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        return null;
    }

    return self::fromArray($data, $this->pdo);    
}

private function fromArray(array $data): Character{
    $character = new Character();

    if isset($data['id']){
        $character->setId($data['id'];)
    }

    return $character;
        ->setName($data['name'])
        ->setBirthDate($data['birth_date'])
        ->setKingdom($data['kingdom'])
        ->setEquimentId($data['equipment_id'])
        ->setFactionId($data['faction_id']);
}

public function findAll(): array{
    $stmt = $this->pdo->query('SELECT * FROM characters');
    $characters = [];

    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)){
        $characters[] = self::fromArray($data);
    }
    
    return $characters;
}
