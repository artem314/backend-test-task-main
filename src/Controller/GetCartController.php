<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController
{
    public function __construct(
        public CartView $cartView,
        public CartManager $cartManager,
    ) {
    }

    public function get(): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->cartManager->getCart();

        return $response->response(200, JsonResponse::SUCCESS, $this->cartView->toArray($cart));
    }
}
