<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function implode;

final class Config implements ConfigRepository
{
    private const REQUIRED_FIELDS = [
        'default',
        'debug',
        'normalizerRegistrationStrategy',
        'encoderRegistrationStrategy',
        'metadataLoader',
    ];

    public function __construct(
        private readonly string $defaultSerializer = 'symfony-json',
        private readonly bool $debug = false,
        /** @var class-string<NormalizerRegistrationStrategy>|null */
        private readonly ?string $normalizerRegistrationStrategy = null,
        /** @var class-string<EncoderRegistrationStrategy>|null */
        private readonly ?string $encoderRegistrationStrategy = null,
        /** @var class-string<LoaderInterface>|null */
        private readonly ?string $metadataLoader = null,
    ) {
    }

    /**
     * @param array{
     *     default: string,
     *     debug: bool,
     *     normalizerRegistrationStrategy: class-string<NormalizerRegistrationStrategy>,
     *     encoderRegistrationStrategy: class-string<EncoderRegistrationStrategy>,
     *     metadataLoader: class-string<LoaderInterface>|null,
     * } $config
     */
    public static function fromArray(array $config): self
    {
        $missingAttributes = array_diff(self::REQUIRED_FIELDS, array_keys($config));

        if ($missingAttributes !== []) {
            throw MissingRequiredAttributes::fromArray(
                implode(',', $missingAttributes)
            );
        }

        return new self(
            $config['default'],
            $config['debug'],
            $config['normalizerRegistrationStrategy'],
            $config['encoderRegistrationStrategy'],
            $config['metadataLoader']
        );
    }

    public function defaultSerializer(): string
    {
        return $this->defaultSerializer;
    }

    public function debug(): bool
    {
        return $this->debug;
    }

    /**
     * @return class-string<NormalizerRegistrationStrategy>
     */
    public function normalizerRegistrationStrategy(): string
    {
        if ($this->normalizerRegistrationStrategy === null) {
            return DefaultNormalizerRegistrationStrategy::class;
        }

        return $this->normalizerRegistrationStrategy;
    }

    /**
     * @return class-string<EncoderRegistrationStrategy>
     */
    public function encoderRegistrationStrategy(): string
    {
        if ($this->encoderRegistrationStrategy === null) {
            return DefaultEncoderRegistrationStrategy::class;
        }

        return $this->encoderRegistrationStrategy;
    }

    public function metadataLoader(): LoaderInterface
    {
        if ($this->metadataLoader !== null) {
            return new ($this->metadataLoader);
        }

        return new AttributeLoader();
    }
}
