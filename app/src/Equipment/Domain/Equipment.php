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


    // Getters (seters are not needed because we are using semantic setters)
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
}
