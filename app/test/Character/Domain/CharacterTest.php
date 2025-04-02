<?php

namespace App\Test\Character\Domain;

use App\Character\Domain\Character;
use App\Character\Domain\Exception\CharacterValidationException;
use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    /**
     * @test
     * @group characterValidation
     * @group unit
     */

     public function givenAnEmptyNameWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Name is required');

        $sut = new Character(
            '', // empty name
            '1990-01-01', // valid birth date
            'Gondor', // valid kingdom
            1, // valid equipment id
            1, // valid faction id
        );
     }

         /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAnEmptyBirthDateWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Birth date is required');

        $sut = new Character(
            'Aragorn',
            '', // empty birth date
            'Gondor', // valid kingdom
            1, // valid equipment id
            1, // valid faction id
        );
     }
        
     /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAnInvalidBirthDateFormatWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Birth date is invalid');

        $sut = new Character(
            'Aragorn',
            '202-01-01', // invalid birth date
            'Gondor', // valid kingdom
            1, // valid equipment id
            1, // valid faction id
        );
     }

     /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAnEmptyKingdomWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Kingdom is required');

        $sut = new Character(
            'Aragorn',  
            '1990-01-01', // valid birth date
            '', // empty kingdom
            1, // valid equipment id
            1, // valid faction id
        );
     }  

     /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAnEmptyEquipmentIdWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Equipment ID is required');

        $sut = new Character(
            'Aragorn',
            '1990-01-01', // valid birth date
            'Gondor', // valid kingdom
            0, // empty equipment id
            1, // valid faction id
        );
     }      
     //Not needed because the equipment id positive is not validated in the constructor Character.php
    //  /**
    //  * @test
    //  * @group characterValidation
    //  * @group unit
    //  */
    //  public function givenANonPositiveEquipmentIdWhenValidateThenExceptionShouldBeRaised(){
    //     $this->expectException(CharacterValidationException::class);
    //     $this->expectExceptionMessage('Equipment ID must be greater than 0');

    //     $sut = new Character(
    //         'Aragorn',
    //         '1990-01-01', // valid birth date
    //         'Gondor', // valid kingdom
    //         -1, // non positive equipment id
    //         1,
    //     );
    //  }

     /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAnEmptyFactionIdWhenValidateThenExceptionShouldBeRaised(){
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Faction ID is required');

        $sut = new Character(
            'Aragorn',
            '1990-01-01', // valid birth date
            'Gondor', // valid kingdom
            1, // valid equipment id
            0, // empty faction id
        );
     }

     //Not needed because the faction id positive is not validated in the constructor Character.php

    //  public function givenANonPositiveFactionIdWhenValidateThenExceptionShouldBeRaised(){
    //     $this->expectException(CharacterValidationException::class);
    //     $this->expectExceptionMessage('Faction ID must be greater than 0');

    //     $sut = new Character(
    //         'Aragorn',
    //         '1990-01-01', // valid birth date
    //         'Gondor', // valid kingdom
    //         1, // valid equipment id
    //         -1, // non positive faction id
    //     );
    //  }      
     
    /**
     * @test
     * @group characterValidation
     * @group unit
     */
     public function givenAValidCharacterWhenValidateThenExceptionShouldNotBeRaised(){
        $sut = new Character(
            'Aragorn',
            '1990-01-01', // valid birth date
            'Gondor', // valid kingdom
            1, // valid equipment id
            1, // valid faction id
        );

        $this->assertNotNull($sut);
        $this->assertEquals('Aragorn', $sut->getName());
        $this->assertEquals('1990-01-01', $sut->getBirthDate());
        $this->assertEquals('Gondor', $sut->getKingdom());
        $this->assertEquals(1, $sut->getEquipmentId());
        $this->assertEquals(1, $sut->getFactionId());
     }
}