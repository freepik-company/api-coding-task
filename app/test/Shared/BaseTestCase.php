<?php

declare(strict_types=1);

namespace App\Test\Shared;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

abstract class BaseTestCase extends TestCase
{
    protected function getAppInstance(bool $cacheEnabled = false): App
    {
        // Forzar variable de entorno para habilitar o deshabilitar caché
        putenv('CACHE_ENABLED=' . ($cacheEnabled ? '1' : '0'));
        $_ENV['CACHE_ENABLED'] = $cacheEnabled ? '1' : '0';
        $_SERVER['CACHE_ENABLED'] = $cacheEnabled ? '1' : '0';

        // Cargar .env si no está ya cargado
        if (!isset($_ENV['APP_ENV'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $containerBuilder = new ContainerBuilder();
        $definitions = require __DIR__ . '/../../../config/definitions.php';
        $definitions($containerBuilder);

        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $routes = require __DIR__ . '/../../../config/routes.php';
        $routes($app);

        return $app;
    }

    protected function createJsonRequest(
        string $method,
        string $path,
        array $body = [],
        array $headers = ['Content-Type' => 'application/json']
    ): ServerRequestInterface {
        $uri = new Uri('', '', 80, $path);

        $streamFactory = new StreamFactory();
        $stream = $streamFactory->createStream(json_encode($body));

        $slimHeaders = new Headers();
        foreach ($headers as $name => $value) {
            $slimHeaders->addHeader($name, $value);
        }

        return new SlimRequest(
            $method,
            $uri,
            $slimHeaders,
            [],
            [],
            $stream
        );
    }
}
