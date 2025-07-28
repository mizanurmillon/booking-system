<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordOtp;
use App\Mail\RegistationOtp;
use App\Models\EmailOtp;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use ApiResponse;

    /**
     * Send a Forgot Password (OTP) to the user via email.
     *
     * @param  \App\Models\User  $user
     * @return void
     */

    private function sendOtp($user)
    {
        $code = rand(1000, 9999);

        // Store verification code in the database
        $verification = EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_code' => $code,
                'expires_at'        => Carbon::now()->addMinutes(1),
            ]
        );

        Mail::to($user->email)->send(new ForgotPasswordOtp($user, $code));
    }

    /**
     * Send a Register (OTP) to the user via email.
     *
     * @param  \App\Models\User  $user
     * @return void
     */

    private function verifyOTP($user)
    {
        $code = rand(1000, 9999);

        // Store verification code in the database
        $verification = EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_code' => $code,
                'expires_at' => Carbon::now()->addMinutes(15)
            ]
        );

        Mail::to($user->email)->send(new RegistationOtp($user, $code));
    }

    /**
     * User Login
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the Login query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error([], 'Invalid credentials', 401);
        }

        if ($user->email_verified_at == null) {
            $this->verifyOTP($user); // your existing logic
            $user->setAttribute('token', null);
        } else {
        // Optional: Revoke previous tokens if needed
        $user->tokens()->delete();

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->setAttribute('token', $token);
        }

        return $this->success($user, 'User authenticated successfully', 200);
    }
    
}
