<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CustomerManager
{
    private LoggerInterface $logger;
    private Connector $connector;

    private string $userKey;

    public function __construct(ConnectorFacade $connectorFacade, private UserRepository $userRepository)
    {
        $this->userKey = \sprintf('%s_%s', session_id(), 'user');
        $this->connector = $connectorFacade->getConnector();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function loadUser(string $identifier)
    {
        try {
            if ($this->connector->has($this->userKey)) {
                return;
            }
        } catch (ConnectorException $e) {
            $this->logger->error($e);
        }

        $user = $this->userRepository->loadByIdentifier($identifier);

        if ($user) {
            try {
                $this->connector->set($this->userKey, $user);
            } catch (ConnectorException $e) {
                $this->logger->error($e);
            }
        }
    }

    public function getUser()
    {
        try {
            return $this->connector->get($this->userKey);
        } catch (ConnectorException $e) {
            $this->logger->error($e);
        }

        return null;
    }
}
