<?php

namespace App\Character\Domain;

//use JsonSerializable;

/**
 * Character entity representing a character in the game
 */
class Character
{

    public function __construct(
        private string $name,
        private string $birth_date,
        private string $kingdom,
        private int $equipment_id,
        private int $faction_id,
        private ?int $id = null
    )
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
     */
    public function setBirthDate(string $birth_date): self
    {
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
     */
    public function setFactionId(int $faction_id): self
    {
        if ($faction_id < 0) {
            throw new \InvalidArgumentException('Faction ID cannot be negative');
        }
        $this->faction_id = $faction_id;
        return $this;
    }
    
}