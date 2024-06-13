<?php

declare(strict_types=1);

namespace WayOfDev\App\Object;

class Post
{
    public function __construct(
        public int $id,
        public string $text,
        public bool $active,
        public int $views
    ) {
    }
}
