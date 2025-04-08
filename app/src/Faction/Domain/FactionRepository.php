<?php

namespace App\Faction\Domain;

/**
 * FactionRepository is an interface for the Faction repository.
 * It is used to define the methods that the repository must implement.
 *
 * @package App\Faction\Domain
 */

interface FactionRepository
{
    public function save(Faction $faction): Faction;
    public function find(int $id): ?Faction;
    public function findAll(): array;
    public function delete(Faction $faction): bool;
}
