<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Manager;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use WayOfDev\Serializer\Contracts\SerializerInterface;
use WayOfDev\Serializer\Exceptions\SerializerNotFound;
use WayOfDev\Serializer\Manager\SerializerRegistry;
use WayOfDev\Tests\Functional\TestCase;

final class SerializerRegistryTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[Test]
    public function it_creates_registry_with_default_serializers(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $registry = new SerializerRegistry(['default' => $serializerMock]);

        self::assertCount(1, $registry->all());
        self::assertTrue($registry->has('default'));
        self::assertSame($serializerMock, $registry->get('default'));
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function it_registers_additional_serializers(): void
    {
        $serializerMock1 = $this->createMock(SerializerInterface::class);
        $serializerMock2 = $this->createMock(SerializerInterface::class);

        $registry = new SerializerRegistry();
        $registry->register('first', $serializerMock1);
        $registry->register('second', $serializerMock2);

        self::assertCount(2, $registry->all());
        self::assertTrue($registry->has('first'));
        self::assertTrue($registry->has('second'));
        self::assertSame($serializerMock1, $registry->get('first'));
        self::assertSame($serializerMock2, $registry->get('second'));
    }

    #[Test]
    public function it_throws_exception_for_missing_serializer(): void
    {
        $this->expectException(SerializerNotFound::class);

        $registry = new SerializerRegistry();
        $registry->get('nonexistent');
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function it_lists_all_serializers(): void
    {
        $serializerMock1 = $this->createMock(SerializerInterface::class);
        $serializerMock2 = $this->createMock(SerializerInterface::class);

        $registry = new SerializerRegistry(['first' => $serializerMock1, 'second' => $serializerMock2]);

        $serializers = $registry->all();

        self::assertCount(2, $serializers);
        self::assertContains($serializerMock1, $serializers);
        self::assertContains($serializerMock2, $serializers);
    }
}
