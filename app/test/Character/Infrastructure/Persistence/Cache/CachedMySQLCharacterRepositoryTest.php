<?php

namespace App\Test\Character\Infrastructure\Persistence\Cache;

use App\Character\Domain\Character;
use App\Character\Infrastructure\Persistence\Cache\CachedMySQLCharacterRepository;
use App\Character\Infrastructure\Persistence\Pdo\MySQLCharacterRepository;
use PDO;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\NullLogger;
use Redis;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Before;


class CachedMySQLCharacterRepositoryTest extends TestCase
{

    private Redis $redis; // Mocked Redis instance
    private CachedMySQLCharacterRepository $repository; // The repository to test
    private int $equipmentId;
    private int $factionId;

    #[Before]
    public function init(): void // New Setup method
    {
        $this->redis = new Redis();
        $this->redis->connect("cache", 6379);
        $this->redis->flushAll(); // Clear the Redis cache before each test

        $pdo = new PDO("mysql:host=db;dbname=test", "root", "root");

        // Insert equipment and get id
        $pdo->exec("INSERT INTO equipments (name, type, made_by) VALUES ('Test Equipment', 'weapon', 'test')");
        $equipmentId = (int)($pdo->lastInsertId());

        // Insert faction and get id
        $pdo->exec("INSERT INTO factions (faction_name, description) VALUES ('Test Faction', 'Test Description')");
        $factionId = (int)($pdo->lastInsertId());


        // Save equipment and faction ids to use in tests
        $this->equipmentId = $equipmentId;
        $this->factionId = $factionId;

        $mySQLRepo = new MySQLCharacterRepository($pdo); // MySQL repository instance

        $this->repository = new CachedMySQLCharacterRepository($mySQLRepo, $this->redis, new NullLogger());

        error_reporting(E_ALL);
    }


    #[Test]
    #[Group('cache')]
    public function testCharacterIsCachedAfterSave(): void
    {
        $character = new Character("John Doe", "1990-01-01", "Spain", $this->equipmentId, $this->factionId);
        $saved = $this->repository->save($character);

        $key = 'App\Character\Infrastructure\Persistence\Cache\CachedMySQLCharacterRepository:' . $saved->getId();
        $cached = $this->redis->get($key);

        $this->assertNotFalse($cached);
        $this->assertEquals($saved, unserialize($cached));
    }

    #[Test]
    #[Group('cache')]
    public function testFindReturnCharacterFromCache(): void
    {
        $character = new Character("John Doe", "1990-01-01", "Spain", $this->equipmentId, $this->factionId);
        $saved = $this->repository->save($character);

        // First call and save to cache
        $this->repository->find($saved->getId());

        // Second call should return from cache
        $cached = $this->repository->find($saved->getId());

        $this->assertEquals($saved, $cached);
    }

    #[After]
    protected function cleanUp(): void // New TearDown method
    {
        $this->redis->flushAll(); // Clear the Redis cache after each test
    }
}
