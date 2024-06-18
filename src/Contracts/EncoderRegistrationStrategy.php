<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

interface EncoderRegistrationStrategy
{
    /**
     * @return iterable<array{encoder: EncoderInterface|DecoderInterface}>
     */
    public function encoders(): iterable;
}
