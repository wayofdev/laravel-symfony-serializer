<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\NormalizerRegistry;
use WayOfDev\Serializer\Normalizers\RamseyUuidNormalizer;

use function sprintf;

final class NormalizerRegistryTest extends TestCase
{
    /**
     * Asserts that an array contains an instance of a class.
     *
     * @param array<NormalizerInterface|DenormalizerInterface> $array
     */
    public static function assertContainsInstanceOf(string $className, array $array): void
    {
        foreach ($array as $element) {
            if ($element instanceof $className) {
                self::assertTrue(true); // @phpstan-ignore-line

                return;
            }
        }

        self::fail(sprintf('Failed asserting that the array contains an instance of %s.', $className));
    }

    #[Test]
    public function construct_with_default_normalizers(): void
    {
        $registry = new NormalizerRegistry(app(NormalizerRegistrationStrategy::class));

        self::assertCount(15, $registry->all());

        self::assertTrue($registry->has(Normalizer\UnwrappingDenormalizer::class));
        self::assertTrue($registry->has(Normalizer\ProblemNormalizer::class));
        self::assertTrue($registry->has(Normalizer\UidNormalizer::class));
        self::assertTrue($registry->has(Normalizer\JsonSerializableNormalizer::class));
        self::assertTrue($registry->has(Normalizer\DateTimeNormalizer::class));
        self::assertTrue($registry->has(Normalizer\ConstraintViolationListNormalizer::class));
        self::assertTrue($registry->has(Normalizer\MimeMessageNormalizer::class));
        self::assertTrue($registry->has(Normalizer\DateTimeZoneNormalizer::class));
        self::assertTrue($registry->has(Normalizer\DateIntervalNormalizer::class));
        self::assertTrue($registry->has(Normalizer\FormErrorNormalizer::class));
        self::assertTrue($registry->has(Normalizer\BackedEnumNormalizer::class));
        self::assertTrue($registry->has(Normalizer\DataUriNormalizer::class));
        self::assertTrue($registry->has(Normalizer\ArrayDenormalizer::class));
        self::assertTrue($registry->has(Normalizer\ObjectNormalizer::class));
        self::assertTrue($registry->has(RamseyUuidNormalizer::class));
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function it_registers_additional_normalizers(): void
    {
        $registry = new NormalizerRegistry(
            $this->createMock(NormalizerRegistrationStrategy::class),
        );

        $normalizer = $this->createMock(NormalizerInterface::class);
        $normalizer2 = $this->createMock(DenormalizerInterface::class);

        $registry->register($normalizer, 2);
        self::assertCount(1, $registry->all());
        self::assertTrue($registry->has($normalizer::class));

        $registry->register($normalizer2, 1);
        self::assertCount(2, $registry->all());
        self::assertTrue($registry->has($normalizer2::class));

        self::assertSame($normalizer2, $registry->all()[0]);
        self::assertSame($normalizer, $registry->all()[1]);
    }

    #[Test]
    public function it_gets_all_registered_normalizers(): void
    {
        $registry = new NormalizerRegistry(
            app(NormalizerRegistrationStrategy::class),
        );

        $registry->register(new Normalizer\UnwrappingDenormalizer(), 1);
        $registry->register(new Normalizer\ObjectNormalizer(), 1);

        $allNormalizers = $registry->all();

        self::assertContainsInstanceOf(Normalizer\UnwrappingDenormalizer::class, $allNormalizers);
        self::assertContainsInstanceOf(Normalizer\ObjectNormalizer::class, $allNormalizers);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function it_has_normalizer_in_registry(): void
    {
        $normalizer = $this->createMock(NormalizerInterface::class);

        $registry = new NormalizerRegistry(
            app(NormalizerRegistrationStrategy::class),
        );

        self::assertFalse($registry->has($normalizer::class));

        $registry->register($normalizer);
        self::assertTrue($registry->has($normalizer::class));
    }
}
