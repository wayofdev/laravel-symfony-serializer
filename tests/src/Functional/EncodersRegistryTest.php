<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use WayOfDev\Serializer\EncodersRegistry;

final class EncodersRegistryTest extends TestCase
{
    #[Test]
    public function construct_with_default_encoders(): void
    {
        $registry = new EncodersRegistry();

        self::assertCount(4, $registry->all());

        self::assertTrue($registry->has(JsonEncoder::class));
        self::assertTrue($registry->has(CsvEncoder::class));
        self::assertTrue($registry->has(XmlEncoder::class));
        self::assertTrue($registry->has(YamlEncoder::class));
    }

    #[Test]
    public function construct_with_encoders(): void
    {
        $registry = new EncodersRegistry([new JsonEncoder(), new CsvEncoder()]);

        self::assertCount(2, $registry->all());

        self::assertTrue($registry->has(JsonEncoder::class));
        self::assertTrue($registry->has(CsvEncoder::class));
        self::assertFalse($registry->has(XmlEncoder::class));
        self::assertFalse($registry->has(YamlEncoder::class));
    }

    #[Test]
    public function register(): void
    {
        $registry = new EncodersRegistry();

        $encoder = $this->createMock(EncoderInterface::class);

        self::assertCount(4, $registry->all());
        $registry->register($encoder);
        self::assertCount(5, $registry->all());
        self::assertTrue($registry->has($encoder::class));

        $registry->register($encoder);
        self::assertCount(5, $registry->all());
        self::assertTrue($registry->has($encoder::class));
    }

    #[Test]
    public function all(): void
    {
        $json = new JsonEncoder();
        $csv = new CsvEncoder();

        $registry = new EncodersRegistry([$json, $csv]);
        self::assertSame([$json, $csv], $registry->all());
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function has(): void
    {
        $encoder = $this->createMock(EncoderInterface::class);

        $registry = new EncodersRegistry();
        self::assertFalse($registry->has($encoder::class));

        $registry->register($encoder);
        self::assertTrue($registry->has($encoder::class));
    }
}
