<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): ?Product
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM products WHERE is_active = 1 AND uuid = ?',
            [$uuid],
        );

        if (!$row) {
            return null;
        }

        return $this->make($row);
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            fn (array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = 1 AND category = ?',
                [$category],
            )
        );
    }

    private function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
