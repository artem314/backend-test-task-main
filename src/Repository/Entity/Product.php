<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository\Entity;

class Product
{
    private int $price;
    private bool $isActive;
    private string $category;
    private string $name;
    private string $description;
    private string $thumbnail;

    public function __construct(
        private readonly int $id,
        private readonly string $uuid,
        bool $isActive,
        string $category,
        string $name,
        string $description,
        string $thumbnail,
        int $price,
    ) {
        $this->thumbnail = $thumbnail;
        $this->description = $description;
        $this->name = $name;
        $this->category = $category;
        $this->isActive = $isActive;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
