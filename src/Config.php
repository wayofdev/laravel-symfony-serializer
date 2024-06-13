<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function implode;

final class Config implements ConfigRepository
{
    private const REQUIRED_FIELDS = [
        'default',
        'normalizers',
        'encoders',
    ];

    public function __construct(
        private readonly string $defaultSerializer,
        private readonly array $normalizers,
        private readonly array $encoders
    ) {
    }

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
            $config['normalizers'],
            $config['encoders'],
        );
    }

    public function defaultSerializer(): string
    {
        return $this->defaultSerializer ?? 'json';
    }

    public function normalizers(): array
    {
        return $this->normalizers ?? [];
    }

    public function encoders(): array
    {
        return $this->encoders ?? [];
    }

    public function metadataLoader(): LoaderInterface
    {
        if (! empty($this->config['metadataLoader'])) {
            return $this->config['metadataLoader'];
        }

        return new AttributeLoader();
    }
}
