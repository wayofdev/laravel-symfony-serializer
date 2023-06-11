<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Normalizer;
use WayOfDev\Serializer\Normalizers\RamseyUuidNormalizer;
use WayOfDev\Serializer\NormalizersRegistry;

final class NormalizersRegistryTest extends TestCase
{
    /**
     * @test
     *
     * @throws Exception
     */
    public function construct_with_default_normalizers(): void
    {
        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true,
        );

        $this::assertCount(15, $registry->all());

        $this::assertTrue($registry->has(Normalizer\UnwrappingDenormalizer::class));
        $this::assertTrue($registry->has(Normalizer\ProblemNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\UidNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\JsonSerializableNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\DateTimeNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\ConstraintViolationListNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\MimeMessageNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\DateTimeZoneNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\DateIntervalNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\FormErrorNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\BackedEnumNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\DataUriNormalizer::class));
        $this::assertTrue($registry->has(Normalizer\ArrayDenormalizer::class));
        $this::assertTrue($registry->has(Normalizer\ObjectNormalizer::class));
        $this::assertTrue($registry->has(RamseyUuidNormalizer::class));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function register(): void
    {
        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true
        );

        $normalizer = $this->createMock(Normalizer\NormalizerInterface::class);
        $normalizer2 = $this->createMock(Normalizer\DenormalizerInterface::class);

        $registry->register($normalizer, 2);
        $this::assertCount(16, $registry->all());
        $this::assertTrue($registry->has($normalizer::class));

        $registry->register($normalizer2, 1);
        $this::assertCount(17, $registry->all());
        $this::assertTrue($registry->has($normalizer2::class));

        $this::assertSame($normalizer2, $registry->all()[0]);
        $this::assertSame($normalizer, $registry->all()[1]);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function all(): void
    {
        $unwrapping = new Normalizer\UnwrappingDenormalizer();
        $object = new Normalizer\ObjectNormalizer();

        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true,
            [$unwrapping, $object]
        );

        $this::assertSame([$unwrapping, $object], $registry->all());
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function has(): void
    {
        $normalizer = $this->createMock(Normalizer\NormalizerInterface::class);

        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true
        );
        $this::assertFalse($registry->has($normalizer::class));

        $registry->register($normalizer);
        $this::assertTrue($registry->has($normalizer::class));
    }
}
