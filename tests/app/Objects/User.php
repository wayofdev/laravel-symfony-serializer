<?php

declare(strict_types=1);

namespace WayOfDev\App\Objects;

use DateTimeInterface;

class User
{
    public function __construct(
        public int $id,
        public DateTimeInterface $registeredAt,
    ) {
    }
}
