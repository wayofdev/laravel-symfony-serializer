<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Manager;

use WayOfDev\Serializer\Contracts\SerializerInterface;
use WayOfDev\Serializer\Contracts\SerializerRegistryInterface;
use WayOfDev\Serializer\Exceptions\SerializerNotFound;

use function array_values;

class SerializerRegistry implements SerializerRegistryInterface
{
    /**
     * @param array<string, SerializerInterface> $serializers
     */
    public function __construct(private array $serializers = [])
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
     * @throws SerializerNotFound
     */
    public function get(string $name): SerializerInterface
    {
        return $this->serializers[$name] ?? throw new SerializerNotFound($name);
    }

    /**
     * @return list<SerializerInterface>
     */
    public function all(): array
    {
        return array_values($this->serializers);
    }

    public function has(string $name): bool
    {
        return isset($this->serializers[$name]);
    }
}
