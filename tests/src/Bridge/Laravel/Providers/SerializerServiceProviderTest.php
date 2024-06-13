<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests\Bridge\Laravel\Providers;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Contracts\EncodersRegistryInterface;
use WayOfDev\Serializer\Contracts\NormalizersRegistryInterface;
use WayOfDev\Serializer\EncodersRegistry;
use WayOfDev\Serializer\NormalizersRegistry;
use WayOfDev\Serializer\Tests\TestCase;

final class SerializerServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_registers_config(): void
    {
        $this::assertTrue($this->app->bound(ConfigRepository::class));
        // @phpstan-ignore-next-line
        $this::assertInstanceOf(Config::class, $this->app->make(ConfigRepository::class));
    }

    /**
     * @test
     */
    public function it_registers_normalizers_registry(): void
    {
        $this::assertTrue($this->app->bound(NormalizersRegistryInterface::class));
        // @phpstan-ignore-next-line
        $this::assertInstanceOf(NormalizersRegistry::class, $this->app->make(NormalizersRegistryInterface::class));
    }

    /**
     * @test
     */
    public function it_registers_encoders_registry(): void
    {
        $this::assertTrue($this->app->bound(EncodersRegistryInterface::class));
        // @phpstan-ignore-next-line
        $this::assertInstanceOf(EncodersRegistry::class, $this->app->make(EncodersRegistryInterface::class));
    }

    /**
     * @test
     */
    public function it_registers_serializer(): void
    {
        $this::assertTrue($this->app->bound(SerializerInterface::class));
        // @phpstan-ignore-next-line
        $this::assertInstanceOf(Serializer::class, $this->app->make(SerializerInterface::class));
    }
}
