<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $fillable = [
        'name','client_id','client_secret','redirect_uri','scopes'
    ];

    protected $casts = [
        'scopes' => 'array',
    ];
}