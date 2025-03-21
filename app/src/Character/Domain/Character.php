<?php

namespace App\Character\Domain;

/**
 * Character entity representing a character in the game
 */
class Character
{
    private ?int $id = null;
    private string $name;
    private string $birth_date;
    private string $kingdom;
    private int $equipment_id;
    private int $faction_id;
    
    public function __construct()
    {
    }

    /**
     * Get the character ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Set the character ID
     * @throws \InvalidArgumentException if ID is negative
     */
    public function setId(int $id): self
    {
        if ($id < 0) {
            throw new \InvalidArgumentException('ID cannot be negative');
        }
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get the character name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Set the character name
     * @throws \InvalidArgumentException if name is empty
     */
    public function setName(string $name): self
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get the character birth date
     */
    public function getBirthDate(): string
    {
        return $this->birth_date;
    }
    
    /**
     * Set the character birth date
     * @throws \InvalidArgumentException if date format is invalid
     */
    public function setBirthDate(string $birth_date): self
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birth_date)) {
            throw new \InvalidArgumentException('Birth date must be in YYYY-MM-DD format');
        }
        $this->birth_date = $birth_date;
        return $this;
    }
    
    /**
     * Get the character kingdom
     */
    public function getKingdom(): string
    {
        return $this->kingdom;
    }
    
    /**
     * Set the character kingdom
     * @throws \InvalidArgumentException if kingdom is empty
     */
    public function setKingdom(string $kingdom): self
    {
        if (empty(trim($kingdom))) {
            throw new \InvalidArgumentException('Kingdom cannot be empty');
        }
        $this->kingdom = $kingdom;
        return $this;
    }
    
    /**
     * Get the character equipment ID
     */
    public function getEquipmentId(): int
    {
        return $this->equipment_id;
    }
    
    /**
     * Set the character equipment ID
     * @throws \InvalidArgumentException if equipment ID is negative
     */
    public function setEquipmentId(int $equipment_id): self 
    {
        if ($equipment_id < 0) {
            throw new \InvalidArgumentException('Equipment ID cannot be negative');
        }
        $this->equipment_id = $equipment_id;
        return $this;
    }
    
    /**
     * Get the character faction ID
     */
    public function getFactionId(): int 
    {
        return $this->faction_id;
    }
    
    /**
     * Set the character faction ID
     * @throws \InvalidArgumentException if faction ID is negative
     */
    public function setFactionId(int $faction_id): self
    {
        if ($faction_id < 0) {
            throw new \InvalidArgumentException('Faction ID cannot be negative');
        }
        $this->faction_id = $faction_id;
        return $this;
    }
    
    /**
     * Create a Character instance from an array of data
     * @throws \InvalidArgumentException if required data is missing
     */
    public function fromArray(array $data): self
    {
        $requiredFields = ['name', 'birth_date', 'kingdom', 'equipment_id', 'faction_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        $character = new self();

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
    
    /**
     * Convert the Character instance to an array
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        return $data;
    }
}