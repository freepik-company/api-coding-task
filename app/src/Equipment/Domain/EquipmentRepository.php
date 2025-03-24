<?php

namespace App\Equipment\Domain;

interface EquipmentRepository
{
    public function save(Equipment $equipment): Equipment;
}