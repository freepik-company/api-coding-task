<?php

namespace App\Model;

use PDO;

/**
 * Character model class that represents a character in the Lord of the Rings universe.
 * 
 * This class handles all database operations related to characters, including:
 * - Retrieving characters by ID or name
 * - Listing all characters
 * - Creating new characters
 * - Updating existing characters
 * - Deleting characters
 * 
 * @package App\Model
 */
class Character
{
    /** @var int The unique identifier of the character */
    private int $id;

    /** @var string The name of the character */
    private string $name;

    /** @var string The birth date of the character in YYYY-MM-DD format */
    private string $birth_date;

    /** @var string The kingdom where the character belongs */
    private string $kingdom;

    /** @var int The ID of the character's equipment */
    private int $equipment_id;

    /** @var int The ID of the character's faction */
    private int $faction_id;

    /** @var string The name of the character's faction */
    private string $faction_name;

    /** @var array Array of equipment IDs associated with the character */
    private array $equipment_ids;

    /** @var array Array of equipment names associated with the character */
    private array $equipment_names;

    /** @var PDO Database connection instance */
    private PDO $pdo;

    /**
     * Character constructor.
     * 
     * @param PDO $pdo Database connection instance
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get the character's ID.
     * 
     * @return int The character's ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the character's ID.
     * 
     * @param int $id The character's ID
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the character's name.
     * 
     * @return string The character's name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the character's name.
     * 
     * @param string $name The character's name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the character's birth date.
     * 
     * @return string The character's birth date
     */
    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    /**
     * Set the character's birth date.
     * 
     * @param string $birth_date The character's birth date
     * @return self
     */
    public function setBirthDate(string $birth_date): self
    {
        $this->birth_date = $birth_date;
        return $this;
    }

    /**
     * Get the character's kingdom.
     * 
     * @return string The character's kingdom
     */
    public function getKingdom(): string
    {
        return $this->kingdom;
    }

    /**
     * Set the character's kingdom.
     * 
     * @param string $kingdom The character's kingdom
     * @return self
     */
    public function setKingdom(string $kingdom): self
    {
        $this->kingdom = $kingdom;
        return $this;
    }

    /**
     * Get the character's equipment ID.
     * 
     * @return int The character's equipment ID
     */
    public function getEquipmentId(): int
    {
        return $this->equipment_id;
    }

    /**
     * Set the character's equipment ID.
     * 
     * @param int $equipment_id The character's equipment ID
     * @return self
     */
    public function setEquipmentId(int $equipment_id): self
    {
        $this->equipment_id = $equipment_id;
        return $this;
    }

    /**
     * Get the character's faction ID.
     * 
     * @return int The character's faction ID
     */
    public function getFactionId(): int
    {
        return $this->faction_id;
    }

    /**
     * Set the character's faction ID.
     * 
     * @param int $faction_id The character's faction ID
     * @return self
     */
    public function setFactionId(int $faction_id): self
    {
        $this->faction_id = $faction_id;
        return $this;
    }

    /**
     * Get the character's faction name.
     * 
     * @return string The character's faction name
     */
    public function getFactionName(): string
    {
        return $this->faction_name;
    }

    /**
     * Set the character's faction name.
     * 
     * @param string $faction_name The character's faction name
     * @return self
     */
    public function setFactionName(string $faction_name): self
    {
        $this->faction_name = $faction_name;
        return $this;
    }

    /**
     * Get the character's equipment IDs.
     * 
     * @return array Array of equipment IDs
     */
    public function getEquipmentIds(): array
    {
        return $this->equipment_ids;
    }

    /**
     * Set the character's equipment IDs.
     * 
     * @param array $equipment_ids Array of equipment IDs
     * @return self
     */
    public function setEquipmentIds(array $equipment_ids): self
    {
        $this->equipment_ids = $equipment_ids;
        return $this;
    }

    /**
     * Get the character's equipment names.
     * 
     * @return array Array of equipment names
     */
    public function getEquipmentNames(): array
    {
        return $this->equipment_names;
    }

    /**
     * Set the character's equipment names.
     * 
     * @param array $equipment_names Array of equipment names
     * @return self
     */
    public function setEquipmentNames(array $equipment_names): self
    {
        $this->equipment_names = $equipment_names;
        return $this;
    }

    /**
     * Create a Character instance from an array of data.
     * 
     * @param array $data Array containing character data
     * @param PDO $pdo Database connection instance
     * @return self
     */
    public function fromArray(array $data, PDO $pdo): self
    {
        $character = new self($pdo);
        
        if (isset($data['id'])) {
            $character->setId($data['id']);
        }
        
        return $character
            ->setName($data['name'])
            ->setBirthDate($data['birth_date'])
            ->setKingdom($data['kingdom'])
            ->setEquipmentId($data['equipment_id'])
            ->setFactionId($data['faction_id'])
            ->setFactionName($data['faction_name'] ?? '')
            ->setEquipmentIds([$data['equipment_id']])
            ->setEquipmentNames([$data['equipment_name'] ?? '']);
    }

