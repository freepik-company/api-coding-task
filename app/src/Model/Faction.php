<?php

namespace App\Model;

use PDO;

class Faction
{
    private int $id;
    private string $faction_name;
    private string $description;

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

    public function getFactionName(): string
    {
        return $this->faction_name;
    }

    public function setFactionName(string $faction_name): self
    {
        $this->faction_name = $faction_name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function fromArray(array $data, PDO $pdo): self
    {
        $faction = new self($pdo);
        
        if (isset($data['id'])) {
            $faction->setId($data['id']);
        }
        
        return $faction
            ->setFactionName($data['faction_name'])
            ->setDescription($data['description']);
    }

    public function toArray(): array
    {
        $data = [
            'faction_name' => $this->faction_name,
            'description' => $this->description
        ];

        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    public function find(int $id): ?self
    {
        $stmt = $this->pdo->prepare('SELECT * FROM factions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data, $this->pdo);
    }

    /**
     * Busca una facción por su nombre
     * 
     * @param string $faction_name Nombre de la facción a buscar
     * @return Faction|null Objeto Faction si se encuentra, null en caso contrario
     */
    
    public function findByName(string $faction_name): ?self
    {
        $stmt = $this->pdo->prepare('SELECT * FROM factions WHERE faction_name = :faction_name');
        $stmt->execute(['faction_name' => $faction_name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data, $this->pdo);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM factions');
        $factions = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $factions[] = self::fromArray($data, $this->pdo);
        }

        return $factions;
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
        $sql = 'INSERT INTO factions (faction_name, description) 
                VALUES (:faction_name, :description)';
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'faction_name' => $this->faction_name,
            'description' => $this->description
        ]);

        if ($result) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $result;
    }

    private function update(): bool
    {
        $sql = 'UPDATE factions 
                SET faction_name = :faction_name, 
                    description = :description 
                WHERE id = :id';
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $this->id,
            'faction_name' => $this->faction_name,
            'description' => $this->description
        ]);
    }

    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM factions WHERE id = :id');
        return $stmt->execute(['id' => $this->id]);
    }
} 