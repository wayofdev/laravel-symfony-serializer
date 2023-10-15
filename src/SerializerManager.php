<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use ArrayObject;
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
        string|object $type = null,
        string $format = null
    ): mixed {
        return $this->getSerializer($format ?? $this->defaultFormat)->unserialize($payload, $type);
    }

    public function normalize(mixed $data, string $format = null, array $context = []): array | string | int | float | bool | ArrayObject | null
    {
        return $this->getSerializer($format ?? $this->defaultFormat)->normalize($data, $format, $context);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->denormalize($data, $type, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->supportsNormalization($data, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->supportsDenormalization($data, $type, $format, $context);
    }

    public function encode(mixed $data, string $format, array $context = [])
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->encode($data, $format, $context);
    }

    public function decode(string $data, string $format, array $context = [])
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->decode($data, $format, $context);
    }

    public function supportsEncoding(string $format, array $context = []): bool
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->supportsEncoding($format, $context);
    }

    public function supportsDecoding(string $format, array $context = []): bool
    {
        $format ??= $this->defaultFormat;

        return $this->getSerializer($format)->supportsDecoding($format, $context);
    }
}
