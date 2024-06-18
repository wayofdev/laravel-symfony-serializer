<?php

declare(strict_types=1);

namespace WayOfDev\App\NestedObjects;

use DateTimeZone;
use JsonSerializable;

final class City implements JsonSerializable
{
    public function __construct(
        public string $name,
        public DateTimeZone $timezone,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'timezone' => $this->timezone->getName(),
        ];
    }
}
