<?php

declare(strict_types=1);

namespace WayOfDev\App\Response;

use ArrayIterator;
use Webmozart\Assert\Assert;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends ArrayIterator<int, Item>
 */
final class Response extends ArrayIterator
{
    /**
     * @param array<Item> $items
     */
    private function __construct(array $items)
    {
        Assert::allIsInstanceOf($items, Item::class);
        Assert::isList($items);

        parent::__construct($items);
    }

    /**
     * @param array<Item> $items
     *
     * @return self<int, Item>
     */
    public static function create(array $items): self
    {
        return new self($items);
    }
}
