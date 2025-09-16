<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\AuthSession;
use Filament\Resources\Pages\CreateRecord;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;

        $token = JWTAuth::fromUser($user);

        AuthSession::create([
            'user_id' => $user->id,
            'jwt_token' => $token,
            'expires_at' => now()->addHour(),
        ]);
    }
}
