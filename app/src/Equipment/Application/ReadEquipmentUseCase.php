<?php

namespace App\Equipment\Application;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\EquipmentRepository;

/**
 * ReadEquipmentUseCase is a class that is used to read an equipment.
 */

class ReadEquipmentUseCase
{

    public function __construct(
        private EquipmentRepository $repository
    ) {}

    public function execute(int $id): Equipment
    {
        return $this->repository->find($id);
    }
}
