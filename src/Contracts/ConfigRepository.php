<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;

interface ConfigRepository
{
    /**
     * @return class-string<NormalizerRegistrationStrategy>
     */
    public function normalizerRegistrationStrategy(): string;

    /**
     * @return class-string<EncoderRegistrationStrategy>
     */
    public function encoderRegistrationStrategy(): string;

    public function metadataLoader(): LoaderInterface;
}
