<?php

namespace App\DTO;

class AuthorizeRequestDTO
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $redirectUri,
        public readonly ?string $scope = null,
        public readonly ?string $state = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['client_id'],
            $data['redirect_uri'],
            $data['scope'] ?? null,
            $data['state'] ?? null
        );
    }
}
