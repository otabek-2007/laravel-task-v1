<?php

namespace App\Http\Services;

use App\Http\Repositories\ClientRepository;
use App\Http\Repositories\TokenRepository;
use App\Models\User;
use App\Models\OAuthClient;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
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

    public function exchangeCodeForToken(TokenRequestDTO $dto): array
    {
        $data = Cache::pull("oauth_code:{$dto->code}");
        if (!$data) {
            throw new \Exception('Invalid or expired code');
        }

        // access token (JWT)
        $payload = [
            'sub' => $data['user_id'],
            'iss' => config('app.url'),
            'exp' => now()->addMinutes(15)->timestamp,
        ];
        $accessToken = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        // refresh token
        $refreshToken = Str::random(60);
        $this->tokens->storeRefreshToken(
            $data['user_id'],
            $data['client_id'],
            $refreshToken,
            now()->addDays(30)
        );

        return [
            'access_token'  => $accessToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 900,
            'refresh_token' => $refreshToken,
        ];
    }
}
