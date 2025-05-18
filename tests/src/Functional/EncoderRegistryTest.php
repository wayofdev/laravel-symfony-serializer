<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy;
use WayOfDev\Serializer\EncoderRegistry;

final class EncoderRegistryTest extends TestCase
{
    #[Test]
    public function it_creates_registry_with_default_encoders(): void
    {
        $registry = new EncoderRegistry(
            $this->app?->make(EncoderRegistrationStrategy::class)
        );

        self::assertCount(4, $registry->all());

        self::assertTrue($registry->has(JsonEncoder::class));
        self::assertTrue($registry->has(CsvEncoder::class));
        self::assertTrue($registry->has(XmlEncoder::class));
        self::assertTrue($registry->has(YamlEncoder::class));
    }

    #[Test]
    public function it_creates_registry_with_user_defined_encoders(): void
    {
        $strategy = new class implements EncoderRegistrationStrategy {
            public function encoders(): iterable
            {
                yield ['encoder' => new JsonEncoder()];
                yield ['encoder' => new CsvEncoder()];
            }
        };

        $registry = new EncoderRegistry($strategy);

        self::assertCount(2, $registry->all());

        self::assertTrue($registry->has(JsonEncoder::class));
        self::assertTrue($registry->has(CsvEncoder::class));
        self::assertFalse($registry->has(XmlEncoder::class));
        self::assertFalse($registry->has(YamlEncoder::class));
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function it_registers_additional_encoders(): void
    {
        $registry = new EncoderRegistry(
            $this->app?->make(EncoderRegistrationStrategy::class)
        );

        $encoderFirst = $this->createMock(EncoderInterface::class);

        self::assertCount(4, $registry->all());
        $registry->register($encoderFirst);
        self::assertCount(5, $registry->all());
        self::assertTrue($registry->has($encoderFirst::class));

        $registry->register($encoderFirst);
        self::assertCount(5, $registry->all());
        self::assertTrue($registry->has($encoderFirst::class));

        $encoderSecond = $this->createMock(DecoderInterface::class);
        $registry->register($encoderSecond);
        self::assertCount(6, $registry->all());
        self::assertTrue($registry->has($encoderSecond::class));
    }
}
