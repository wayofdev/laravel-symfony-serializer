<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Normalizers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramsey\Uuid\Uuid;
use Traversable;
use WayOfDev\App\Objects\Author;
use WayOfDev\Serializer\Manager\SerializerManager;
use WayOfDev\Tests\Functional\TestCase;

use function preg_replace;

final class RamseyUuidNormalizerTest extends TestCase
{
    /**
     * @return Traversable<array{0: string, 1: mixed, 2: string}>
     */
    public static function serializeDataProvider(): Traversable
    {
        yield [
            '{"uuid":"1d96a152-9838-43a0-a189-159befc9e38f","name":"some"}',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'symfony-json',
        ];
        yield [
            'uuid,name1d96a152-9838-43a0-a189-159befc9e38f,some',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'symfony-csv',
        ];
        yield [
            '{uuid:1d96a152-9838-43a0-a189-159befc9e38f,name:some}',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'symfony-yaml',
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[DataProvider('serializeDataProvider')]
    #[Test]
    public function it_serializes_using_serializer_manager(string $expected, mixed $payload, string $format): void
    {
        $manager = $this->app?->get(SerializerManager::class);
        self::assertNotNull($manager, 'SerializerManager should not be null');

        $serialized = $manager->serialize($payload, $format);
        self::assertIsString($serialized);
        self::assertSame($expected, preg_replace('/\s+/', '', $serialized));
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    #[Test]
    public function it_deserializes_using_serialize_manager(): void
    {
        $manager = $this->app?->get(SerializerManager::class);
        self::assertNotNull($manager, 'SerializerManager should not be null');

        $result = $manager->deserialize(
            '{"uuid":"1d96a152-9838-43a0-a189-159befc9e38f","name":"some"}',
            Author::class,
            'symfony-json'
        );

        self::assertInstanceOf(Author::class, $result);
        self::assertSame('1d96a152-9838-43a0-a189-159befc9e38f', $result->uuid->toString());
    }
}
