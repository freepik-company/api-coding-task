<?php

namespace App\Character\Infrastructure\Persistence;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\MySQLCharacterFactory;
use PDO;

class MySQLCharacterRepository implements CharacterRepository
{
    public function __construct(private PDO $pdo)
    {

    }


    public function find(int $id): ?Character{
        $stmt = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data, $this->pdo);    
    }

    public function findAll(): array{
        $stmt = $this->pdo->query('SELECT * FROM characters');
        $characters = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)){
            $characters[] = MySQLCharacterFactory::buildFromArray($data);
        }
        
        return $characters;
    }

    public function save(Character $character): Character{
        if (isset($this->id)){
            return $this->update($character);
        }

        return $this->insert($character);
    }

    private function insert(Character $character): Character{
        $sql = 'INSERT INTO characters (name, birth_date, kingdom, equipment_id, faction_id) VALUES (:name, :birth_date, :kingdom, :equipment_id, :faction_id)';

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' =>           $character->getName(),
            'birth_date' =>     $character->getBirthDate(),
            'kingdom' =>        $character->getKingdom(),
            'equipment_id'=>    $character->getEquipmentId(),
            'faction_id' =>     $character->getFactionId(),
            ]);

            if (!$result){
            $character->setId($this->pdo->lastInsertId());
            }

            return $character;  
    }

    private function update(Character $character) : Character{
        $sql ='UPDATE characters
            SET name = :name,
                birth_date = :birth_date,
                kingdom = :kingdom,
                equipment_id = :equipment_id,
                faction_id = :faction_id
            WHERE id = :id';
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $character->getId(),
            'name' => $character->getName(),
            'birth_date' => $character->getBirthDate(),
            'kingdom' => $character->getKingdom(),
            'equipment_id' => $character->getEquipmentId(),
            'faction_id' => $character->getFactionId(),
        ]);

            return $character;
    }

    public function delete(Character $character): bool{
        if (null === $character->getId()){
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM characters WHERE id = :id');
        return $stmt-> execute(['id' => $character->getId()]);
    }

    public function findByName(string $name): ?Character
    {
        $stmt = $this->pdo->prepare('SELECT * FROM characters WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data);
    }

    public function fromArray(array $data): Character{
        $character = new Character(
            $data['name'],
            $data['birth_date'],
            $data['kingdom'],
            $data['equipment_id'],
            $data['faction_id']
        );

        if (isset($data['id'])){
            $character->setId($data['id']);
        }

        return $character;
    }
}