<?php

declare(strict_types=1);

namespace WayOfDev\App;

use ArrayIterator;
use Webmozart\Assert\Assert;

final class Response extends ArrayIterator
{
    private function __construct(array $items)
    {
        Assert::allIsInstanceOf($items, Item::class);
        Assert::isList($items);

        parent::__construct($items);
    }

    public static function create(array $items): self
    {
        return new self($items);
    }
}
