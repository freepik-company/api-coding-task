<?php

namespace App\Equipment\Application;

use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Persistance\Pdo\Exception\EquipmentNotFoundException as ExceptionEquipmentNotFoundException;

class DeleteEquipmentUseCase
{
    public function __construct(
        private EquipmentRepository $repository
    ) {}

    public function execute(string $id): void
    {
        $equipment = $this->repository->find($id);

        if (!$equipment) {
            throw ExceptionEquipmentNotFoundException::build($id);
        }

        $this->repository->delete($equipment);
    }
}
