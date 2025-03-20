<?php

use DI\Container;
use Slim\Factory\AppFactory;
use App\Controller\GreetingController;
use App\Controller\CreateFactionsController;
use App\Controller\GetFactionsController;
use App\Controller\CreateEquipmentsController;
use App\Controller\GetEquipmentsController;
use App\Controller\GetCharactersController;
use App\Controller\CreateCharactersController;
use Slim\Middleware\BodyParsingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Create Container
$container = new Container();

// Configure database connection
$container->set(\PDO::class, function () {
    $host = getenv('DB_HOST') ?: 'db';
    $dbname = getenv('DB_NAME') ?: 'lotr';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'root';

    try {
        $pdo = new \PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (\PDOException $e) {
        throw new \Exception("Database connection failed: " . $e->getMessage());
    }
});

// Create App
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);

// Add JSON parsing middleware with debug logging
$app->add(new BodyParsingMiddleware());

// Add routes
$app->get('/', GreetingController::class);
$app->post('/factions', CreateFactionsController::class);
$app->get('/factions', GetFactionsController::class);
$app->post('/equipments', CreateEquipmentsController::class);
$app->get('/equipments', GetEquipmentsController::class);
$app->get('/characters', GetCharactersController::class);
$app->post('/characters', CreateCharactersController::class);

$app->run();