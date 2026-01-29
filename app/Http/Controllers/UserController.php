<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index() : JsonResponse {
        $users = User::all();
        
        return response()->json(['data' => $users], 200);
    }

    public function find(string $id) : JsonResponse {

        $user = User::findOrFail($id);
        
        $this->authorize('view', $user);

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

    public function update(UserUpdateRequest $request, string $id) : JsonResponse {

        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        $data = $request->only(['name', 'email', 'password']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        
        $data = $request->validated();

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user, 200);
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
