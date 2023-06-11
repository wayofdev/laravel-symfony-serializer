<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\App\NestedObjects;

final class Country
{
    /**
     * @param non-empty-string $name
     * @param City[] $cities
     */
    public function __construct(
        public string $name,
        public array $cities,
    ) {
    }
}
