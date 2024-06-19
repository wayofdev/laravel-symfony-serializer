<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Stringable;
use WayOfDev\Serializer\Contracts\SerializerInterface;

/**
 * @method static string format()
 * @method static SerializerInterface serializer(?string $format = null)
 * @method static string serialize(mixed $payload, ?string $format = null, ?array $context = [])
 * @method static mixed deserialize(string|Stringable $payload, string|object|null $type = null, ?string $format = null, ?array $context = [])
 *
 * @see \WayOfDev\Serializer\Manager\SerializerManager
 */
class Manager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'serializer.manager';
    }
}
