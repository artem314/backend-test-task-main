<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
        private CartManager $cartManager,
    ) {
    }

    public function add(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $response = new JsonResponse();

        if (!$product) {
            return $response->response(404, JsonResponse::ERROR, ['message' => 'Product not found']);
        }

        $quantity = (int) $rawRequest['quantity'];

        if ($quantity <= 0) {
            return $response->response(400, JsonResponse::ERROR, ['message' => 'Quantity must be positive']);
        }

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $quantity,
            $product,
        ));

        return $response->response(200, JsonResponse::SUCCESS, $this->cartView->toArray($cart));
    }
}
