<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface NormalizerRegistryInterface
{
    /**
     * @param int<0, max> $priority
     */
    public function register(NormalizerInterface|DenormalizerInterface $normalizer, int $priority = 0): void;

    /**
     * @return array<NormalizerInterface|DenormalizerInterface>
     */
    public function all(): array;

    /**
     * @phpstan-param class-string $className
     */
    public function has(string $className): bool;
}
