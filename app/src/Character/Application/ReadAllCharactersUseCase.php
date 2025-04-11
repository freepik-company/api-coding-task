<?php

namespace App\Character\Application;

use App\Character\Domain\CharacterRepository;

class ReadAllCharactersUseCase
{
    public function __construct(
        private CharacterRepository $characterRepository
    ) {}

    public function execute(): array
    {
        return $this->characterRepository->findAll();
    }
}
