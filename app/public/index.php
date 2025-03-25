<?php

use App\Character\Application\CreateCharacterUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Domain\Validation\CharacterValidatorBuilder;
use App\Character\Infrastructure\Http\CreateCharacterController;
use App\Character\Infrastructure\Persistence\MySQLCharacterRepository;
use App\Equipment\Application\CreateEquipmentUseCase;
use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Http\CreateEquipmentController;
use App\Equipment\Infrastructure\MySQLEquipmentRepository;
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
            $c->get(CharacterRepository::class),
            $c->get(CharacterValidatorBuilder::class)
        );
    },
    CharacterValidatorBuilder::class => function (ContainerInterface $c){
        return new CharacterValidatorBuilder($c->get(CharacterRepository::class));
    },
    // Define the EquipmentRepository
    EquipmentRepository::class => function (ContainerInterface $c){
        return new MySQLEquipmentRepository($c->get(PDO::class));
    },
    CreateEquipmentUseCase::class => function (ContainerInterface $c){
        return new CreateEquipmentUseCase(
            $c->get(EquipmentRepository::class)
        );
    },
]);

$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);

// Add routes
$app->post('/characters', CreateCharacterController::class);
$app->post('/equipments', CreateEquipmentController::class);
$app->run();