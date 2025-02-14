<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\ProductsView;

readonly class GetProductsController
{
    public function __construct(
        private ProductsView $productsVew,
        private ProductRepository $productRepository,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        $rawRequest = json_decode($request->getBody()->getContents(), true);

        $products = $this->productRepository->getByCategory($rawRequest['category']);

        if (!\count($products)) {
            return $response->response(404, JsonResponse::ERROR, ['message' => 'Products not found']);
        }

        $products = $this->productsVew->toArray($products);

        return $response->response(200, JsonResponse::SUCCESS, $products);
    }
}
