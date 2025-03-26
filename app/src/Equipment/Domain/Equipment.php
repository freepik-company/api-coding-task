<?php

namespace App\Equipment\Domain;

class Equipment
{
    // Properties
    private string $name;
    private string $type;
    private string $made_by;
    private ?int $id = null;

    // Constructor
    public function __construct(
        string $name,
        string $type,
        string $made_by,
        ?int $id = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->made_by = $made_by;
        $this->id = $id;
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
