<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function implode;

final class Config implements ConfigRepository
{
    private const REQUIRED_FIELDS = [
        'default_serializer',
        'normalizers',
        'encoders',
        'metadata_loader',
    ];

    public static function fromArray(array $config): self
    {
        $missingAttributes = array_diff(self::REQUIRED_FIELDS, array_keys($config));

        if ([] !== $missingAttributes) {
            throw MissingRequiredAttributes::fromArray(
                implode(',', $missingAttributes)
            );
        }

        return new self(
            $config['default_serializer'],
            $config['normalizers'],
            $config['encoders'],
            $config['metadata_loader']
        );
    }

    public function __construct(
        private readonly string $defaultSerializer,
        private readonly array $normalizers,
        private readonly array $encoders,
        private readonly LoaderInterface $metadataLoader
    ) {
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
        return $this->metadataLoader ?? new AnnotationLoader(new AnnotationReader());
    }
}
