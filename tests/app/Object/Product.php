<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\App\Object;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Product
{
    public function __construct(
        #[Groups('group1')]
        public int $id,
        #[Groups('group1')]
        public string $title,
        #[Groups('group2')]
        public float $price,
        #[Groups('group2')]
        public bool $active,
        #[Groups('group3')]
        #[SerializedName('product_views')]
        public int $views
    ) {
    }
}
