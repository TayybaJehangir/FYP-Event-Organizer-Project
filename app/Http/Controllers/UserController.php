<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'phone' => 'required|string|min:10',
            'role' => 'required|integer',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Sign up successful',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        
        // $credentials = request(['email', 'password']);

        // if (!Auth::attempt($credentials)) {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Invalid login credentials'
        //     ], 401);
        // }

        // $user = Auth::user();
        // $user->tokens()->delete(); // If you're using Laravel Sanctum for API token
        // $token = $user->createToken('Personal Access Token')->accessToken; // If you're using Laravel Sanctum for API token

        $user = User::where('email', $request->email)->first();
        if (!$user || $user->password !== $request->password) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged in successfully',
            'data' => [
                'user' => $user,
                // 'token' => $token // If you're using Laravel Sanctum for API token
            ]
        ], 200);
    }
}
