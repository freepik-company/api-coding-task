<?php

namespace App\Character\Infrastructure\Persistence\Pdo\Exception;

class RowInsertionFailedException extends \Exception{
    private const MESSAGE = 'Failed to insert row';

    public static function build(): self
    {
        return new self(self::MESSAGE);
    }
    
}