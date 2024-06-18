<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Unit\Normalizers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use WayOfDev\Serializer\Normalizers\RamseyUuidNormalizer;

class RamseyUuidNormalizerTest extends TestCase
{
    private RamseyUuidNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new RamseyUuidNormalizer();
    }

    #[Test]
    public function it_normalizes(): void
    {
        $uuid = Uuid::uuid4();
        $normalized = $this->normalizer->normalize($uuid);

        self::assertSame($uuid->toString(), $normalized);
    }

    #[Test]
    public function it_supports_normalization(): void
    {
        $uuid = Uuid::uuid4();
        $notUuid = 'not-a-uuid';

        self::assertTrue($this->normalizer->supportsNormalization($uuid));
        self::assertFalse($this->normalizer->supportsNormalization($notUuid));
    }

    #[Test]
    public function it_denormalizes(): void
    {
        $uuidString = Uuid::uuid4()->toString();
        $denormalized = $this->normalizer->denormalize($uuidString, UuidInterface::class);

        self::assertSame($uuidString, $denormalized->toString());
    }

    #[Test]
    public function denormalize_throws_exception_for_invalid_uuid(): void
    {
        $this->expectException(NotNormalizableValueException::class);

        $invalidUuidString = 'invalid-uuid';
        $this->normalizer->denormalize($invalidUuidString, UuidInterface::class);
    }

    #[Test]
    public function it_supports_denormalization(): void
    {
        $validUuidString = Uuid::uuid4()->toString();
        $invalidUuidString = 'invalid-uuid';
        $notAString = 12345;

        self::assertTrue($this->normalizer->supportsDenormalization($validUuidString, UuidInterface::class));
        self::assertFalse($this->normalizer->supportsDenormalization($invalidUuidString, UuidInterface::class));
        self::assertFalse($this->normalizer->supportsDenormalization($notAString, UuidInterface::class));
    }

    #[Test]
    public function it_gets_supported_types(): void
    {
        $expected = [UuidInterface::class => true];
        $actual = $this->normalizer->getSupportedTypes(null);

        self::assertSame($expected, $actual);
    }
}
