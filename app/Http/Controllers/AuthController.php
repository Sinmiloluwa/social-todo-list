<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $existingUser = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if ($existingUser) {
            if ($existingUser->email === $request->email) {
                return $this->badRequestResponse('Email already exists');
            }

            if ($existingUser->username === $request->username) {
                return $this->badRequestResponse('Username already exists');
            }
        }

        $user = User::create([
            ...$request->validated(),
            'type' => $request->type ?? 'user',
            'password' => bcrypt($request->password),
        ]);

        return $this->okResponse('User registered successfully', new UserResource($user));
    }

    public function login(Request $request)
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        return $this->okResponse('Logged in', new UserResource($user));
    }
}
