<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Stringable;

interface SerializerInterface
{
    /**
     * Serializes data in the appropriate format.
     *
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function serialize(mixed $payload, ?string $format = null, ?array $context = []): string|Stringable;

    /**
     * Deserializes data into the given type.
     *
     * @param array<string, mixed> $context
     */
    public function deserialize(
        string|Stringable $payload,
        string|object|null $type = null,
        ?string $format = null,
        ?array $context = [],
    ): mixed;
}
