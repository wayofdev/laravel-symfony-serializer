<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use ArrayObject;
use Stringable;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface SerializerInterface
{
    public function serialize(mixed $payload): string|Stringable;

    public function unserialize(string|Stringable $payload, string|object $type = null): mixed;

    /**
     * @param mixed       $data
     * @param string|null $format
     * @param array       $context
     *
     * @throws ExceptionInterface
     *
     * @return array|string|int|float|bool|ArrayObject|null
     */
    public function normalize(mixed $data, string $format = null, array $context = []): array | string | int | float | bool | ArrayObject | null;

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed;

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool;

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool;

    public function encode(mixed $data, string $format, array $context = []);

    public function decode(string $data, string $format, array $context = []);

    public function supportsEncoding(string $format, array $context = []): bool;

    public function supportsDecoding(string $format, array $context = []): bool;
}
