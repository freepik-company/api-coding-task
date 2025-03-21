<?php

use DI\Container;
use Slim\Factory\AppFactory;
use App\Controller\GreetingController;
use App\Controller\Create\CreateFactionsController;
use App\Controller\Read\ReadFactionsController;
use App\Controller\Create\CreateEquipmentsController;
use App\Controller\Read\ReadEquipmentsController;
use App\Controller\Read\ReadCharactersController;
use App\Controller\Create\CreateCharactersController;
use App\Controller\Read\ReadCharacterByIdController;
use Slim\Middleware\BodyParsingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Create Container
$container = new Container();

// Configure database connection
$container->set('db', function () {
    try {
        $db = new \PDO(
            "mysql:host=db;dbname=lotr",
            "root",
            "root",
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
        return $db;
    } catch (\PDOException $e) {
        throw new \Exception("Database connection failed: " . $e->getMessage());
    }
});

// Register controllers
$container->set(ReadCharacterByIdController::class, function (Container $c) {
    return new ReadCharacterByIdController($c->get('db'));
});

$container->set(ReadCharactersController::class, function (Container $c) {
    return new ReadCharactersController($c->get('db'));
});

// Create App
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);

// Add JSON parsing middleware
$app->add(new BodyParsingMiddleware());

// Add routes
$app->get('/', GreetingController::class);
$app->post('/factions', CreateFactionsController::class);
$app->get('/factions', ReadFactionsController::class);
$app->post('/equipments', CreateEquipmentsController::class);
$app->get('/equipments', ReadEquipmentsController::class);
$app->get('/characters', ReadCharactersController::class);
$app->get('/characters/{id}', ReadCharacterByIdController::class);
$app->post('/characters', CreateCharactersController::class);

$app->run();