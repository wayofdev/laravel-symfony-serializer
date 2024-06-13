<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use WayOfDev\Serializer\EncodersRegistry;

final class EncodersRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function construct_with_default_encoders(): void
    {
        $registry = new EncodersRegistry();

        $this::assertCount(4, $registry->all());

        $this::assertTrue($registry->has(JsonEncoder::class));
        $this::assertTrue($registry->has(CsvEncoder::class));
        $this::assertTrue($registry->has(XmlEncoder::class));
        $this::assertTrue($registry->has(YamlEncoder::class));
    }

    /**
     * @test
     */
    public function construct_with_encoders(): void
    {
        $registry = new EncodersRegistry([new JsonEncoder(), new CsvEncoder()]);

        $this::assertCount(2, $registry->all());

        $this::assertTrue($registry->has(JsonEncoder::class));
        $this::assertTrue($registry->has(CsvEncoder::class));
        $this::assertFalse($registry->has(XmlEncoder::class));
        $this::assertFalse($registry->has(YamlEncoder::class));
    }

    /**
     * @test
     */
    public function register(): void
    {
        $registry = new EncodersRegistry();

        $encoder = $this->createMock(EncoderInterface::class);

        $this::assertCount(4, $registry->all());
        $registry->register($encoder);
        $this::assertCount(5, $registry->all());
        $this::assertTrue($registry->has($encoder::class));

        $registry->register($encoder);
        $this::assertCount(5, $registry->all());
        $this::assertTrue($registry->has($encoder::class));
    }

    /**
     * @test
     */
    public function all(): void
    {
        $json = new JsonEncoder();
        $csv = new CsvEncoder();

        $registry = new EncodersRegistry([$json, $csv]);
        $this::assertSame([$json, $csv], $registry->all());
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function has(): void
    {
        $encoder = $this->createMock(EncoderInterface::class);

        $registry = new EncodersRegistry();
        $this::assertFalse($registry->has($encoder::class));

        $registry->register($encoder);
        $this::assertTrue($registry->has($encoder::class));
    }
}
