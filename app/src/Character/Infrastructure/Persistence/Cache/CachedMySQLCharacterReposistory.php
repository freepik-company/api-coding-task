<?php

namespace App\Character\Infrastructure\Persistence\Cache;

use App\Character\Domain\Character;
use App\Character\Domain\CharacterRepository;
use App\Character\Infrastructure\Persistence\MySQLCharacterRepository;
use Psr\Log\LoggerInterface;
use Redis;

class CachedMySQLCharacterReposistory implements CharacterRepository
{
    public function __construct(
        private MySQLCharacterRepository $mySQLCharacterRepository,
        private Redis $redis,
        private ?LoggerInterface $logger
    ) {}

    public function find(int $id): ?Character
    {
        $cachedCharacter = $this->redis->get($id);
        if ($cachedCharacter) {
            $this->logger->info("Character foun in cache", ['id' => $id]);

            return unserialize($cachedCharacter);
        }

        $character = $this->mySQLCharacterRepository->find($id);
        $this->redis->set($id, serialize($character));

        return $character;
    }

    public function findAll(): array
    {
        $cachedCharacters = $this->redis->get('all');
        if ($cachedCharacters) {
            $this->logger->info('Getting all characters from cache');

            return unserialize($cachedCharacters);
        }

        $characters = $this->mySQLCharacterRepository->findAll();
        $this->redis->set('all', serialize($characters));

        return $characters;
    }

    public function save(Character $character): Character
    {
        $savedCharacter = $this->mySQLCharacterRepository->save($character);

        $this->redis->set($savedCharacter->getId(), serialize($savedCharacter));

        return $savedCharacter;
    }

    public function delete(Character $character): bool
    {
        $this->mySQLCharacterRepository->delete($character);
        $this->redis->del($character->getId());

        return true;
    }

    public function findByName(string $name): ?Character
    {
        $cachedCharacter = $this->redis->get($name);
        if ($cachedCharacter) {
            $this->logger->info("Character found in cache", ['name' => $name]);
            
            return unserialize($cachedCharacter);
        }

        $character = $this->mySQLCharacterRepository->findByName($name);
        $this->redis->set($name, serialize($character));
        
        return $character;
    }

}
