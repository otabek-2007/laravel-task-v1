<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OAuthRefreshToken extends Model
{
    protected $fillable = [
        'user_id','oauth_client_id','token','expires_at'
    ];

    protected $dates = ['expires_at'];
}
