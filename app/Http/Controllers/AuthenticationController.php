<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request) : JsonResponse {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('access_token')->accessToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }
    
    public function logout(Request $request) : JsonResponse {

        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out']);
    }

    public function refresh(Request $request) : JsonResponse {
        $user = $request->user();

        $request->user()->token()->revoke();

        $newToken = $user->createToken('access_token')->accessToken;

        return response()->json([
            'access_token' => $newToken,
        ]);
    }
    
    public function me(Request $request) : JsonResponse {

        return response()->json([
            'data' => $request->user()
        ]);
    }
}
