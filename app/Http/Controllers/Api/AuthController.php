<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 *
 * Ushbu endpointlar foydalanuvchi ro‘yxatdan o‘tishi,
 * tizimga kirishi, chiqishi va tokenni yangilashi uchun.
 */
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Ro‘yxatdan o‘tish
     *
     * @bodyParam name    string required Foydalanuvchi ismi. Example: Otabek
     * @bodyParam email   string required Foydalanuvchi email. Example: otabek@example.com
     * @bodyParam password string required Kamida 8 ta belgidan iborat. Example: secret123
     * @bodyParam password_confirmation string required Parolni tasdiqlash. Example: secret123
     *
     * @response 201 {
     *  "data": {
     *      "id": 7,
     *      "name": "Otabek",
     *      "email": "otabek@example.com",
     *      "created_at": "2025-09-16 09:34:08"
     *  },
     *  "access_token": "eyJ0eXAiOiJKV1QiLCJh...",
     *  "token_type": "bearer",
     *  "expires_in": 3600
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return (new AuthResource($data['user']))
            ->additional([
                'access_token' => $data['access_token'],
                'token_type' => $data['token_type'],
                'expires_in' => $data['expires_in'],
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tizimga kirish
     *
     * @bodyParam email string required Ro‘yxatdan o‘tgan email. Example: otabek@example.com
     * @bodyParam password string required Parol. Example: secret123
     *
     * @response {
     *  "data": {
     *      "id": 7,
     *      "name": "Otabek",
     *      "email": "otabek@example.com",
     *      "created_at": "2025-09-16 09:34:08"
     *  },
     *  "access_token": "eyJ0eXAiOiJKV1QiLCJh...",
     *  "token_type": "bearer",
     *  "expires_in": 3600
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return (new AuthResource($data['user']))
            ->additional([
                'access_token' => $data['access_token'],
                'token_type' => $data['token_type'],
                'expires_in' => $data['expires_in'],
            ])
            ->response();
    }

    /**
     * Chiqish (Logout)
     *
     * @authenticated
     *
     * @response {
     *   "message": "Successfully logged out"
     * }
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Tokenni yangilash (Refresh)
     *
     * @authenticated
     *
     * @response {
     *  "access_token": "eyJ0eXAiOiJKV1QiLCJh...",
     *  "token_type": "bearer",
     *  "expires_in": 3600
     * }
     */
    public function refresh(): JsonResponse
    {
        $tokenData = $this->authService->refresh();

        return response()->json($tokenData);
    }

    /**
     * Mening ma’lumotlarim
     *
     * @authenticated
     *
     * @response {
     *  "data": {
     *      "id": 7,
     *      "name": "Otabek",
     *      "email": "otabek@example.com",
     *      "created_at": "2025-09-16 09:34:08"
     *  },
     *  "access_token": "eyJ0eXAiOiJKV1QiLCJh...",
     *  "token_type": "bearer",
     *  "expires_in": 3600
     * }
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        return (new AuthResource($user))
            ->additional([
                'access_token' => auth()->getToken()->get(),
                'token_type' => 'bearer',
                'expires_in' => (int) config('jwt.ttl') * 60,
            ])
            ->response();
    }
}
