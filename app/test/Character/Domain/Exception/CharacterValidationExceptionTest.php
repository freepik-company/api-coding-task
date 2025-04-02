<?php

namespace App\Test\Character\Domain\Exception;

use App\Character\Domain\Exception\CharacterValidationException;
use PHPUnit\Framework\TestCase;

class CharacterValidationExceptionTest extends TestCase
{
    /**
     * @test
     * @group validation
     * @group unit
     */

     // Every test follows the pattern AAA (Arrange, Act, Assert)
    public function givenAnEmptyNameWhenValidateThenExceptionShouldBeRaised()
    {
        // Arrange, prepare the test
        $sut = CharacterValidationException::nameRequired();

        // Act, execute the test, expectations and messages
        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Name is required');

        // Assert, verify the test
        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenAnEmptyBirthDateWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::birthDateRequired();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Birth date is required');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenAnInvalidBirthDateFormatWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::birthDateInvalidFormat();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Birth date is invalid');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenAnEmptyKingdomWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::kingdomRequired();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Kingdom is required');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenAnEmptyEquipmentIdWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::equipmentIdRequired();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Equipment ID is required');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenANonPositiveEquipmentIdWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::equipmentIdNonPositive();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Equipment ID must be greater than 0');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenAnEmptyFactionIdWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::factionIdRequired();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Faction ID is required');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenANonPositiveFactionIdWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::factionIdNonPositive();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('Faction ID must be greater than 0');

        throw $sut;
    }

    /**
     * @test
     * @group validation
     * @group unit
     */
    public function givenANonPositiveIdWhenValidateThenExceptionShouldBeRaised()
    {
        $sut = CharacterValidationException::idNonPositive();

        $this->expectException(CharacterValidationException::class);
        $this->expectExceptionMessage('ID must be greater than 0');

        throw $sut;
    }
}