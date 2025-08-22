<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'country_of_origin' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'chart_id' => 'nullable|string|max:255',
            'support_country' => 'nullable|string|max:255',
            'winner_prediction' => 'nullable|string|max:255',
        ]);
    
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'country_of_origin' => $request->country_of_origin,
            'city' => $request->city,
            'chart_id' => $request->chart_id,
            'support_country' => $request->support_country,
            'winner_prediction' => $request->winner_prediction,
        ]);
    
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
    

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
