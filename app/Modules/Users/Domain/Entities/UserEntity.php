<?php

namespace Users\Domain\Entities;

class UserEntity
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private ?string $password = null
    ) {}

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    
    // Example business rule
    public function isValidEmail(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
