<?php

namespace App\Test\Character\Infrastructure\Persistence\Pdo;

use App\Character\Domain\Character;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use PHPUnit\Framework\TestCase;
use PDO;

class MySQLCharacterRepositoryTest extends TestCase{


/**
 * @test
 * @group integration
 */

 public function givenARepositoryWithOneCharacterIdWhenReadCharacterThenReturnTheCharacter() {
    $repository = new MySQLCharacterRepository(
    $this->createPdoConnection()
        
 );

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
$this->assertEquals('1', $character->getEquipmentId());
$this->assertEquals('1', $character->getFactionId());
 }
}