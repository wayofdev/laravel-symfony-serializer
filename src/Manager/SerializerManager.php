<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Manager;

use Stringable;
use WayOfDev\Serializer\Contracts\SerializerInterface;

readonly class SerializerManager implements SerializerInterface
{
    public function __construct(
        protected SerializerRegistry $serializers,
        protected string $defaultFormat = 'symfony-json'
    ) {
    }

    public function format(): string
    {
        return $this->defaultFormat;
    }

    public function serializer(?string $format = null): SerializerInterface
    {
        return $this->serializers->get($format ?? $this->defaultFormat);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function serialize(mixed $payload, ?string $format = null, ?array $context = []): string|Stringable
    {
        $format = $format ?? $this->defaultFormat;

        return $this->serializer($format)->serialize($payload, $format, $context);
    }

    /**
     * Deserializes data into the given type.
     *
     * @param array<string, mixed> $context
     */
    public function deserialize(
        string|Stringable $payload,
        string|object|null $type = null,
        ?string $format = null,
        ?array $context = []
    ): mixed {
        $format = $format ?? $this->defaultFormat;

        return $this->serializer($format)->deserialize($payload, $type, $format, $context);
    }
}
