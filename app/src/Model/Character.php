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
    private string $faction_name;
    private array $equipment_ids;
    private array $equipment_names;

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

    public function getFactionName(): string
    {
        return $this->faction_name;
    }

    public function setFactionName(string $faction_name): self
    {
        $this->faction_name = $faction_name;
        return $this;
    }

    public function getEquipmentIds(): array
    {
        return $this->equipment_ids;
    }

    public function setEquipmentIds(array $equipment_ids): self
    {
        $this->equipment_ids = $equipment_ids;
        return $this;
    }

    public function getEquipmentNames(): array
    {
        return $this->equipment_names;
    }

    public function setEquipmentNames(array $equipment_names): self
    {
        $this->equipment_names = $equipment_names;
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
            ->setFactionId($data['faction_id'])
            ->setFactionName($data['faction_name'] ?? '')
            ->setEquipmentIds([$data['equipment_id']])
            ->setEquipmentNames([$data['equipment_name'] ?? '']);
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id,
            'faction_name' => $this->faction_name,
            'equipment_ids' => $this->equipment_ids,
            'equipment_names' => $this->equipment_names
        ];

        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    public function find(int $id): ?self
    {
        $sql = "SELECT * FROM characters WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        // Obtener el nombre de la facción
        $sql = "SELECT faction_name FROM factions WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $data['faction_id']]);
        $faction = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['faction_name'] = $faction ? $faction['faction_name'] : '';

        // Obtener el nombre del equipamiento
        $sql = "SELECT name FROM equipments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $data['equipment_id']]);
        $equipment = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['equipment_name'] = $equipment ? $equipment['name'] : '';

        return self::fromArray($data, $this->pdo);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM characters";
        $stmt = $this->pdo->query($sql);
        $characters = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Obtener el nombre de la facción
            $sql = "SELECT faction_name FROM factions WHERE id = :id";
            $stmt2 = $this->pdo->prepare($sql);
            $stmt2->execute(['id' => $data['faction_id']]);
            $faction = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data['faction_name'] = $faction ? $faction['faction_name'] : '';

            // Obtener el nombre del equipamiento
            $sql = "SELECT name FROM equipments WHERE id = :id";
            $stmt2 = $this->pdo->prepare($sql);
            $stmt2->execute(['id' => $data['equipment_id']]);
            $equipment = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data['equipment_name'] = $equipment ? $equipment['name'] : '';

            $character = new self($this->pdo);
            $character->setId($data['id'])
                     ->setName($data['name'])
                     ->setBirthDate($data['birth_date'])
                     ->setKingdom($data['kingdom'])
                     ->setEquipmentId($data['equipment_id'])
                     ->setFactionId($data['faction_id'])
                     ->setFactionName($data['faction_name'])
                     ->setEquipmentIds([$data['equipment_id']])
                     ->setEquipmentNames([$data['equipment_name']]);

            $characters[] = $character;
        }

        return $characters;
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

        return self::fromArray($data, $pdo);
    }

    public static function getAll(PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT 
                c.id,
                c.name,
                c.faction_id,
                f.name as faction_name,
                GROUP_CONCAT(DISTINCT e.id) as equipment_ids,
                GROUP_CONCAT(DISTINCT e.name) as equipment_names
            FROM characters c
            LEFT JOIN factions f ON c.faction_id = f.id
            LEFT JOIN character_equipment ce ON c.id = ce.character_id
            LEFT JOIN equipments e ON ce.equipment_id = e.id
            GROUP BY c.id
        ");

        $characters = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['equipment_ids'] = $row['equipment_ids'] ? explode(',', $row['equipment_ids']) : [];
            $row['equipment_names'] = $row['equipment_names'] ? explode(',', $row['equipment_names']) : [];
            $characters[] = self::fromArray($row, $pdo);
        }

        return $characters;
    }

    public static function getById(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("
            SELECT 
                c.id,
                c.name,
                c.faction_id,
                f.name as faction_name,
                GROUP_CONCAT(DISTINCT e.id) as equipment_ids,
                GROUP_CONCAT(DISTINCT e.name) as equipment_names
            FROM characters c
            LEFT JOIN factions f ON c.faction_id = f.id
            LEFT JOIN character_equipment ce ON c.id = ce.character_id
            LEFT JOIN equipments e ON ce.equipment_id = e.id
            WHERE c.id = ?
            GROUP BY c.id
        ");
        
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $row['equipment_ids'] = $row['equipment_ids'] ? explode(',', $row['equipment_ids']) : [];
        $row['equipment_names'] = $row['equipment_names'] ? explode(',', $row['equipment_names']) : [];
        
        return self::fromArray($row, $pdo);
    }
}
