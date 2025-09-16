<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
