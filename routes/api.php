<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OAuth\AuthorizeController;
use App\Http\Controllers\Api\OAuth\TokenController;
use App\Http\Controllers\Api\OAuth\UserInfoController;
use App\Http\Services\OAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::prefix('oauth')->group(function () {
    Route::middleware('auth:api')->get('/authorize', AuthorizeController::class);
    Route::post('/token', TokenController::class);
    Route::middleware('auth:api')->get('/userinfo', UserInfoController::class);
});
Route::get('/test-dummy-code', function (OAuthService $service) {
    // Dummy user va client (DB da mavjud bo'lishi kerak)
    $user = \App\Models\User::first();
    $client = \App\Models\OAuthClient::first();

    $code = $service->generateDummyCode($user, $client);

    return response()->json([
        'dummy_code' => $code,
        'note' => 'Ushbu code orqali /oauth/token test qilishingiz mumkin',
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
