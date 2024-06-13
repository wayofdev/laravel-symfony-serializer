<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Stringable;

interface SerializerInterface
{
    public function serialize(mixed $payload): string|Stringable;

    public function unserialize(string|Stringable $payload, string|object|null $type = null): mixed;

    public function normalize(mixed $data, ?string $format = null, array $context = []);
}
