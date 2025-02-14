<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

readonly class Connector
{
    public function __construct(private \Redis $redis)
    {
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key)
    {
        try {
            return unserialize($this->redis->get($key));
        } catch (\RedisException $e) {
            throw new ConnectorException('Unable to get ', $e->getCode(), $e, ['key' => $key]);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, mixed $value): void
    {
        try {
            $this->redis->setex($key, 24 * 60 * 60, serialize($value));
        } catch (\RedisException $e) {
            throw new ConnectorException('Unable to set '.$key, $e->getCode(), $e, ['key' => $key, 'data' => $value]);
        }
    }

    public function has($key): bool
    {
        try {
            return $this->redis->exists($key);
        } catch (\RedisException $e) {
            throw new ConnectorException('Connector error ', $e->getCode(), $e, ['key' => $key]);
        }
    }
}
