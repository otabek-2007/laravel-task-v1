<?php

namespace App\Http\Services;

use App\Http\DTO\OAuth\TokenRequestDTO;
use App\Http\Repositories\OAuth\ClientRepository;
use App\Http\Repositories\OAuth\TokenRepository;
use App\Models\OAuthClient;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class OAuthService
{
    public function __construct(
        private ClientRepository $clients,
        private TokenRepository $tokens
    ) {}

    public function generateAuthorizationCode(User $user, OAuthClient $client): string
    {
        $code = Str::random(40);
        Cache::put("oauth_code:{$code}", [
            'user_id' => $user->id,
            'client_id' => $client->id,
        ], now()->addMinutes(10));

        return $code;
    }

    public function generateDummyCode(User $user, OAuthClient $client): string
    {
        $code = Str::random(40);
        Cache::put("oauth_code:{$code}", [
            'user_id' => $user->id,
            'client_id' => $client->id,
        ], now()->addMinutes(10));

        return $code;
    }

    public function exchangeCodeForToken(TokenRequestDTO $dto): array
    {
        $data = Cache::pull("oauth_code:{$dto->code}");
        if (! $data) {
            throw new \Exception('Invalid or expired code');
        }

        $user = User::findOrFail($data['user_id']);

        $accessToken = JWTAuth::fromUser($user);

        $refreshToken = Str::random(60);
        $this->tokens->storeRefreshToken(
            $data['user_id'],
            $data['client_id'],
            $refreshToken,
            now()->addDays(30)
        );

        return [
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'refresh_token' => $refreshToken,
        ];
    }
}
