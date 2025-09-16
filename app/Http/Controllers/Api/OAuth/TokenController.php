<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use App\Http\DTO\OAuth\TokenRequestDTO;
use App\Http\Requests\OAuth\TokenRequest;
use App\Http\Services\OAuthService;

class TokenController extends Controller
{
    public function __invoke(TokenRequest $request, OAuthService $service)
    {
        $dto = TokenRequestDTO::fromArray($request->validated());

        return response()->json(
            $service->exchangeCodeForToken($dto)
        );
    }
}
