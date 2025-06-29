<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json(['message' => 'Logged out']);
    }
}
