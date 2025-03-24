<?php

namespace App\Equipment\Infraestructure;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\EquipmentRepository;
use PDO;

class MySQLEquipmentRepository implements EquipmentRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function find(int $id): ?Equipment{
        $stmt = $this->pdo->prepare('SELECT * FROM equipments WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data){
            return null;
        }

        return self::fromArray($data, $this->pdo);
    }

    private function fromArray(array $data): Equipment{
        $equipment = new Equipment();

        if (isset($data['id'])){
            $equipment->setId($data['id']);
        }

        return $equipment
            ->setName($data['name'])
            ->setType($data['type'])
            ->setMadeBy($data['made_by']);
    }

    public function findAll(): array{
        $stmt = $this->pdo->query('SELECT * FROM equipments');
        $equipments = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)){
            $equipments[] = self::fromArray($data);
        }

        return $equipments;
    }

    public function save(Equipment $equipment):Equipment{
        if (isset($equipment->getId())){
            return $this->update($equipment);
        }

        return $this->insert($equipment);
    }

    private function insert(Equipment $equipment): Equipment{
        $sql = 'INSERT INTO equipments (name, type, made_by) VALUES (:name, :type, :made_by)';

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $equipment->getName(),
            'type' => $equipment->getType(),
            'made_by' => $equipment->getMadeBy(),
        ]);

        if(!$result){
            $equipment->setId($this->pdo->lastInsertId());
        }

        return $equipment;
    }


    private function update(Equipment $equipment): Equipment{
        $sql = 'UPDATE equipments
            SET name = :name,
            type = :type,
            made_by = :made_by
        WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $equipment->getId(),
            'name' => $equipment ->getName(),
            'type' => $equipment->getType(),
            'made_by' => $equipment->getMadeBy(),
        ]);

        return $equipment;
    }

    public function delete(Equipment $equipment): bool{
        if (null === $equipment->getId()){
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM equipments WHERE id = :id');
        return $stmt->execute(['id' => $equipment->getId()]);
    }
}