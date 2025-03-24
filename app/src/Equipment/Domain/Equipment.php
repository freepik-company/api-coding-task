<?php

namespace App\Equipment\Domain;

class Equipment
{
    // Properties
    private ?int $id = null;
    private string $name;
    private string $type;
    private string $made_by;

    // Constructor
    public function __construct()
    {
    }


    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }   

    public function getMadeBy(): string
    {
        return $this->made_by;
    }   

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }   

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }   

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }      

    public function setMadeBy(string $made_by): self
    {
        $this->made_by = $made_by;
        return $this;
    }      

    public function fromArray(array $data): self
    {
        $requiredFields = ['name', 'type', 'made_by'];
        foreach ($requiredFields as $field){
            if (!isset($data[$field])){
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        $equipment = new self();

        if (isset($data['id'])){
            $equipment->setId($data['id']);
        }

        return $equipment
            ->setName($data['name'])
            ->setType($data['type'])
            ->setMadeBy($data['made_by']);
    }
    
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'type'=> $this->type,
            'made_by'=> $this->made_by,
        ];

        if ($this->id !== null){
            $data['id'] = $this-> id;
        }

        return $data;
    }

}
