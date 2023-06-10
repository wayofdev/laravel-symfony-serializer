<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Serializer\Bridge\Laravel\Providers\SerializerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SerializerServiceProvider::class,
        ];
    }
}
