<?php

namespace App\Http\Repositories\OAuth;

use App\Models\OAuthRefreshToken;
use Illuminate\Support\Carbon;

class TokenRepository
{
    public function storeRefreshToken(int $userId, int $clientId, string $token, Carbon $expires): OAuthRefreshToken
    {
        return OAuthRefreshToken::create([
            'user_id' => $userId,
            'oauth_client_id' => $clientId,
            'token' => $token,
            'expires_at' => $expires,
        ]);
    }
}
