<?php

namespace App\Test\Character\Infrastructure\Http;

use App\Character\Application\ReadAllCharactersUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Http\ReadAllCharactersController;
use App\Test\Character\Application\MotherObject\ReadAllCharactersUseCaseRequestMotherObject;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

class ReadAllCharactersControllerTest extends TestCase
{
    /** @var CharacterRepository&MockObject */
    private CharacterRepository $repository;
    private ReadAllCharactersUseCase $useCase;
    private ReadAllCharactersController $controller;

    protected function setUp(): void
    {
        /** @var CharacterRepository&MockObject $repository */
        $repository = $this->createMock(CharacterRepository::class);
        // @phpstan-ignore-next-line
        $this->repository = $repository;
        $this->useCase = new ReadAllCharactersUseCase($this->repository);
        $this->controller = new ReadAllCharactersController($this->useCase);
    }

    /**
     * @dataProvider provideCharacters
     * @group happy-path
     * @group acceptance
     * @group readAllCharactersController
     */
    public function testShouldReturnAllCharacters(array $characters): void
    {
        // Arrange
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($characters);

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/characters');
        $response = new Response();

        // Act
        $response = $this->controller->__invoke($request, $response, []);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertArrayHasKey('characters', $responseData);
        $this->assertCount(count($characters), $responseData['characters']);

        foreach ($characters as $index => $character) {
            $this->assertEquals($character->getName(), $responseData['characters'][$index]['name']);
            $this->assertEquals($character->getBirthDate(), $responseData['characters'][$index]['birth-date']);
            $this->assertEquals($character->getKingdom(), $responseData['characters'][$index]['kingdom']);
            $this->assertEquals($character->getEquipmentId(), $responseData['characters'][$index]['equipment-id']);
            $this->assertEquals($character->getFactionId(), $responseData['characters'][$index]['faction-id']);
            $this->assertEquals($character->getId(), $responseData['characters'][$index]['id']);
        }
    }

    public function provideCharacters(): array
    {
        return [
            'empty repository' => [
                ReadAllCharactersUseCaseRequestMotherObject::withEmptyRepository()
            ],
            'one character' => [
                ReadAllCharactersUseCaseRequestMotherObject::withOneCharacter()
            ],
            'multiple characters' => [
                ReadAllCharactersUseCaseRequestMotherObject::withMultipleCharacters()
            ],
            'three characters' => [
                ReadAllCharactersUseCaseRequestMotherObject::withThreeCharacters()
            ]
        ];
    }
}
