<?php

namespace App\Test\Character\Application;

use App\Character\Application\ReadCharacterUseCase;
use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Persistence\InMemory\ArrayCharacerRepository;
use App\Character\Infrastructure\Persistence\Pdo\Exception\CharacterNotFoundException;
use PHPUnit\Framework\TestCase;

class ReadCharacterUseCaseTest extends TestCase{

/**
      * @test
      * @group happy-path
      * @group unit
      */
    public function givenARepositoryWithOneCharacterIdWhenReadCharacterThenReturnCharacter(){
        $sut = new ReadCharacterUseCase(
            $this->mockCharacterRepository([
                new Character(
                    'John Doe',
                    '1990-01-01',
                    'Kingdom of Doe',
                    1,
                    1,
                    1
                ),
            ])
        );

        $character = $sut->execute(1);

        $this->assertEquals(1, $character->getId());
        $this->assertEquals('John Doe', $character->getName());
        $this->assertEquals('1990-01-01', $character->getBirthDate());
        $this->assertEquals('Kingdom of Doe', $character->getKingdom());
        $this->assertEquals('1', $character->getEquipmentId());
        $this->assertEquals('1', $character->getFactionId());
    }
    

    private function mockCharacterRepository(array $characters): CharacterRepository{
        $repository = new ArrayCharacerRepository();
        foreach ($characters as $character){
            $repository->save($character);
        }
        return $repository;
    }

     /**
      * @test
      * @group unhappy-path
      * @group unit
      */
      public function givenARepositoryWithNonExistingCharacterIdWhenReadCharacterThenExceptionShouldBeRaised()
      {
          $sut = new ReadCharacterUseCase(
              $this->mockCharacterRepository([])
          );
  
          $this->expectException(CharacterNotFoundException::class);
  
          $sut->execute(1);
      }
  
}