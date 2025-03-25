<?php

namespace App\Equipment\Domain;

interface EquipmentRepository
{
    public function save(Equipment $equipment): Equipment;
    public function find(int $id): ?Equipment;
    public function findAll(): array;
    public function delete(Equipment $equipment): bool;
}