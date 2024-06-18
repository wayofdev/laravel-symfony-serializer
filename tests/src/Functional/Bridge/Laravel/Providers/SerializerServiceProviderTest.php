<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Bridge\Laravel\Providers;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Contracts\EncoderRegistryInterface;
use WayOfDev\Serializer\Contracts\NormalizerRegistryInterface;
use WayOfDev\Serializer\EncoderRegistry;
use WayOfDev\Serializer\NormalizerRegistry;
use WayOfDev\Tests\Functional\TestCase;

final class SerializerServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_config(): void
    {
        self::assertTrue($this->app?->bound(ConfigRepository::class));
        self::assertInstanceOf(Config::class, $this->app->make(ConfigRepository::class));
    }

    #[Test]
    public function it_registers_loader(): void
    {
        self::assertTrue($this->app?->bound(LoaderInterface::class));
        self::assertInstanceOf(AttributeLoader::class, $this->app->make(LoaderInterface::class));
    }

    #[Test]
    public function it_registers_normalizers_registry(): void
    {
        self::assertTrue($this->app?->bound(NormalizerRegistryInterface::class));
        self::assertInstanceOf(NormalizerRegistry::class, $this->app->make(NormalizerRegistryInterface::class));

        $normalizersRegistry = $this->app->make(NormalizerRegistryInterface::class);
        $normalizers = $normalizersRegistry->all();
        self::assertNotEmpty($normalizers);
    }

    #[Test]
    public function it_registers_encoders_registry(): void
    {
        self::assertTrue($this->app?->bound(EncoderRegistryInterface::class));
        self::assertInstanceOf(EncoderRegistry::class, $this->app->make(EncoderRegistryInterface::class));

        $encodersRegistry = $this->app->make(EncoderRegistryInterface::class);
        $encoders = $encodersRegistry->all();
        self::assertNotEmpty($encoders);
        self::assertContainsOnlyInstancesOf(EncoderInterface::class, $encoders);
    }

    #[Test]
    public function it_registers_symfony_serializer_interface(): void
    {
        self::assertTrue($this->app?->bound(SymfonySerializerInterface::class));
        self::assertTrue($this->app->bound(SymfonySerializer::class));
        self::assertInstanceOf(SymfonySerializer::class, $this->app->make(SymfonySerializerInterface::class));
    }
}
