<?php

declare(strict_types=1);

namespace WayOfDev\Package\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Package\Bridge\Laravel\Providers\PackageServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PackageServiceProvider::class,
        ];
    }
}
