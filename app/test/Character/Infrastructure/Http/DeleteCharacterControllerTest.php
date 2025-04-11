<?php

namespace App\Test\Character\Infrastructure\Http;

use App\Character\Application\DeleteCharacterUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Http\DeleteCharacterController;
use App\Character\Infrastructure\Persistence\Pdo\Exception\CharacterNotFoundException;
use App\Test\Shared\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;

class DeleteCharacterControllerTest extends BaseTestCase

/**
 * @test
 * @testdox Given a character id when delete character then return 204
 * @covers \App\Character\Infrastructure\Http\DeleteCharacterController
 */

{

    #[Test]
    #[Group('unit')]
    #[Group('deleteCharacter')]
    public function testSuccesfullDeletionReturns204()
    {
        /** @var \App\Character\Application\DeleteCharacterUseCase&\PHPUnit\Framework\MockObject\MockObject $useCaseMock */
        $useCaseMock = $this->createMock(DeleteCharacterUseCase::class);
        $useCaseMock->expects($this->once())
            ->method('execute')
            ->with(1);

        $controller = new DeleteCharacterController($useCaseMock);

        // ✅ Usamos createJsonRequest desde BaseTestCase
        $request = $this->createJsonRequest('DELETE', '/character/1');
        $response = new Response();

        $result = $controller($request, $response, ['id' => 1]);

        $this->assertEquals(204, $result->getStatusCode());

        // Verificamos el mensaje JSON
        $bodyContent = (string) $result->getBody();
        $this->assertJson($bodyContent);

        $body = json_decode($bodyContent, true);
        $this->assertEquals("Character correctly deleted", $body['message']);
    }

    /**
     * @test
     * @testdox Given a character id when delete character then return 404
     * @covers \App\Character\Infrastructure\Http\DeleteCharacterController
     */

    #[Test]
    #[Group('unit')]
    #[Group('deleteCharacter')]
    public function testCharacterNotFoundReturns404(): void
    {
        /** @var \App\Character\Application\DeleteCharacterUseCase&\PHPUnit\Framework\MockObject\MockObject $useCaseMock */
        $useCaseMock = $this->createMock(DeleteCharacterUseCase::class);
        $useCaseMock->expects($this->once())
            ->method('execute')
            ->with(99)
            ->willThrowException(new CharacterNotFoundException());

        $controller = new DeleteCharacterController($useCaseMock);

        $request = $this->createJsonRequest('DELETE', '/character/99');
        $response = new Response();

        $result = $controller($request, $response, ['id' => 99]);

        $this->assertEquals(404, $result->getStatusCode());

        $bodyContent = (string) $result->getBody();
        $this->assertJson($bodyContent);

        $body = json_decode($bodyContent, true);
        $this->assertEquals("Character doesn't exist", $body['error']);
    }
}
