<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AuthResource;

/**
 * @group OAuth 2.0
 *
 * User info endpoints.
 */
class UserInfoController extends Controller
{
    /**
     * Get the authenticated user's information.
     *
     * Returns information about the currently authenticated user
     * based on the provided access token (Bearer).
     *
     * @authenticated
     *
     * @header Authorization Bearer {access_token} Required JWT access token
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   },
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *   "token_type": "bearer",
     *   "expires_in": 3600
     * }
     *
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => auth()->getToken()->get(),
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
        ]);
    }

}
