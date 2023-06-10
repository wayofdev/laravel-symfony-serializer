<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Exceptions;

use RuntimeException;

use function sprintf;

final class SerializerNotFoundException extends RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Serializer with name [%s] not found.', $name));
    }
}
