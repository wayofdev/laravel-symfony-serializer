<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Facades;

use ArrayObject;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method static string serialize(mixed $data, string $format, array $context = [])
 * @method static mixed deserialize(string $data, string $type, string $format, array $context = [])
 * @method static array|string|int|float|bool|ArrayObject|null normalize(mixed $data, string $format, array $context = [])
 * @method static mixed denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
 * @method static array getSupportedTypes(?string $format)
 * @method static bool supportsNormalization(mixed $data, ?string $format = null, array $context = [])
 * @method static bool supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = [])
 * @method static NormalizerInterface|null getNormalizer(mixed $data, ?string $format, array $context)
 * @method static DenormalizerInterface|null getDenormalizer(mixed $data, string $type, ?string $format, array $context)
 * @method static string encode(mixed $data, string $format, array $context = [])
 * @method static mixed decode(string $data, string $type, string $format, array $context = [])
 * @method static bool supportsEncoding(string $format, array $context = [])
 * @method static bool supportsDecoding(string $format, array $context = [])
 */
class Serializer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'symfony.serializer';
    }
}
