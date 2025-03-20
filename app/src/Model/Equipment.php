<?php

namespace App\Model;

use PDO;

class Equipment
{
    private $id;
    private $name;
    private $type;
    private $made_by;
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getMadeBy()
    {
        return $this->made_by;
    }

    public function setMadeBy($made_by)
    {
        $this->made_by = $made_by;
    }

    public function save()
    {
        $sql = "INSERT INTO equipments (name, type, made_by) VALUES (:name, :type, :made_by)";
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            ':name' => $this->name,
            ':type' => $this->type,
            ':made_by' => $this->made_by
        ]);

        if ($result) {
            $this->id = $this->db->lastInsertId();
        }

        return $result;
    }

    public static function findByName(PDO $db, $name)
    {
        $sql = "SELECT * FROM equipments WHERE name = :name";
        $stmt = $db->prepare($sql);
        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $equipment = new self($db);
            $equipment->id = $result['id'];
            $equipment->name = $result['name'];
            $equipment->type = $result['type'];
            $equipment->made_by = $result['made_by'];
            return $equipment;
        }

        return null;
    }

    public static function getAll(PDO $db)
    {
        $sql = "SELECT * FROM equipments";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 