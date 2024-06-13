<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Normalizers;

use ArrayObject;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function is_a;
use function is_string;
use function sprintf;

final class RamseyUuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param UuidInterface $object
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        return $object->toString();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof UuidInterface;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): UuidInterface
    {
        try {
            return Uuid::fromString($data);
        } catch (InvalidArgumentException) {
            throw new NotNormalizableValueException(
                sprintf('The data is not a valid "%s" string representation.', $type)
            );
        }
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_string($data) && is_a($type, UuidInterface::class, true) && Uuid::isValid($data);
    }

    /**
     * @return array<class-string|'*'|'object'|string, bool|null>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            UuidInterface::class => true,
        ];
    }
}
