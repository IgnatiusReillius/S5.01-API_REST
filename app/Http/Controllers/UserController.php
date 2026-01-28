<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() : JsonResponse {
        $users = User::all();
        
        return response()->json(['data' => $users], 200);
    }

    public function find(string $id) : JsonResponse {
        $user = User::findOrFail($id);
        
        return response()->json(['data' => $user], 200);
    }

    public function store(UserRequest $request) : JsonResponse {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json(['data' => $user], 201);
    }

    public function destroyAllUsers() : JsonResponse {
        User::query()->delete();
        
        return response()->json([], 200);
    }

    public function destroyById(string $id) : JsonResponse {
        $user = User::findOrFail($id);
        
        $user->delete();
        
        return response()->json([], 200);
    }
}
