<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quotation;
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
            'profile_image' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255'
        ]);

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->phone = $request->input('phone');
        $user->role = $request->input('role');
        $user->profile_image = $request->input('profile_image');
        $user->address = $request->input('address');
        $user->area = $request->input('area');
        $user->save();

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

    public function getUserByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'User retrieved successfully',
            'data' => $user
        ], 200);
    }

    public function getManagers()
    {
        $managers = User::where('role', 4)->get();

        $managers->transform(function ($manager) {
            $manager->quotations_count = Quotation::whereHas('business', function ($query) use ($manager) {
                $query->where('business_address', 'like', '%' . $manager->area . '%');
            })->count();

            return $manager;
        });

        return response()->json([
            'status' => 'success',
            'data' => $managers
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|string',
            'phone' => 'required|string',
            'role' => 'required|integer',
            'profile_image' => 'nullable|string',
            'address' => 'nullable|string',
            'area' => 'nullable|string'
        ];

        $validatedData = $request->validate($rules);

        $user->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }
}
