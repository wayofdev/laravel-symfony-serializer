<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\Contracts\NormalizerRegistryInterface;

use function array_column;
use function uasort;

final class NormalizerRegistry implements NormalizerRegistryInterface
{
    /**
     * @var array<class-string, array{priority: int<0, max>, normalizer: NormalizerInterface|DenormalizerInterface}>
     */
    private array $normalizers = [];

    public function __construct(NormalizerRegistrationStrategy $strategy)
    {
        foreach ($strategy->normalizers() as $normalizer) {
            $this->register($normalizer['normalizer'], $normalizer['priority']);
        }
    }

    /**
     * @param int<0, max> $priority
     */
    public function register(NormalizerInterface|DenormalizerInterface $normalizer, int $priority = 0): void
    {
        if (! $this->has($normalizer::class)) {
            $this->normalizers[$normalizer::class] = [
                'priority' => $priority,
                'normalizer' => $normalizer,
            ];
        }
    }

    /**
     * @return array<NormalizerInterface|DenormalizerInterface>
     */
    public function all(): array
    {
        uasort(
            $this->normalizers,
            static fn (array $normalizer1, array $normalizer2) => $normalizer1['priority'] <=> $normalizer2['priority']
        );

        return array_column($this->normalizers, 'normalizer');
    }

    /**
     * @phpstan-param class-string $className
     */
    public function has(string $className): bool
    {
        return isset($this->normalizers[$className]);
    }
}
