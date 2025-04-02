<?php

namespace App\Test\Character\Application;

use App\Character\Application\DeleteCharacterUseCase;
use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use App\Character\Infrastructure\Persistence\Pdo\Exception\CharacterNotFoundException;
use PHPUnit\Framework\TestCase;
use PDO;

class DeleteCharacterUseCaseTest extends TestCase
{
    private CharacterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MySQLCharacterRepository(
            new PDO(
                'mysql:host=db;dbname=test',
                'root',
                'root'
            )
        );
    }

    /**
     * @test
     * @group happy-path
     * @group unit
     * @group deleteCharacter
     */
    public function givenAValidCharacterWhenDeleteThenReturnTrue()
    {
        $sut = new DeleteCharacterUseCase($this->repository);
        $sut->execute(2); // Eliminamos el personaje con ID 2

        // Verificamos que ya no existe
        $this->expectException(CharacterNotFoundException::class);
        $this->repository->find(2);
    }

    /**
     * @test
     * @group unhappy-path
     * @group unit
     * @group deleteCharacter
     */
    public function givenAnInvalidCharacterWhenDeleteThenExceptionShouldBeThrown()
    {
        $sut = new DeleteCharacterUseCase($this->repository);

        $this->expectException(CharacterNotFoundException::class);
        $this->expectExceptionMessage('Character not found');

        $sut->execute(999999); // ID que no existe
    }
}
