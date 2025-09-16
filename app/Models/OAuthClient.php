<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OAuthClient extends Model
{
    protected $table = 'auth.oauth_clients';

    protected $fillable = [
        'name', 'client_id', 'client_secret', 'redirect_uri', 'scopes',
    ];

    protected $casts = [
        'scopes' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->client_id = Str::uuid()->toString();
            $model->client_secret = Str::random(40);
        });
    }
}
