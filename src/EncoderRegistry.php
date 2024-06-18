<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy;
use WayOfDev\Serializer\Contracts\EncoderRegistryInterface;

use function array_values;

class EncoderRegistry implements EncoderRegistryInterface
{
    /**
     * @var array<class-string, EncoderInterface|DecoderInterface>
     */
    private array $encoders = [];

    public function __construct(EncoderRegistrationStrategy $strategy)
    {
        foreach ($strategy->encoders() as $encoder) {
            $this->register($encoder['encoder']);
        }
    }

    public function register(EncoderInterface|DecoderInterface $encoder): void
    {
        if (! $this->has($encoder::class)) {
            $this->encoders[$encoder::class] = $encoder;
        }
    }

    /**
     * @return list<EncoderInterface|DecoderInterface>
     */
    public function all(): array
    {
        return array_values($this->encoders);
    }

    /**
     * @phpstan-param class-string<EncoderInterface|DecoderInterface> $className
     */
    public function has(string $className): bool
    {
        return isset($this->encoders[$className]);
    }
}
