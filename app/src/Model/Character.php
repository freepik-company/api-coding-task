<?php

namespace App\Model;

use PDO;

class Character
{
    private int $id;
    private string $name;
    private string $birth_date;
    private string $kingdom;
    private int $equipment_id;
    private int $faction_id;

    public function __construct(private PDO $pdo)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    public function setBirthDate(string $birth_date): self
    {
        $this->birth_date = $birth_date;
        return $this;
    }

    public function getKingdom(): string
    {
        return $this->kingdom;
    }

    public function setKingdom(string $kingdom): self
    {
        $this->kingdom = $kingdom;
        return $this;
    }

    public function getEquipmentId(): int
    {
        return $this->equipment_id;
    }

    public function setEquipmentId(int $equipment_id): self
    {
        $this->equipment_id = $equipment_id;
        return $this;
    }

    public function getFactionId(): int
    {
        return $this->faction_id;
    }

    public function setFactionId(int $faction_id): self
    {
        $this->faction_id = $faction_id;
        return $this;
    }

    public function fromArray(array $data, PDO $pdo): self
    {
        $character = new self($pdo);
        
        if (isset($data['id'])) {
            $character->setId($data['id']);
        }
        
        return $character
            ->setName($data['name'])
            ->setBirthDate($data['birth_date'])
            ->setKingdom($data['kingdom'])
            ->setEquipmentId($data['equipment_id'])
            ->setFactionId($data['faction_id']);
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id
        ];

        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    public function find(int $id): ?self
    {
        $stmt = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data, $this->pdo);
    }

    public function findAll(): array
    {
        $sql = "SELECT c.*, f.faction_name, e.name as equipment_name 
                FROM characters c 
                LEFT JOIN factions f ON c.faction_id = f.id
                LEFT JOIN equipments e ON c.equipment_id = e.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(): bool
    {
        if (isset($this->id)) {
            return $this->update();
        }

        return $this->insert();
    }

    private function insert(): bool
    {
        $sql = 'INSERT INTO characters (name, birth_date, kingdom, equipment_id, faction_id) 
                VALUES (:name, :birth_date, :kingdom, :equipment_id, :faction_id)';
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id
        ]);

        if ($result) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $result;
    }

    private function update(): bool
    {
        $sql = 'UPDATE characters 
                SET name = :name, 
                    birth_date = :birth_date, 
                    kingdom = :kingdom, 
                    equipment_id = :equipment_id, 
                    faction_id = :faction_id 
                WHERE id = :id';
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id
        ]);
    }

    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM characters WHERE id = :id');
        return $stmt->execute(['id' => $this->id]);
    }

    public static function findByName(PDO $pdo, string $name): ?self
    {
        $stmt = $pdo->prepare('SELECT * FROM characters WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $character = new self($pdo);
        $character->id = (int) $data['id'];
        $character->name = $data['name'];
        $character->birth_date = $data['birth_date'];
        $character->kingdom = $data['kingdom'];
        $character->equipment_id = (int) $data['equipment_id'];
        $character->faction_id = (int) $data['faction_id'];

        return $character;
    }
}
