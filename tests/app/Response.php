<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\App;

use ArrayIterator;
use Webmozart\Assert\Assert;

final class Response extends ArrayIterator
{
    public static function create(array $items): self
    {
        return new self($items);
    }

    private function __construct(array $items)
    {
        Assert::allIsInstanceOf($items, Item::class);
        Assert::isList($items);

        parent::__construct($items);
    }
}
