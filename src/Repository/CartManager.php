<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;
use Raketa\BackendTestTask\Repository\Entity\Cart;

class CartManager
{
    private LoggerInterface $logger;
    private Connector $connector;

    public function __construct(ConnectorFacade $connectorFacade, private readonly CustomerManager $customerManager)
    {
        $this->connector = $connectorFacade->getConnector();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (ConnectorException $e) {
            $this->logger->error($e);
        }
    }

    public function getCart()
    {
        try {
            return $this->connector->get(session_id());
        } catch (ConnectorException $e) {
            $this->logger->error($e);
        }

        return new Cart(session_id(), $this->customerManager->getUser());
    }
}
