<?php

namespace App\Shared\Infrastructure\Pdo\Exception;

class RowInsertionFailedException extends \Exception
{

    private const MESSAGE = 'Row insertion failed';

    public static function build(): self
    {
        return new self(self::MESSAGE);
    }

}