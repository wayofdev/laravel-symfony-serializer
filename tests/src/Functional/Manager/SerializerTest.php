<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Manager;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use WayOfDev\Serializer\Manager\Serializer;
use WayOfDev\Tests\Functional\TestCase;

final class SerializerTest extends TestCase
{
    #[Test]
    public function it_creates_serializer(): void
    {
        /** @var SymfonySerializer $symfonySerializer */
        $symfonySerializer = $this->app?->make(SymfonySerializerInterface::class);

        $serializer = new Serializer($symfonySerializer, 'json');

        $json = $serializer->serialize(new class() {
            public string $name = 'some';
        });

        self::assertSame('{"name":"some"}', $json);
    }
}
