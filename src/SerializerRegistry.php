<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use WayOfDev\Serializer\Contracts\SerializerInterface;
use WayOfDev\Serializer\Contracts\SerializerRegistryInterface;
use WayOfDev\Serializer\Exceptions\SerializerNotFoundException;

class SerializerRegistry implements SerializerRegistryInterface
{
    /**
     * @var SerializerInterface[]
     */
    private array $serializers = [];

    public function __construct(array $serializers = [])
    {
        foreach ($serializers as $name => $serializer) {
            $this->register($name, $serializer);
        }
    }

    public function register(string $name, SerializerInterface $serializer): void
    {
        $this->serializers[$name] = $serializer;
    }

    /**
     * @throws SerializerNotFoundException
     */
    public function get(string $name): SerializerInterface
    {
        return $this->serializers[$name] ?? throw new SerializerNotFoundException($name);
    }

    public function has(string $name): bool
    {
        return isset($this->serializers[$name]);
    }
}
