<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\Normalizers\RamseyUuidNormalizer;

use function interface_exists;

final readonly class DefaultNormalizerRegistrationStrategy implements NormalizerRegistrationStrategy
{
    public function __construct(
        private LoaderInterface $loader,
        private bool $debugMode = false,
    ) {
    }

    /**
     * @return iterable<array{normalizer: NormalizerInterface|DenormalizerInterface, priority: int<0, max>}>
     */
    public function normalizers(): iterable
    {
        $factory = new ClassMetadataFactory($this->loader);

        yield ['normalizer' => new Normalizer\UnwrappingDenormalizer(), 'priority' => 50];
        yield ['normalizer' => new Normalizer\ProblemNormalizer(debug: $this->debugMode), 'priority' => 100];
        yield ['normalizer' => new Normalizer\UidNormalizer(), 'priority' => 150];
        yield ['normalizer' => new Normalizer\JsonSerializableNormalizer(), 'priority' => 200];
        yield ['normalizer' => new Normalizer\DateTimeNormalizer(), 'priority' => 250];
        yield ['normalizer' => new Normalizer\ConstraintViolationListNormalizer(), 'priority' => 300];
        yield ['normalizer' => new Normalizer\MimeMessageNormalizer(new Normalizer\PropertyNormalizer()), 'priority' => 350];
        yield ['normalizer' => new Normalizer\DateTimeZoneNormalizer(), 'priority' => 400];
        yield ['normalizer' => new Normalizer\DateIntervalNormalizer(), 'priority' => 450];
        yield ['normalizer' => new Normalizer\FormErrorNormalizer(), 'priority' => 500];
        yield ['normalizer' => new Normalizer\BackedEnumNormalizer(), 'priority' => 550];
        yield ['normalizer' => new Normalizer\DataUriNormalizer(), 'priority' => 600];
        yield ['normalizer' => new Normalizer\ArrayDenormalizer(), 'priority' => 650];
        yield ['normalizer' => new Normalizer\ObjectNormalizer(
            classMetadataFactory: $factory,
            nameConverter: new MetadataAwareNameConverter($factory),
            propertyTypeExtractor: new PropertyInfoExtractor(
                typeExtractors: [new PhpDocExtractor(), new ReflectionExtractor()]
            )
        ), 'priority' => 700];

        if (interface_exists(UuidInterface::class)) {
            yield ['normalizer' => new RamseyUuidNormalizer(), 'priority' => 155];
        }
    }
}
