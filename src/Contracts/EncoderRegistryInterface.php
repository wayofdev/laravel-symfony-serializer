<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

interface EncoderRegistryInterface
{
    public function register(EncoderInterface $encoder): void;

    /**
     * @return list<EncoderInterface|DecoderInterface>
     */
    public function all(): array;

    /**
     * @param class-string<EncoderInterface|DecoderInterface> $className
     */
    public function has(string $className): bool;
}
