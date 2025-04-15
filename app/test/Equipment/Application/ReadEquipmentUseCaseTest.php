<?php

namespace App\Test\Equipment\Application;

use App\Equipment\Application\ReadEquipmentUseCase;
use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Persistence\Pdo\Exception\EquipmentNotFoundException;
use App\Test\Equipment\Application\MotherObject\ReadEquipmentUseCaseRequestMotherObject;
use App\Test\Shared\BaseTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class ReadEquipmentUseCaseTest extends BaseTestCase
{
    private $repository;
    private $useCase;

    protected function setUp(): void
    {
        $equipment = ReadEquipmentUseCaseRequestMotherObject::valid();

        /** @var EquipmentRepository&\PHPUnit\Framework\MockObject\MockObject $repository */
        $repository = $this->mockRepositoryWithFind(EquipmentRepository::class, [$equipment]);
        // @phpstan-ignore-next-line
        $this->repository = $repository;
        $this->useCase = new ReadEquipmentUseCase($this->repository);
    }

    #[Test]
    #[Group('happy-path')]
    #[Group('unit')]
    #[Group('readEquipment')]
    #[DataProvider('provideEquipment')]
    public function givenARepositoryWithOneEquipmentIdWhenReadCharacterThenReturnEquipment(): void
    {
        $equipment = ReadEquipmentUseCaseRequestMotherObject::valid();

        $restul = $this->useCase->execute($equipment->getId());

        $this->assertEquals($equipment->getId(), $restul->getId());
        $this->assertEquals($equipment->getName(), $restul->getName());
        $this->assertEquals($equipment->getType(), $restul->getType());
        $this->assertEquals($equipment->getMadeBy(), $restul->getMadeBy());
    }

    public static function provideEquipment(): array
    {
        return [
            [ReadEquipmentUseCaseRequestMotherObject::valid()],
            [ReadEquipmentUseCaseRequestMotherObject::withInvalidId()],
        ];
    }
}
