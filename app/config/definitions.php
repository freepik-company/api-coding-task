<?php

namespace App\Config;

use App\Character\Application\CreateCharacterUseCase;
use App\Character\Application\DeleteCharacterUseCase;
use App\Character\Application\ReadAllCharactersUseCase;
use App\Character\Application\ReadCharacterUseCase;
use App\Character\Application\UpdateCharacterUseCase;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Persistence\Cache\CachedMySQLCharacterRepository;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use App\Equipment\Application\CreateEquipmentUseCase;
use App\Equipment\Domain\EquipmentRepository;
use App\Equipment\Infrastructure\Persistance\Pdo\MySQLEquipmentRepository;
use App\Equipment\Infrastructure\Persistence\Cache\CachedMySQLEquipmentRepository;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Redis;

/**
 * This file is used to define the dependencies of the application.
 * It is used by the ContainerBuilder to build the container.
 * 
 * @param ContainerBuilder $containerBuilder
 * @return void
 */

return function (ContainerBuilder $containerBuilder) {


    $containerBuilder->addDefinitions([
        PDO::class => function () {
            $conn = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
            );


            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;
        },

        /**
         * Redis is used to cache the characters.
         * 
         * @return Redis
         */
        Redis::class => function () {
            return new Redis([
                'host' => $_ENV['REDIS_HOST'],
                'port' => (int) $_ENV['REDIS_PORT'],
            ]);
        },

        /**
         * Logger is used to log the messages.
         * 
         * @return LoggerInterface
         */
        LoggerInterface::class => function () {
            if ((bool) $_ENV['DEBUG_MODE']) {
                $logger = new Logger('app');
                $logger->pushHandler(new StreamHandler(('php://stdout'), Level::Debug));

                return $logger;
            }


            return new NullLogger();
        },

        /**
         * CharacterRepository is used to get the characters from the database.
         * 
         * @param ContainerInterface $c
         * @return CharacterRepository
         */
        CharacterRepository::class => function (ContainerInterface $c) {
            if ((bool) ((int) $_ENV['CACHE_ENABLED'])) {
                return new CachedMySQLCharacterRepository(
                    // Inject the PDO connection from the container instead of creating a new one
                    new MySQLCharacterRepository($c->get(PDO::class)),
                    $c->get(Redis::class),
                    $c->get(LoggerInterface::class),

                );
            }

            return new MySQLCharacterRepository(
                $c->get(PDO::class)
            );
        },

        /**
         * CreateCharacterUseCase is used to create a new character.
         * 
         * @param ContainerInterface $c
         * @return CreateCharacterUseCase
         */
        CreateCharacterUseCase::class => function (ContainerInterface $c) {
            return new CreateCharacterUseCase(
                $c->get(CharacterRepository::class)
            );
        },

        /**
         * ReadCharacterUseCase is used to read a character by id.
         * 
         * @param ContainerInterface $c
         * @return ReadCharacterUseCase
         */
        ReadCharacterUseCase::class => function (ContainerInterface $c) {
            return new ReadCharacterUseCase(
                $c->get(CharacterRepository::class)
            );
        },
        // Read all characters
        ReadAllCharactersUseCase::class => function (ContainerInterface $c) {
            return new ReadAllCharactersUseCase(
                $c->get(CharacterRepository::class)
            );
        },
        // Delete a character by id
        DeleteCharacterUseCase::class => function (ContainerInterface $c) {
            return new DeleteCharacterUseCase(
                $c->get(CharacterRepository::class)
            );
        },
        // Update a character by id
        UpdateCharacterUseCase::class => function (ContainerInterface $c) {
            return new UpdateCharacterUseCase(
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
};
