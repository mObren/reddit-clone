<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function username()
    {
        return 'username';
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        Auth::login($user);

        return  response()->json(["message"=>"Welcome to Reddit clone, " . $validated['username'] . "!" ],200);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            return response()->json(['messageg' =>'Welcome back, ' . $credentials['username'] . '!' ],200);
        } else {
            return response()->json(['message' => 'Incorrect credentials are provided.']);
        }
    }
}
