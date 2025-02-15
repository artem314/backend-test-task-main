<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Cart;

readonly class CartView
{
    public function __construct(private ProductsView $productsView)
    {
    }

    public function toArray(Cart $cart): array
    {
        $customer = $cart->getCustomer();

        $customerData = [];

        if ($customer) {
            $customerData = [
                'id'    => $customer->getId(),
                'name'  => $customer->getFullName(),
                'email' => $customer->getEmail(),
            ];
        }

        $data = [
            'uuid'           => $cart->getUuid(),
            'customer'       => $customerData,
            'payment_method' => $cart->getPaymentMethod(),
        ];

        $total = 0;
        $data['items'] = [];

        $items = $cart->getItems();

        foreach ($items as $item) {
            $cartItemPrice = $item->getPrice();

            $total += $cartItemPrice;
            $product = $item->getProduct();

            $data['items'][] = [
                'uuid'     => $item->getUuid(),
                'price'    => CurrencyConverter::convertToMajor($cartItemPrice),
                'quantity' => $item->getQuantity(),
                'product'  => $this->productsView->toArray([$product]),
            ];
        }

        $data['total'] = CurrencyConverter::convertToMajor($total);

        return $data;
    }
}
