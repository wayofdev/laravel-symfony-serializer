<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use Stringable;
use Traversable;
use WayOfDev\Serializer\Exceptions\SerializerNotFoundException;
use WayOfDev\Serializer\Serializer;
use WayOfDev\Serializer\SerializerManager;

final class SerializerManagerTest extends TestCase
{
    private SerializerManager $serializer;

    public static function serializeDataProvider(): Traversable
    {
        yield [['some', 'elements'], '["some","elements"]', 'json'];
        yield [['some', 'elements'], 'a:2:{i:0;s:4:"some";i:1;s:8:"elements";}', 'serializer'];
        yield [['some', 'elements'], '["some","elements"]'];
    }

    public static function unserializeDataProvider(): Traversable
    {
        yield ['["some","elements"]', ['some', 'elements'], 'json'];
        yield [new class() implements Stringable {
            public function __toString(): string
            {
                return '["some","elements"]';
            }
        }, ['some', 'elements'], 'json'];
        yield ['a:2:{i:0;s:4:"some";i:1;s:8:"elements";}', ['some', 'elements'], 'serializer'];
        yield [new class() implements Stringable {
            public function __toString(): string
            {
                return 'a:2:{i:0;s:4:"some";i:1;s:8:"elements";}';
            }
        }, ['some', 'elements'], 'serializer'];
        yield ['["some","elements"]', ['some', 'elements']];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->app->make(SerializerManager::class);
    }

    /**
     * @test
     */
    public function get_serializer(): void
    {
        $this::assertInstanceOf(
            Serializer::class,
            $this->serializer->getSerializer('json')
        );

        $this::assertInstanceOf(
            Serializer::class,
            $this->serializer->getSerializer('xml')
        );

        $this::assertInstanceOf(
            Serializer::class,
            $this->serializer->getSerializer('csv')
        );

        $this->expectException(SerializerNotFoundException::class);
        $this->serializer->getSerializer('bad');
    }

    /**
     * @test
     */
    #[DataProvider('serializeDataProvider')]
    public function serialize(mixed $payload, string $expected, ?string $format = null): void
    {
        $this::assertSame($expected, $this->serializer->serialize($payload, $format));
    }

    /**
     * @test
     */
    public function bad_serializer(): void
    {
        $this->expectException(SerializerNotFoundException::class);
        $this->serializer->serialize('payload', 'bad');

        $this->expectException(SerializerNotFoundException::class);
        $this->serializer->unserialize('payload', 'bad');
    }

    /**
     * @test
     */
    #[DataProvider('unserializeDataProvider')]
    public function unserialize(string|Stringable $payload, mixed $expected, ?string $format = null): void
    {
        $this::assertSame($expected, $this->serializer->unserialize($payload, format: $format));
    }
}
