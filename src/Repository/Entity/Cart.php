<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository\Entity;

final class Cart
{
    private string $paymentMethod;

    /**
     * @var array<CartItem>
     */
    private array $items;

    public function __construct(
        readonly private string $uuid,
        private ?Customer $customer,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }
}
