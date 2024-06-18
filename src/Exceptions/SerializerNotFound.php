<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Exceptions;

use RuntimeException;
use WayOfDev\Serializer\Contracts\SerializerException;

use function sprintf;

final class SerializerNotFound extends RuntimeException implements SerializerException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Serializer with name [%s] not found.', $name));
    }
}
