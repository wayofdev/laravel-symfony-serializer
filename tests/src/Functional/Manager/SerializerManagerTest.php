<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Manager;

use PHPUnit\Framework\Attributes\Test;
use WayOfDev\Serializer\Manager\SerializerManager;
use WayOfDev\Tests\Functional\TestCase;

final class SerializerManagerTest extends TestCase
{
    #[Test]
    public function it_returns_serializer_manager_with_default_format(): void
    {
        /** @var SerializerManager $manager */
        $manager = $this->app?->make(SerializerManager::class);

        self::assertSame('symfony-json', $manager->format());
    }
}
