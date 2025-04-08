<?php

namespace App\Faction\Application;

/**
 * This class generate a request with the faction data.
 *
 * @package App\Faction\Application
 */

class CreateFactionUseCaseRequest
{

    // This constructor is used to create a new faction. It is used to validate the data. The variables are private and readonly because we want to ensure that the data is private and cannot be changed.
    public function __construct(
        private readonly string $faction_name,
        private readonly string $description,
    ) {}

    public function getFactionName(): string
    {
        return $this->faction_name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
