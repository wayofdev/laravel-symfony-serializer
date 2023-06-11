<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;

interface ConfigRepository
{
    public function normalizers(): array;

    public function encoders(): array;

    public function metadataLoader(): LoaderInterface;
}
