<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Bridge\Laravel\Facades;

use PHPUnit\Framework\Attributes\Test;
use WayOfDev\Serializer\Bridge\Laravel\Facades\Manager;
use WayOfDev\Serializer\Manager\SerializerManager;
use WayOfDev\Tests\Functional\TestCase;

final class ManagerTest extends TestCase
{
    #[Test]
    public function it_gets_manager_from_facade(): void
    {
        $manager = Manager::getFacadeRoot();

        self::assertNotNull($manager);
        self::assertInstanceOf(SerializerManager::class, $manager);
    }
}
