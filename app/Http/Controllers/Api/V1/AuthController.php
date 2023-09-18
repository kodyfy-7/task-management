<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response()->error('Credentials do not match', 401);
        }
        
        $user = User::where('email', $request->email)->first();
        
        return response()->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ], null, 200);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ], null, 201);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }
}
