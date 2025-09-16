<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthRefreshToken extends Model
{
    protected $table = 'auth.oauth_refresh_tokens';

    protected $fillable = [
        'user_id', 'oauth_client_id', 'token', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
