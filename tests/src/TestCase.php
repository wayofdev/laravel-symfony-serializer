<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Serializer\Bridge\Laravel\Providers\SerializerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('serializer.serializers', [
            'json' => true,
            'csv' => true,
            'xml' => true,
            'yaml' => true,
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            SerializerServiceProvider::class,
        ];
    }
}
