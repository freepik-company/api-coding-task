<?php

use App\Controller\CreateCharactersController;
use App\Controller\GreetingController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new DI\ContainerBuilder();

$containerBuilder->addDefinitions([
    PDO::class => function () {
        return new PDO('mysql:host=db;dbname=lotr', 'root', 'root');
    }
]);

$container = $containerBuilder->build();

$app = AppFactory::create(container: $container);

$app->get('/hello/{id}', GreetingController::class);

$app->post('/characters', CreateCharactersController::class);

$app->run();