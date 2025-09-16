<?php

namespace App\Http\Repositories\OAuth;

use App\Models\OAuthClient;

class ClientRepository
{
    public function findByClientId(string $id): ?OAuthClient
    {
        return OAuthClient::where('client_id', $id)->first();
    }
}
