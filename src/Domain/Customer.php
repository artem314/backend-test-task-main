<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final class Customer
{
    private string $firstName;
    private string $lastName;
    private string $middleName;
    private string $email;

    public function __construct(
        private readonly int $id,
        string $firstName,
        string $lastName,
        string $middleName,
        string $email,
    ) {
        $this->email = $email;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): string
    {
        $fullname = implode(' ', [
            $this->getLastName(),
            $this->getFirstName(),
            $this->getMiddleName(),
        ]);

        return $fullname;
    }
}
