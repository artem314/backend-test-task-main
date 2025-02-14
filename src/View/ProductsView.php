<?php

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;

readonly class ProductsView
{
    public function __construct()
    {
    }

    public function toArray(array $products): array
    {
        return array_map(
            fn (Product $product) => [
                'id'          => $product->getId(),
                'uuid'        => $product->getUuid(),
                'name'        => $product->getName(),
                'category'    => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail'   => $product->getThumbnail(),
                'price'       => CurrencyConverter::convertToMajor($product->getPrice()),
            ],
            $products
        );
    }
}
