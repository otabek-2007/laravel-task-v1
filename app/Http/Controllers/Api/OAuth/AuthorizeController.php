<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\AuthorizeRequest;
use App\Http\Services\OAuthService;
use App\Models\OAuthClient;

/**
 * @group OAuth 2.0
 *
 * API endpoints for OAuth 2.0 authorization.
 */
class AuthorizeController extends Controller
{
    /**
     * Authorize a client and generate an authorization code.
     *
     * This endpoint checks the authenticated user and generates an
     * OAuth2 authorization code for the client application.
     *
     * @authenticated
     *
     * @bodyParam client_id string required The client ID of the OAuth application. Example: 38aa7716-c938-4cdf-9557-83c744d58971
     * @bodyParam redirect_uri string required The URL to redirect the user after authorization. Example: https://example.com/callback
     * @bodyParam state string optional A state parameter to maintain state between request and callback. Example: xyz123
     * @bodyParam scope string optional The requested scopes. Example: read write
     *
     * @response 302 Redirects to the provided redirect_uri with a code and state query parameters.
     *
     * @param AuthorizeRequest $request
     * @param OAuthService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(AuthorizeRequest $request, OAuthService $service)
    {
        $client = OAuthClient::where('client_id', $request->client_id)->firstOrFail();

        $code = $service->generateAuthorizationCode($request->user(), $client);

        return redirect()->away(
            $request->redirect_uri.'?code='.$code.'&state='.$request->state
        );
    }
}
