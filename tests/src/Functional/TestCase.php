<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Serializer\Bridge\Laravel\Providers\SerializerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SerializerServiceProvider::class,
        ];
    }
}
