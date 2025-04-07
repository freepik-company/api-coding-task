<?php

namespace App\Equipment\Application;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\EquipmentFactory;
use App\Equipment\Domain\EquipmentRepository;

class CreateEquipmentUseCase
{

    public function __construct(
        private EquipmentRepository $repository
    ) {}

    public function execute(
        CreateEquipmentUseCaseRequest $request
    ): Equipment {
        $equipment = EquipmentFactory::build(
            $request->getName(),
            $request->getType(),
            $request->getMadeBy()
        );

        return $this->repository->save($equipment);
    }
}
