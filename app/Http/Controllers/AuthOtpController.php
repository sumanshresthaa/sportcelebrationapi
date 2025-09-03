<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthOtpController extends Controller
{
    /**
     * Register a new user and send OTP
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8',
            'country_of_origin' => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'chart_id'          => 'nullable|string|max:255',
            'support_country'   => 'nullable|string|max:255',
            'winner_prediction' => 'nullable|string|max:255',
        ]);

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $user = User::create([
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'country_of_origin'=> $request->country_of_origin,
            'city'             => $request->city,
            'chart_id'         => $request->chart_id,
            'support_country'  => $request->support_country,
            'winner_prediction'=> $request->winner_prediction,
            'is_verified'      => false,
            'otp_hash'         => Hash::make($otp),
            'otp_expires_at'   => $expiresAt,
        ]);

        // Send OTP via email
        // Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
        //     $message->to($user->email)
        //         ->subject('Your OTP Code');
        // });

        return response()->json([
            'message' => 'User registered. OTP sent to email.',
            'user_id' => $user->id
        ], 201);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'otp'     => 'required|string|size:6',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->is_verified) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        if (!$user->otp_expires_at || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        if (!Hash::check($request->otp, $user->otp_hash)) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        $user->update([
            'is_verified'    => true,
            'email_verified_at' => Carbon::now(),
            'otp_hash'       => null,
            'otp_expires_at' => null,
        ]);

        return response()->json(['message' => 'OTP verified successfully']);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->is_verified) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $user->update([
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => $expiresAt,
        ]);

        Mail::raw("Your new OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });

        return response()->json(['message' => 'OTP resent successfully']);
    }
}
