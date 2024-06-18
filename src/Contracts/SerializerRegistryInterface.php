<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use WayOfDev\Serializer\Exceptions\SerializerNotFound;

interface SerializerRegistryInterface
{
    public function register(string $name, SerializerInterface $serializer): void;

    /**
     * @throws SerializerNotFound
     */
    public function get(string $name): SerializerInterface;

    /**
     * @return list<SerializerInterface>
     */
    public function all(): array;

    public function has(string $name): bool;
}
