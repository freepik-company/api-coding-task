<?php

namespace App\Faction\Application;

use App\Faction\Domain\Faction;
use App\Faction\Domain\FactionRepository;

/**
 * UpdateFactionUseCase is a class that is used to update a faction.
 *
 * @package App\Faction\Application
 */

class UpdateFactionUseCase
{
    public function __construct(
        private FactionRepository $repository
    ) {}

    public function execute(
        UpdateFactionUseCaseRequest $request
    ): Faction {
        $oldFaction = $this->repository->find($request->getId());

        if (!$oldFaction) {
            throw new  \Exception('Faction not found');
        }

        $updatedFaction = new Faction(
            faction_name: $request->getFactionName(),
            description: $request->getDescription(),
            id: $oldFaction->getId()
        );

        return $this->repository->save($updatedFaction);
    }
}
