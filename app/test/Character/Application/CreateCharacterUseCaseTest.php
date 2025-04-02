<?php

namespace App\Test\Character\Application;

use App\Character\Application\CreateCharacterUseCase;
use App\Character\Application\CreateCharacterUseCaseRequest;
use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Domain\Exception\CharacterValidationException;
use App\Character\Infrastructure\Persistence\InMemory\ArrayCharacterRepository;
use PHPUnit\Framework\TestCase;

class CreateCharacterUseCaseTest extends TestCase
{
    private CharacterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mockCharacterRepository([]);
    }

    private function mockCharacterRepository(array $characters): CharacterRepository
    {
        $repository = new ArrayCharacterRepository();
        foreach ($characters as $character) {
            $repository->save($character);
        }
        return $repository;
    }

    /**
     * @test
     * @group happy-path
     * @group unit
     * @group createCharacter
     */
    public function givenAValidCharacterWhenCreateThenReturnCharacter()
    {
        $sut = new CreateCharacterUseCase($this->repository);

        $request = new CreateCharacterUseCaseRequest(
            'Pepe',
            '1990-01-01',
            'Spain',
            1,
            1
        );

        $response = $sut->execute($request);
        $character = $response->getCharacter();

        $this->assertNull($character->getId());
        $this->assertEquals('Pepe', $character->getName());
        $this->assertEquals('1990-01-01', $character->getBirthDate());
        $this->assertEquals('Spain', $character->getKingdom());
        $this->assertEquals(1, $character->getEquipmentId());
        $this->assertEquals(1, $character->getFactionId());
    }

    /**
     * @test
     * @group unhappy-path
     * @group unit
     * @group createCharacter
     */
    public function givenAnInvalidCharacterWhenCreateThenExceptionShouldBeThrown()
    {
        $sut = new CreateCharacterUseCase($this->repository);

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Name is required');

        $request = new CreateCharacterUseCaseRequest(
            '',
            '1990-01-01',
            'Spain',
            1,
            1
        );

        $sut->execute($request);
    }
}
/* Como es un test unitario, no se puede conectar a la base de datos, por lo que se crea un mock de la clase ArrayCharacterRepository.
Se conecta a la base de datos en el test de integración.*/