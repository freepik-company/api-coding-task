<?php

namespace App\Character\Domain\Validation;

class ValidationResult
{
    private bool $isValid;
    private array $errors;

    public function __construct(bool $isValid = true, array $errors = [])
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $field, string $message): void
    {
        $this->isValid = false;
        $this->errors[$field] = $message;
    }

    public function merge(ValidationResult $other): void
    {
        if (!$other->isValid()) {
            $this->isValid = false;
            $this->errors = array_merge($this->errors, $other->getErrors());
        }
    }
} 