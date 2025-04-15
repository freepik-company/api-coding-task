<?php

declare(strict_types=1);

namespace App\Test\Shared;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;


abstract class BaseTestCase extends TestCase
{
    protected function getAppInstanceWithCache(): App
    {
        return $this->buildAppInstance(true);
    }

    protected function getAppInstanceWithoutCache(): App
    {
        return $this->buildAppInstance(false);
    }

    private function buildAppInstance(bool $cacheEnabled): App
    {
        // Forzar variable de entorno
        putenv('CACHE_ENABLED=' . ($cacheEnabled ? '1' : '0'));
        $_ENV['CACHE_ENABLED'] = $cacheEnabled ? '1' : '0';
        $_SERVER['CACHE_ENABLED'] = $cacheEnabled ? '1' : '0';

        // Cargar .env solo si no está cargado
        if (!isset($_ENV['APP_ENV'])) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2)); // Ruta corregida
            $dotenv->load();
        }

        $containerBuilder = new ContainerBuilder();

        $definitions = require dirname(__DIR__, 2) . '/config/definitions.php';
        $definitions($containerBuilder);

        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $routes = require dirname(__DIR__, 2) . '/config/routes.php';
        $routes($app);

        foreach ($app->getRouteCollector()->getRoutes() as $route) {
            echo $route->getPattern() . PHP_EOL;
        }


        return $app;
    }

    protected function createJsonRequest(
        string $method,
        string $path,
        array $body = [],
        array $headers = ['Content-Type' => 'application/json']
    ): ServerRequestInterface {
        $streamFactory = new StreamFactory();
        $stream = $streamFactory->createStream(json_encode($body));
        $stream->rewind();

        $serverRequestFactory = new ServerRequestFactory();
        $request = $serverRequestFactory->createServerRequest($method, $path);
        return $request->withBody($stream)->withHeader('Content-Type', 'application/json');
    }


    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ServerRequestInterface {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    /**
     * Crea un mock de repositorio con `find()` que devuelve entidades por ID.
     *
     * @template T of object
     * @param class-string $repositoryInterface Ej: EquipmentRepository::class
     * @param T[] $entities Entidades que implementen getId()
     * @return T&MockObject
     */
    protected function mockRepositoryWithFind(string $repositoryInterface, array $entities): object
    {
        /** @var MockObject $repository */
        $repository = $this->createMock($repositoryInterface);

        $repository->method('find')
            ->willReturnCallback(function (int $id) use ($entities) {
                foreach ($entities as $entity) {
                    if ($entity->getId() === $id) {
                        return $entity;
                    }
                }
                return null;
            });

        return $repository;
    }
}
