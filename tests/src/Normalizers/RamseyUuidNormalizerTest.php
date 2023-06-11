<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests\Normalizers;

use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Uuid;
use Traversable;
use WayOfDev\Serializer\App\Object\Author;
use WayOfDev\Serializer\SerializerManager;
use WayOfDev\Serializer\Tests\TestCase;

use function preg_replace;

final class RamseyUuidNormalizerTest extends TestCase
{
    public static function serializeDataProvider(): Traversable
    {
        yield [
            '{"uuid":"1d96a152-9838-43a0-a189-159befc9e38f","name":"some"}',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'json',
        ];
        yield [
            'uuid,name1d96a152-9838-43a0-a189-159befc9e38f,some',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'csv',
        ];
        yield [
            '{uuid:1d96a152-9838-43a0-a189-159befc9e38f,name:some}',
            new Author(Uuid::fromString('1d96a152-9838-43a0-a189-159befc9e38f'), 'some'),
            'symfony-yaml',
        ];
    }

    /**
     * @test
     */
    #[DataProvider('serializeDataProvider')]
    public function serialize(string $expected, mixed $payload, string $format): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $this::assertSame($expected, preg_replace('/\s+/', '', $manager->serialize($payload, $format)));
    }

    /**
     * @test
     */
    public function unserialize(): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $result = $manager->unserialize(
            '{"uuid":"1d96a152-9838-43a0-a189-159befc9e38f","name":"some"}',
            Author::class,
            'json'
        );

        $this::assertInstanceOf(Author::class, $result);
        $this::assertSame('1d96a152-9838-43a0-a189-159befc9e38f', $result->uuid->toString());
    }
}
