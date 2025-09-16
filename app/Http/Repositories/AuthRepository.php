<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AuthRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function storeToken(int $userId, string $token, Carbon $expiresAt): void
    {
        DB::table('auth.auth_sessions')->updateOrInsert(
            ['user_id' => $userId],  
            [
                'jwt_token' => $token,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