    /**
     * Convert the character to an array.
     * 
     * @return array Array representation of the character
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id,
            'faction_name' => $this->faction_name,
            'equipment_ids' => $this->equipment_ids,
            'equipment_names' => $this->equipment_names
        ];

        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    /**
     * Find a character by ID.
     * 
     * @param int $id The character's ID
     * @return self|null The character if found, null otherwise
     */
    public function find(int $id): ?self
    {
        $sql = "SELECT * FROM characters WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        // Get faction name
        $sql = "SELECT faction_name FROM factions WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $data['faction_id']]);
        $faction = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['faction_name'] = $faction ? $faction['faction_name'] : '';

        // Get equipment name
        $sql = "SELECT name FROM equipments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $data['equipment_id']]);
        $equipment = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['equipment_name'] = $equipment ? $equipment['name'] : '';

        return self::fromArray($data, $this->pdo);
    }

    /**
     * Find all characters.
     * 
     * @return array Array of Character instances
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM characters";
        $stmt = $this->pdo->query($sql);
        $characters = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Get faction name
            $sql = "SELECT faction_name FROM factions WHERE id = :id";
            $stmt2 = $this->pdo->prepare($sql);
            $stmt2->execute(['id' => $data['faction_id']]);
            $faction = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data['faction_name'] = $faction ? $faction['faction_name'] : '';

            // Get equipment name
            $sql = "SELECT name FROM equipments WHERE id = :id";
            $stmt2 = $this->pdo->prepare($sql);
            $stmt2->execute(['id' => $data['equipment_id']]);
            $equipment = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data['equipment_name'] = $equipment ? $equipment['name'] : '';

            $character = new self($this->pdo);
            $character->setId($data['id'])
                     ->setName($data['name'])
                     ->setBirthDate($data['birth_date'])
                     ->setKingdom($data['kingdom'])
                     ->setEquipmentId($data['equipment_id'])
                     ->setFactionId($data['faction_id'])
                     ->setFactionName($data['faction_name'])
                     ->setEquipmentIds([$data['equipment_id']])
                     ->setEquipmentNames([$data['equipment_name']]);

            $characters[] = $character;
        }

        return $characters;
    }

    /**
     * Save the character to the database.
     * 
     * @return bool True if successful, false otherwise
     */
    public function save(): bool
    {
        if (isset($this->id)) {
            return $this->update();
        }

        return $this->insert();
    }

    /**
     * Insert a new character into the database.
     * 
     * @return bool True if successful, false otherwise
     */
    private function insert(): bool
    {
        $sql = 'INSERT INTO characters (name, birth_date, kingdom, equipment_id, faction_id) 
                VALUES (:name, :birth_date, :kingdom, :equipment_id, :faction_id)';
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id
        ]);

        if ($result) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $result;
    }

    /**
     * Update an existing character in the database.
     * 
     * @return bool True if successful, false otherwise
     */
    private function update(): bool
    {
        $sql = 'UPDATE characters 
                SET name = :name, 
                    birth_date = :birth_date, 
                    kingdom = :kingdom, 
                    equipment_id = :equipment_id, 
                    faction_id = :faction_id 
                WHERE id = :id';
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'kingdom' => $this->kingdom,
            'equipment_id' => $this->equipment_id,
            'faction_id' => $this->faction_id
        ]);
    }

    /**
     * Delete the character from the database.
     * 
     * @return bool True if successful, false otherwise
     */
    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM characters WHERE id = :id');
        return $stmt->execute(['id' => $this->id]);
    }

    /**
     * Find a character by name.
     * 
     * @param PDO $pdo Database connection instance
     * @param string $name The character's name
     * @return self|null The character if found, null otherwise
     */
    public static function findByName(PDO $pdo, string $name): ?self
    {
        $stmt = $pdo->prepare('SELECT * FROM characters WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return self::fromArray($data, $pdo);
    }

    public static function getAll(PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT 
                c.id,
                c.name,
                c.faction_id,
                f.name as faction_name,
                GROUP_CONCAT(DISTINCT e.id) as equipment_ids,
                GROUP_CONCAT(DISTINCT e.name) as equipment_names
            FROM characters c
            LEFT JOIN factions f ON c.faction_id = f.id
            LEFT JOIN character_equipment ce ON c.id = ce.character_id
            LEFT JOIN equipments e ON ce.equipment_id = e.id
            GROUP BY c.id
        ");

        $characters = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['equipment_ids'] = $row['equipment_ids'] ? explode(',', $row['equipment_ids']) : [];
            $row['equipment_names'] = $row['equipment_names'] ? explode(',', $row['equipment_names']) : [];
            $characters[] = self::fromArray($row, $pdo);
        }

        return $characters;
    }

    public static function getById(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("
            SELECT 
                c.id,
                c.name,
                c.faction_id,
                f.name as faction_name,
                GROUP_CONCAT(DISTINCT e.id) as equipment_ids,
                GROUP_CONCAT(DISTINCT e.name) as equipment_names
            FROM characters c
            LEFT JOIN factions f ON c.faction_id = f.id
            LEFT JOIN character_equipment ce ON c.id = ce.character_id
            LEFT JOIN equipments e ON ce.equipment_id = e.id
            WHERE c.id = ?
            GROUP BY c.id
        ");
        
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $row['equipment_ids'] = $row['equipment_ids'] ? explode(',', $row['equipment_ids']) : [];
        $row['equipment_names'] = $row['equipment_names'] ? explode(',', $row['equipment_names']) : [];
        
        return self::fromArray($row, $pdo);
    }
}
