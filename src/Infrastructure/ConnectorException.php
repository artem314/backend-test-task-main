<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

class ConnectorException extends \Exception
{
    public function __construct(
        protected $message,
        protected $code,
        protected ?\Throwable $previous,
        private readonly mixed $params = [],
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return \sprintf(
            '[%s] %s in %s on line %d with params: %s',
            $this->getCode(),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
            json_encode($this->params)
        );
    }
}
