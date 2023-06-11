<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

interface EncodersRegistryInterface
{
    public function register(EncoderInterface $encoder): void;

    /**
     * @return EncoderInterface[]
     */
    public function all(): array;

    /**
     * @phpstan-param class-string<EncoderInterface> $className
     */
    public function has(string $className): bool;
}
