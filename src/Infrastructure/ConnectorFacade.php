<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

class ConnectorFacade
{
    private Connector $connector;

    public function __construct(
        private string $host,
        private int $port,
        private ?string $password,
        private ?int $dbindex,
    ) {
    }

    public function getConnector()
    {
        return $this->connector;
    }

    protected function build(): void
    {
        $redis = new \Redis();

        try {
            $redis->connect(
                $this->host,
                $this->port,
            );

            $isConnected = $redis->isConnected();

            if (!$isConnected && 'PONG' === $redis->ping()) {
                $redis->auth($this->password);
                $redis->select($this->dbindex);
                $this->connector = new Connector($redis);
            }
        } catch (\RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }
}
