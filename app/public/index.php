<?php

use App\Character\Application\CreateCharacterUseCase;
use App\Character\Application\ReadAllCharactersUseCase;
use App\Character\Application\ReadCharacterUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Http\CreateCharacterController;
use App\Character\Infrastructure\Http\ListCharactersController;
use App\Character\Infrastructure\Http\ReadCharacterController;
use App\Character\Infrastructure\Persistence\Cache\CachedMySQLCharacterRepository;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use App\Equipment\Application\CreateEquipmentUseCase;
use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Http\CreateEquipmentController;
use App\Equipment\Infrastructure\Persistance\Pdo\MySQLEquipmentRepository;
use App\Equipment\Infrastructure\Persistence\Cache\CachedMySQLEquipmentRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;

/* Alternativa a BodyParsingMiddleware sería usar: $data->json_decode(file_get_contents('php://input'), true); 
habria que ponerlo en el controlador*/

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create Container
$containerBuilder = new DI\ContainerBuilder();

$containerBuilder->addDefinitions([
    PDO::class => function () {
        $conn = new PDO(
            'mysql:host=db;dbname=' . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    },

    // Redis connection
    Redis::class => function () {
        return new Redis(
            [
                'host' => $_ENV['REDIS_HOST'],
                'port' => (int) $_ENV['REDIS_PORT']
            ]
        );
    },

    // Logger configuration
    LoggerInterface::class => function () {
        if ((bool) ((int) $_ENV['DEBUG_MODE'])) {
            $logger = new Logger('app');
            $logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));

            return $logger;
        }

        return new NullLogger();
    },

    CharacterRepository::class => function (ContainerInterface $c) {
        if ((bool) ((int) $_ENV['CACHE_ENABLED'])) {
            return new CachedMySQLCharacterRepository(
                new MySQLCharacterRepository($c->get(PDO::class)),
                $c->get(Redis::class),
                $c->get(LoggerInterface::class)
            );
        }
        return new MySQLCharacterRepository($c->get(PDO::class));
    },
    CreateCharacterUseCase::class => function (ContainerInterface $c) {
        return new CreateCharacterUseCase(
            $c->get(CharacterRepository::class)
        );
    },
    // Read all characters
    ReadAllCharactersUseCase::class => function (ContainerInterface $c) {
        return new ReadAllCharactersUseCase(
            $c->get(CharacterRepository::class)
        );
    },
    // Read a character by id
    ReadCharacterUseCase::class => function (ContainerInterface $c) {
        return new ReadCharacterUseCase(
            $c->get(CharacterRepository::class)
        );
    },
    // Define the EquipmentRepository
    EquipmentRepository::class => function (ContainerInterface $c) {
        if ((bool) ((int) $_ENV['CACHE_ENABLED'])) {
            return new CachedMySQLEquipmentRepository(
                new MySQLEquipmentRepository($c->get(PDO::class)),
                $c->get(Redis::class),
                $c->get(LoggerInterface::class)
            );
        }
        return new MySQLEquipmentRepository($c->get(PDO::class));
    },
    CreateEquipmentUseCase::class => function (ContainerInterface $c) {
        return new CreateEquipmentUseCase(
            $c->get(EquipmentRepository::class)
        );
    },

]);

$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);

// Add middleware
$app->add(new BodyParsingMiddleware());

// Add routes
$app->post('/characters', CreateCharacterController::class);
$app->get('/characters', ListCharactersController::class);
$app->get('/character/{id}', ReadCharacterController::class);
$app->post('/equipments', CreateEquipmentController::class);
$app->run();
