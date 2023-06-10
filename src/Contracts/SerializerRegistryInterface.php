<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use WayOfDev\Serializer\Exceptions\SerializerNotFoundException;

interface SerializerRegistryInterface
{
    public function register(string $name, SerializerInterface $serializer): void;

    /**
     * @throws SerializerNotFoundException
     */
    public function get(string $name): SerializerInterface;

    public function has(string $name): bool;
}
