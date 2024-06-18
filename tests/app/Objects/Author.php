<?php

declare(strict_types=1);

namespace WayOfDev\App\Objects;

use Ramsey\Uuid\UuidInterface;

class Author
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name
    ) {
    }
}
