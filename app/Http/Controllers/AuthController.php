<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;

class  AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => bcrypt($validated['password'])
        ]);

        UserGroup::create([
            'group_id' => Group::where('group_name', 'normal')->first()->id,
            'user_id'  => $user->id
        ]);

        $token = $user->createToken('access:token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response(['message' => 'fail login'], 401);
        }

        $userGroups = $user->groups->pluck('group_name');
        $token = $user->createToken('access:token', $userGroups->toArray())->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }



    public function logout() {
        auth()->user()->tokens()->delete();
        return response(['message' => 'success'], 200);
    }
}
