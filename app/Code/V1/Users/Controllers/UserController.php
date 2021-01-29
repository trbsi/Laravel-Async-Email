<?php


namespace App\Code\V1\Users\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function createToken(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['username'])->first();
        if (!$user) {
            abort(404, 'User does not exist');
        }

        if (!Hash::check($validated['password'], $user->password)) {
            abort(400, 'Bad password');
        }

        $token = $user->createToken($user->email);

        return ['token' => $token->plainTextToken];
    }

    public function getUser()
    {
        return response()->json(Auth::user());
    }
}