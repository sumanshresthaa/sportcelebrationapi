<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    
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
    
        // 🚨 Check if user is not verified
        if (! $user->is_verified) {
            // If OTP is expired or not set, generate new one
            if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
                $otp = (string) random_int(100000, 999999);
                $expiresAt = now()->addMinutes(10);
    
                $user->update([
                    'otp_hash'       => Hash::make($otp),
                    'otp_expires_at' => $expiresAt,
                ]);
    
                // Send OTP email
                Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Your OTP Code (Login Verification)');
                });
            }
    
            return response()->json([
                'message' => 'Your account is not verified. OTP sent to your email.',
                'requires_otp' => true,
                'user_id' => $user->id,
            ], 403);
        }
    
        // ✅ Verified user → allow login
        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
            'total_points' => $user->total_points,

        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function deleteUser(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}    
