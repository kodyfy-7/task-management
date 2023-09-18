<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class ProfileController extends Controller
{

    public function index()
    {        
        $user = Auth::user();
        
        return response()->success(['user' => $user,], null, 200);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->validated($request->only(['name', 'password']));
        
        $user->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        return response()->success(null, 'Profile updated successfully', 200);
    }
}
