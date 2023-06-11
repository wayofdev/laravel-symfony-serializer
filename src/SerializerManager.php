<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Stringable;
use WayOfDev\Serializer\Contracts\SerializerInterface;

readonly class SerializerManager implements SerializerInterface
{
    public function __construct(
        protected SerializerRegistry $serializers,
        protected string $defaultFormat
    ) {
    }

    public function getSerializer(string $format = null): SerializerInterface
    {
        return $this->serializers->get($format ?? $this->defaultFormat);
    }

    public function serialize(mixed $payload, string $format = null): string|Stringable
    {
        return $this->getSerializer($format ?? $this->defaultFormat)->serialize($payload);
    }

    public function unserialize(
        string|Stringable $payload,
        string|object|null $type = null,
        string $format = null
    ): mixed {
        return $this->getSerializer($format ?? $this->defaultFormat)->unserialize($payload, $type);
    }

    public function normalize(mixed $data, string $format = null, array $context = [])
    {
        return $this->getSerializer($format ?? $this->defaultFormat)->normalize($data, $format, $context);
    }
}
