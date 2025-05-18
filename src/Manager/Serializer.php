<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Manager;

use Stringable;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use WayOfDev\Serializer\Contracts\SerializerInterface;

use function is_object;

final readonly class Serializer implements SerializerInterface
{
    public function __construct(
        private SymfonySerializerInterface $serializer,
        private string $format,
    ) {
    }

    public function serialize(mixed $payload, ?string $format = null, ?array $context = []): string|Stringable
    {
        $context = $context ?? [];

        return $this->serializer->serialize($payload, $this->format, $context);
    }

    public function deserialize(Stringable|string $payload, object|string|null $type = null, ?string $format = null, ?array $context = []): mixed
    {
        $context = $context ?? [];

        return $this->serializer->deserialize(
            (string) $payload,
            is_object($type) ? $type::class : $type, // @phpstan-ignore-line
            $this->format,
            $context
        );
    }
}
