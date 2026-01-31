<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterOtpMail;
use App\Mail\GenerateOtpMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $countUser = User::count();

        $roleAdmin = Roles::where('name', 'admin')->first();
        $roleUser = Roles:: where('name', 'user')->first();

        if($countUser === 0){
            $role_id = $roleAdmin->id;
        }else{
            $role_id = $roleUser->id;
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role_id
        ]);

        // Generate unique OTP
        do {
            $otp = mt_rand(100000, 999999);
        } while (OtpCode::where('otp', $otp)->exists());

        OtpCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'valid_until' => Carbon::now()->addMinutes(10)
            ]
        );

        // Mailer
        Mail::to($user->email)->send(new RegisterOtpMail($user));
        
        // Token
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'data'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'E-mail is not registered'
            ], 404);
        }

        do {
            $otp = mt_rand(100000, 999999);
        } while (OtpCode::where('otp', $otp)->exists());

        OtpCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'valid_until' => now()->addMinutes(10)
            ]
        );

        // Email
        Mail::to($user->email)->send(new GenerateOtpMail($user));

        return response()->json([
            'success' => true,
            'message' => 'OTP code successfully generated'
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $otpCode = OtpCode::where('otp', $request->otp)->first();

        if (!$otpCode) {
            return response()->json([
                'success' => false,
                'message' => 'OTP code not found'
            ], 404);
        }

        if (now()->gt($otpCode->valid_until)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP code is no longer valid, please regenerate'
            ], 400);
        }

        // $user = $request->user();
        $user = $otpCode->user;
        $user->email_verified_at = now();
        $user->save();

        $otpCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'email sudah terverifikasi'
        ]);
    }


    // Get authenticated user
    // public function user(Request $request)
    // {
    //     return response()->json($request->user());
    // }
    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user()->load('role')
            ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

     // Login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data'    => $user,
            'token'   => $token,
        ]);
    }
}


