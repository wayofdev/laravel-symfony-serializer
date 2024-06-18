<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface NormalizerRegistrationStrategy
{
    /**
     * @return iterable<array{normalizer: NormalizerInterface|DenormalizerInterface, priority: int<0, max>}>
     */
    public function normalizers(): iterable;
}
