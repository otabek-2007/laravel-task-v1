<?php

namespace App\DTO;

class TokenRequestDTO
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $code,
        public readonly string $redirectUri
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['client_id'],
            $data['client_secret'],
            $data['code'],
            $data['redirect_uri']
        );
    }
}
