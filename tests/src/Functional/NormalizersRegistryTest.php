<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Normalizer;
use WayOfDev\Serializer\Normalizers\RamseyUuidNormalizer;
use WayOfDev\Serializer\NormalizersRegistry;

use function sprintf;

final class NormalizersRegistryTest extends TestCase
{
    /**
     * Asserts that an array contains an instance of a class.
     *
     * @param string $className The class name
     * @param array $array The array
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

    /**
     * @throws Exception
     */
    #[Test]
    public function construct_with_default_normalizers(): void
    {
        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true,
        );

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
    public function register(): void
    {
        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true
        );

        $normalizer = $this->createMock(Normalizer\NormalizerInterface::class);
        $normalizer2 = $this->createMock(Normalizer\DenormalizerInterface::class);

        $registry->register($normalizer, 2);
        self::assertCount(16, $registry->all());
        self::assertTrue($registry->has($normalizer::class));

        $registry->register($normalizer2, 1);
        self::assertCount(17, $registry->all());
        self::assertTrue($registry->has($normalizer2::class));

        self::assertSame($normalizer2, $registry->all()[0]);
        self::assertSame($normalizer, $registry->all()[1]);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function all(): void
    {
        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true,
            [new Normalizer\UnwrappingDenormalizer(), new Normalizer\ObjectNormalizer()]
        );

        $allNormalizers = $registry->all();

        self::assertContainsInstanceOf(Normalizer\UnwrappingDenormalizer::class, $allNormalizers);
        self::assertContainsInstanceOf(Normalizer\ObjectNormalizer::class, $allNormalizers);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function has(): void
    {
        $normalizer = $this->createMock(Normalizer\NormalizerInterface::class);

        $registry = new NormalizersRegistry(
            $this->createMock(LoaderInterface::class),
            true
        );
        self::assertFalse($registry->has($normalizer::class));

        $registry->register($normalizer);
        self::assertTrue($registry->has($normalizer::class));
    }
}
