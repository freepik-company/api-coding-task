<?php

namespace App\Test\Character\Infrastructure\Http;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Domain\CharacterToArrayTransformer;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class ReadAllCharactersControllerTest extends TestCase
{
    private App $app;
    private CharacterRepository $repository;
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->repository = $this->app->getContainer()->get(CharacterRepository::class);
        $this->pdo = $this->app->getContainer()->get(PDO::class);

        // Clean database before each test to ensure a fresh state
        $this->pdo->exec('DELETE FROM characters');  // First delete the characters
        $this->pdo->exec('DELETE FROM equipments');  // Then delete the equipments
        $this->pdo->exec('DELETE FROM factions');    // Finally delete the factions

        // Insert test data
        $this->pdo->exec('INSERT INTO equipments (id, name, type, made_by) VALUES (1, "Sword", "weapon", "Blacksmith")');
        $this->pdo->exec('INSERT INTO factions (id, faction_name, description) VALUES (1, "Alliance", "The Alliance faction")');
    }

    /**
     * @test
     * @group happy-path
     * @group acceptance
     * @group readAllCharacters
     */
    public function givenARepositoryWithMultipleCharactersWhenReadAllCharactersThenReturnCharactersAsJson(): void
    {
        // Crear y guardar múltiples personajes
        $character1 = new Character(
            'John Doe',
            '1990-01-01',
            'Kingdom of Doe',
            1,
            1
        );
        $character2 = new Character(
            'Jane Smith',
            '1992-05-15',
            'Kingdom of Smith',
            1,
            1
        );

        $character1 = $this->repository->save($character1);
        $character2 = $this->repository->save($character2);

        $request = $this->createRequest('GET', '/characters');
        $response = $this->app->handle($request);

        $payload = (string) $response->getBody();
        $serializedPayload = json_encode([
            'characters' => [
                CharacterToArrayTransformer::transform($character1),
                CharacterToArrayTransformer::transform($character2)
            ],
        ]);

        $this->assertEquals($serializedPayload, $payload);
    }

    private function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    private function getAppInstance(): App
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
        $dotenv->load();

        $containerBuilder = new ContainerBuilder();

        $settings = require __DIR__ . '/../../../../config/definitions.php';
        $settings($containerBuilder);

        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $routes = require __DIR__ . '/../../../../config/routes.php';
        $routes($app);

        return $app;
    }
}
