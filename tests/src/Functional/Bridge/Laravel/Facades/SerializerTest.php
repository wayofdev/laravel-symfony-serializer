<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Bridge\Laravel\Facades;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use WayOfDev\Serializer\Bridge\Laravel\Facades\Serializer;
use WayOfDev\Tests\Functional\TestCase;

final class SerializerTest extends TestCase
{
    #[Test]
    public function it_gets_symfony_serializer_from_facade(): void
    {
        $serializer = Serializer::getFacadeRoot();

        self::assertNotNull($serializer);
        self::assertInstanceOf(SymfonySerializerInterface::class, $serializer);
    }
}
