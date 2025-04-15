<?php

namespace App\Test\Equipment\Application;

use App\Equipment\Application\ReadAllEquipmentUseCase;
use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Persistence\InMemory\ArrayEquipmentRepository;
use App\Test\Equipment\Application\MotherObject\ReadAllEquipmentUseCaseRequestMotherObject;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Test\Shared\BaseTestCase;

class ReadAllEquipmentUseCaseTest extends BaseTestCase
{

    /** @var EquipmentRepository|\PHPUnit\Framework\MockObject\MockObject */
    private $repository;
    private ReadAllEquipmentUseCase $useCase;

    protected function setUp(): void
    {
        /** @var EquipmentRepository&\PHPUnit\Framework\MockObject\MockObject $repository */
        $repository = $this->createMock(EquipmentRepository::class);
        // @phpstan-ignore-next-line
        $this->repository = $repository;
        $this->useCase = new ReadAllEquipmentUseCase($this->repository);
    }



    #[Test]
    #[Group('happy-path')]
    #[Group('unit')]
    #[Group('readAllEquipment')]
    #[DataProvider('provideEquipment')]
    public function testShouldReturnAllEquipment(array $equipments): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($equipments);

        $result = $this->useCase->execute();

        $this->assertCount(count($equipments), $result);
        foreach ($equipments as $index => $equipment) {
            $this->assertEquals($equipment->getName(), $result[$index]->getName());
            $this->assertEquals($equipment->getType(), $result[$index]->getType());
            $this->assertEquals($equipment->getMadeBy(), $result[$index]->getMadeBy());
            $this->assertEquals($equipment->getId(), $result[$index]->getId());
        }
    }

    #[Test]
    #[Group('unhappy-path')]
    #[Group('unit')]
    #[Group('readAllEquipment')]
    #[DataProvider('provideEquipment')]
    public function testShouldThrowExceptionIfRepositoryFails(array $equipments): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willThrowException(new \RuntimeException('Database error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Database error');

        $this->useCase->execute();
    }


    public static function provideEquipment(): array
    {
        return [
            'empty repository' => [
                ReadAllEquipmentUseCaseRequestMotherObject::withEmptyRepository()
            ],
            'one equipment' => [
                ReadAllEquipmentUseCaseRequestMotherObject::valid()
            ],
            'multiple equipment' => [
                ReadAllEquipmentUseCaseRequestMotherObject::withMultipleEquipment()
            ],
        ];
    }
}
