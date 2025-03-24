<?php

use App\Character\Application\CreateCharacterUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Http\CreateCharactersController;
use App\Character\Infrastructure\MySQLCharacterRepository;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Create Container
$containerBuilder = new DI\ContainerBuilder();

$containerBuilder->addDefinitions([
    PDO::class => function(){
        return new PDO('mysql:host=db; dbname=lotr', 'root', 'root');
    },
    CharacterRepository::class => function (ContainerInterface $c){
        return new MySQLCharacterRepository($c->get(PDO::class));
    },
    CreateCharacterUseCase::class => function (ContainerInterface $c){
        return new CreateCharacterUseCase(
            $c->get(CharacterRepository::class)
        );
    },
]);

$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);

$app->post('/characters', CreateCharactersController::class);

$app->run();

// Configure database connection
// $container->set('db', function () {
//     try {
//         $db = new \PDO(
//             "mysql:host=db;dbname=lotr",
//             "root",
//             "root",
//             [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
//         );
//         return $db;
//     } catch (\PDOException $e) {
//         throw new \Exception("Database connection failed: " . $e->getMessage());
//     }
// });

// Register controllers
// $container->set(ReadCharacterByIdController::class, function (Container $c) {
//     return new ReadCharacterByIdController($c->get('db'));
// });

// $container->set(ReadCharactersController::class, function (Container $c) {
//     return new ReadCharactersController($c->get('db'));
// });

// Create App
//AppFactory::setContainer($container);
//$app = AppFactory::create();

// Add Error Middleware
//$app->addErrorMiddleware(true, true, true);

// Add JSON parsing middleware
//$app->add(new BodyParsingMiddleware());

// Add routes
//$app->get('/', GreetingController::class);
// $app->post('/factions', CreateFactionsController::class);
// $app->get('/factions', ReadFactionsController::class);
// $app->post('/equipments', CreateEquipmentsController::class);
// $app->get('/equipments', ReadEquipmentsController::class);
// $app->get('/characters', ReadCharactersController::class);
//$app->get('/characters/{id}', ReadCharacterByIdController::class);
//$app->post('/characters', CreateCharactersController::class);

//$app->post('/characters', CreateCharactersController::class);
