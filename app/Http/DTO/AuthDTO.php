<?php

namespace App\Http\DTO;

class AuthDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
        );
    }
}
