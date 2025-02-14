<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use Raketa\BackendTestTask\Repository\Entity\Product;

final readonly class CartItem
{
    public function __construct(
        private string $uuid,
        private int $quantity,
        private Product $product,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->quantity * $this->product->getPrice();
    }
}
