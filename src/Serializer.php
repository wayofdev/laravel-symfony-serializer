<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use ArrayObject;
use Stringable;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use WayOfDev\Serializer\Contracts\SerializerInterface;
use WayOfDev\Serializer\Exceptions\UnsupportedTypeException;

use function is_object;

readonly class Serializer implements SerializerInterface
{
    public function __construct(
        private SymfonySerializer $serializer,
        private string $format
    ) {
    }

    public function serialize(mixed $payload, array $context = []): string
    {
        return $this->serializer->serialize($payload, $this->format);
    }

    public function unserialize(
        Stringable|string $payload,
        object|string $type = null,
        array $context = []
    ): mixed {
        if (null === $type) {
            throw new UnsupportedTypeException();
        }

        return $this->serializer->deserialize(
            (string) $payload,
            is_object($type) ? $type::class : $type,
            $this->format,
            $context
        );
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize(
        mixed $data,
        string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|null {
        return $this->serializer->normalize($data, $format, $context);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        return $this->serializer->denormalize($data, $type, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->serializer->supportsNormalization($data, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $this->serializer->supportsDenormalization($data, $type, $format, $context);
    }

    public function encode(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->encode($data, $format, $context);
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {
        return $this->serializer->decode($data, $format, $context);
    }

    public function supportsEncoding(string $format, array $context = []): bool
    {
        return $this->serializer->supportsEncoding($format, $context);
    }

    public function supportsDecoding(string $format, array $context = []): bool
    {
        return $this->serializer->supportsDecoding($format, $context);
    }
}
