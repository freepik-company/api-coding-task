<?php

namespace App\Faction\Domain;

use App\Faction\Domain\Exception\FactionValidationException;

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
        if (empty($faction_name)) {
            throw FactionValidationException::factionNameRequired();
        }

        if (empty($description)) {
            throw FactionValidationException::factionDescriptionRequired();
        }

        if ($id !== null && $id <= 0) {
            throw FactionValidationException::idNonPositive();
        }

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
