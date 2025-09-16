<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\AuthorizeRequest;
use App\Http\Services\OAuthService;
use App\Models\OAuthClient;

class AuthorizeController extends Controller
{
    public function __invoke(AuthorizeRequest $request, OAuthService $service)
    {
        $client = OAuthClient::where('client_id', $request->client_id)->firstOrFail();
        $code   = $service->generateAuthorizationCode($request->user(), $client);

        return redirect()->away(
            $request->redirect_uri . '?code=' . $code . '&state=' . $request->state
        );
    }
}