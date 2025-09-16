<?php

namespace App\Http\Services;

use App\Http\Repositories\AuthRepository;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(private AuthRepository $userRepo) {}

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepo->create($data);

        $token = JWTAuth::fromUser($user);
        $this->userRepo->storeToken(
            $user->id,
            $token,
            now()->addMinutes((int) config('jwt.ttl'))
        );

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ];
    }

    public function login(array $credentials): array
    {
        if (! $token = auth()->attempt($credentials)) {
            throw new Exception('Invalid credentials', 401);
        }

        $user = auth()->user();

        $this->userRepo->storeToken(
            $user->id,
            $token,
            Carbon::now()->addMinutes((int) config('jwt.ttl'))
        );

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function refresh(): array
    {
        $token = auth()->refresh();
        $user = auth()->user();
        $expiresAt = Carbon::now()->addMinutes((int) config('jwt.ttl'));
        $this->userRepo->storeToken($user->id, $token, $expiresAt);

        return $this->tokenResponse($token);
    }

    public function me(): User
    {
        return auth()->user();
    }

    private function tokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ];
    }
}
