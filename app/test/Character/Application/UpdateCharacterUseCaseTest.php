<?php

namespace App\Test\Character\Application;

use App\Character\Application\UpdateCharacterUseCase;
use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Persistence\InMemory\ArrayCharacterRepository;
use App\Test\Character\Application\MotherObject\UpdateCharacterUseCaseRequestMotherObject;
use PHPUnit\Framework\TestCase;

class UpdateCharacterUseCaseTest extends TestCase
{
    /**
     * @test
     * @group happy-path
     * @group unit
     * @group updateCharacter
     */

    public function givenARequestWithValidDataWhenUpdateCharacterThenReturnSuccess()
    {
        $request = UpdateCharacterUseCaseRequestMotherObject::valid();

        // Crear un personaje existente en el repositorio
        $existingCharacter = new Character(
            'Original Name',
            '1980-01-01',
            'Original Kingdom',
            1,
            1
        );

        // Crear el repositorio y guardar el personaje existente
        $repository = new ArrayCharacterRepository();
        $existingCharacter = $repository->save($existingCharacter);

        // Crear el caso de uso con el repositorio que contiene el personaje
        $sut = new UpdateCharacterUseCase($repository);

        $result = $sut->execute($request);

        $this->assertEquals($existingCharacter->getId(), $result->getId());
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('1990-01-01', $result->getBirthDate());
        $this->assertEquals('Kingdom of Spain', $result->getKingdom());
        $this->assertEquals(1, $result->getEquipmentId());
    }

    private function mockCharacterRepository(array $characters): CharacterRepository
    {
        $repository = new ArrayCharacterRepository();

        foreach ($characters as $character) {
            $repository->save($character);
        }

        return $repository;
    }
}
