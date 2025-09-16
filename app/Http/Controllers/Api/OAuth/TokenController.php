<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use App\Http\DTO\OAuth\TokenRequestDTO;
use App\Http\Requests\OAuth\TokenRequest;
use App\Http\Services\OAuthService;

/**
 * @group OAuth 2.0
 *
 * OAuth 2.0 token endpoints.
 */
class TokenController extends Controller
{
    /**
     * Exchange an authorization code for access and refresh tokens.
     *
     * This endpoint allows a client to exchange a valid authorization code
     * for an access token (JWT) and a refresh token.
     *
     * @bodyParam client_id string required The client ID of the OAuth application. Example: 38aa7716-c938-4cdf-9557-83c744d58971
     * @bodyParam client_secret string required The client secret of the OAuth application. Example: bGnpkQGi6zlP0nuWXu7IB71BGmlTHa4YXTK7dDgp
     * @bodyParam code string required The authorization code received from the /authorize endpoint. Example: HAQIQIY_CODE
     * @bodyParam redirect_uri string required The redirect URI used in the /authorize request. Example: https://example.com/callback
     *
     * @response 200 {
     *  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *  "token_type": "Bearer",
     *  "expires_in": 900,
     *  "refresh_token": "RANDOM_REFRESH_TOKEN_STRING"
     * }
     *
     * @response 400 {
     *  "message": "Invalid or expired code"
     * }
     *
     * @param TokenRequest $request
     * @param OAuthService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(TokenRequest $request, OAuthService $service)
    {
        $dto = TokenRequestDTO::fromArray($request->validated());

        return response()->json(
            $service->exchangeCodeForToken($dto)
        );
    }
}
