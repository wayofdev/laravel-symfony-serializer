<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\App\NestedObjects;

use DateTimeZone;
use JsonSerializable;

final class City implements JsonSerializable
{
    public function __construct(
        public string $name,
        public DateTimeZone $timezone,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'timezone' => $this->timezone->getName(),
        ];
    }
}
