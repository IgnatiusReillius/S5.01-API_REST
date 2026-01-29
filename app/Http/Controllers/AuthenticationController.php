<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(UserRequest $request) : JsonResponse {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('access_token')->accessToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            ], 201);
    }

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
