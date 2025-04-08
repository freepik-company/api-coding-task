<?php

namespace App\Faction\Domain;

class Faction
{
    // Properties
    private string $faction_name;
    private string $description;
    private ?int $id = null;

    // Constructor
    public function __construct(
        string $faction_name,
        string $description,
        ?int $id = null
    ) {
        $this->faction_name = $faction_name;
        $this->description = $description;
        $this->id = $id;
    }

    //Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactionName(): string
    {
        return $this->faction_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
