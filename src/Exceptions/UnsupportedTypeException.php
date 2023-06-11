<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Exceptions;

use InvalidArgumentException;

final class UnsupportedTypeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The Symfony Serializer only supports deserialization to a specific type. Parameter
        `$type` is required.');
    }
}
