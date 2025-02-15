<?php

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Customer;

class UserRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadByIdentifier(string $identifier): ?Customer
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE email = ?',
            [$identifier],
        );

        if (!$row) {
            return null;
        }

        return $this->make($row);
    }

    private function make(array $row): Customer
    {
        return new Customer(
            $row['id'],
            $row['firstname'],
            $row['lastname'],
            $row['middlename'],
            $row['email'],
        );
    }
}
