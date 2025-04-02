<?php

namespace App\Test\Character\Infrastructure\Persistence\Pdo;

use App\Character\Domain\Character;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use PHPUnit\Framework\TestCase;
use PDO;

class MySQLCharacterRepositoryTest extends TestCase
{


    /**
     * This test is an integration test because it connects to the database
     * and it is a good example of how to use the repository pattern
     * @test
     * @group integration
     */

    public function givenARepositoryWithOneCharacterIdWhenReadCharacterThenReturnTheCharacter()
    {
        // Arrange create a new repository with a PDO connection
        $repository = new MySQLCharacterRepository(
            $this->createPdoConnection()

        );

        // Create a new character
        $character = new Character(
            'John Doe',
            '1990-01-01',
            'Kingdom of Doe',
            1,
            1
        );

        $character = $repository->save($character);

        $character = $repository->find($character->getId());

        $this->assertEquals('John Doe', $character->getName());
        $this->assertEquals('1990-01-01', $character->getBirthDate());
        $this->assertEquals('Kingdom of Doe', $character->getKingdom());
        $this->assertEquals(1, $character->getEquipmentId());
        $this->assertEquals(1, $character->getFactionId());
    }

    // This is a helper method to create a PDO connection
    private function createPdoConnection(): PDO
    {
        return new PDO('mysql:host=db;dbname=test', 'root', 'root');
    }
}
