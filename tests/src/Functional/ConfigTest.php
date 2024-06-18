<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\DefaultEncoderRegistrationStrategy;
use WayOfDev\Serializer\DefaultNormalizerRegistrationStrategy;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;

final class ConfigTest extends TestCase
{
    #[Test]
    public function it_creates_config_from_array(): void
    {
        $configArray = [
            'default' => 'json',
            'debug' => true,
            'normalizerRegistrationStrategy' => DefaultNormalizerRegistrationStrategy::class,
            'encoderRegistrationStrategy' => DefaultEncoderRegistrationStrategy::class,
            'metadataLoader' => AttributeLoader::class,
        ];

        $config = Config::fromArray($configArray);

        self::assertSame('json', $config->defaultSerializer());
        self::assertSame(DefaultNormalizerRegistrationStrategy::class, $config->normalizerRegistrationStrategy());
        self::assertSame(DefaultEncoderRegistrationStrategy::class, $config->encoderRegistrationStrategy());
        self::assertInstanceOf(AttributeLoader::class, $config->metadataLoader());
    }

    #[Test]
    public function it_fails_to_create_config_due_to_missing_attributes(): void
    {
        $this->expectException(MissingRequiredAttributes::class);

        $configArray = [
            'default' => 'json',
            'debug' => true,
            'encoderRegistrationStrategy' => DefaultEncoderRegistrationStrategy::class,
            'metadataLoader' => AttributeLoader::class,
        ];

        // @phpstan-ignore-next-line
        Config::fromArray($configArray);
    }

    #[Test]
    public function it_creates_config_with_default_metadata_loader(): void
    {
        $configArray = [
            'default' => 'json',
            'debug' => true,
            'normalizerRegistrationStrategy' => DefaultNormalizerRegistrationStrategy::class,
            'encoderRegistrationStrategy' => DefaultEncoderRegistrationStrategy::class,
            'metadataLoader' => null,
        ];

        $config = Config::fromArray($configArray);

        self::assertInstanceOf(AttributeLoader::class, $config->metadataLoader());
    }
}
