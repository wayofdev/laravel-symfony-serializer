<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\App\Object;

use Ramsey\Uuid\UuidInterface;

class Author
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name
    ) {
    }
}
