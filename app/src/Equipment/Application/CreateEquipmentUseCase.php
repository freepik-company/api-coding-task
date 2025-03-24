<?php

namespace App\Equipment\Application;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\EquipmentRepository;

class CreateEquipmentUseCase
{
    public function __construct(private EquipmentRepository $repository)
    {
    }

    public function execute(
        string $name,
        string $type,
        string $madeBy
    ): Equipment
        {
            // Create equipment
            $equipment = new Equipment();
            $equipment->setName($name);
            $equipment->setType($type);
            $equipment->setMadeBy($madeBy);

            return $this->repository->save($equipment);
        }
}