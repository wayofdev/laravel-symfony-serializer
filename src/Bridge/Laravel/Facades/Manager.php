<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'serializer.manager';
    }
}
