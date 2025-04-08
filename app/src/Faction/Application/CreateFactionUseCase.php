<?php

namespace App\Faction\Application;

use App\Faction\Domain\Faction;
use App\Faction\Domain\FactionFactory;
use App\Faction\Domain\FactionRepository;

/**
 * CreateFactionUseCase is a class that is used to create a new faction.
 *
 * @package App\Faction\Application
 */

class CreateFactionUseCase
{
    public function __construct(
        private FactionRepository $repository
    ) {}

    public function execute(
        CreateFactionUseCaseRequest $request
    ): Faction {
        $faction = FactionFactory::build(
            $request->getFactionName(),
            $request->getDescription()
        );

        return $this->repository->save($faction);
    }
}
