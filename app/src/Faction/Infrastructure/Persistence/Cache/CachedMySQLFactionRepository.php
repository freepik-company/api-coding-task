<?php

namespace App\Faction\Infrastructure\Persistence\Cache;

use App\Faction\Domain\Faction;
use App\Faction\Domain\FactionRepository;
use App\Faction\Infrastructure\Persistence\Pdo\MySQLFactionRepository;
use Psr\Log\LoggerInterface;
use Redis;

/**
 * CachedMySQLEquipmentRepository is a class that is used to cache the equipment repository.
 *
 * @package App\Faction\Infrastructure\Cache
 */

class CachedMySQLFactionRepository implements FactionRepository
{
    public function __construct(
        private MySQLFactionRepository $repository,
        private Redis $redis,
        private LoggerInterface $logger
    ) {}

    public function find(int $id): ?Faction
    {
        $cacheKey = "faction:{$id}";
        $cached = $this->redis->get($cacheKey);

        if ($cached) {
            $this->logger->debug("Cache hit for faction {$id}");
            return unserialize($cached);
        }

        $this->logger->debug("Cache miss for faction {$id}");
        $faction = $this->repository->find($id);

        return $faction;
    }

    public function findAll(): array
    {
        $cacheKey = "factions:all";
        $cached = $this->redis->get($cacheKey);

        if ($cached) {
            $this->logger->debug("Cache hit for all factions");
            return unserialize($cached);
        }

        $this->logger->debug("Cache miss for all factions");
        return $this->repository->findAll();
    }

    public function save(Faction $faction): Faction
    {
        $savedFaction = $this->repository->save($faction);

        // Invalidate cache
        $this->redis->del("faction:{$savedFaction->getId()}");
        $this->redis->del("factions:all");

        return $savedFaction;
    }

    public function delete(Faction $faction): bool
    {
        $result = $this->repository->delete($faction);

        if ($result && $faction->getId()) {
            // Invalidate cache
            $this->redis->del("faction:{$faction->getId()}");
            $this->redis->del("factions:all");
        }

        return $result;
    }
}
