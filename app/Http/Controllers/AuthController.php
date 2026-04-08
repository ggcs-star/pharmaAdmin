<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    /* ===============================
        SINGLE AUTH PAGE
    =============================== */
    public function index()
    {
return view('login');    }

    /* ===============================
        LOGIN
    =============================== */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        Log::info('Login Attempt', ['email' => $request->email]);

        try {
            $response = $this->apiPost('/user/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            Log::info('Login API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {

                $data = $response->json();

                if (!isset($data['token'])) {
                    return back()->with('error', 'Invalid login response');
                }

                // ✅ Session store
                session([
                    'user_token' => $data['token'],
                    'user_email' => $data['user']['email'] ?? '',
                    'user_name'  => $data['user']['name'] ?? ''
                ]);

                // ✅ Cart fetch (optional but recommended)
                try {
                    $cartResponse = $this->apiGet('/cart');

                    if ($cartResponse->successful()) {
                        $cart = $cartResponse->json();
                        session(['cart_count' => count($cart['items'] ?? [])]);
                    } else {
                        session(['cart_count' => 0]);
                    }
                } catch (\Exception $e) {
                    session(['cart_count' => 0]);
                }

                return redirect()->route('home')->with('success', 'Login successful');
            }

            $data = $response->json();

            if ($response->status() == 401) {
                return back()->with('error', $data['message'] ?? 'Invalid credentials');
            }

            if ($response->status() == 403) {
                return back()->with([
                    'error' => $data['message'] ?? 'Email not verified',
                    'show_otp' => true,
                    'email' => $request->email
                ]);
            }

            if ($response->status() == 404) {
                return back()->with('error', $data['message'] ?? 'User not found');
            }

            return back()->with('error', $data['message'] ?? 'Login failed');

        } catch (\Exception $e) {

            Log::error('Login Exception', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Server error, try again');
        }
    }

    /* ===============================
        REGISTER (SEND OTP)
    =============================== */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        try {
            $response = $this->apiPost('/user/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            Log::info('Register API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return back()->with([
                    'success' => 'OTP sent to your email',
                    'show_otp' => true,
                    'email' => $request->email
                ]);
            }

            $data = $response->json();

            return back()->with('error', $data['message'] ?? 'Registration failed');

        } catch (\Exception $e) {

            Log::error('Register Exception', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Server error, try again');
        }
    }

    /* ===============================
        VERIFY OTP
    =============================== */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        try {
            $response = $this->apiPost('/user/verify-email-otp', [
                'email' => $request->email,
                'otp' => $request->otp
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Email verified! Now login');
            }

            $data = $response->json();

            return back()->with([
                'error' => $data['message'] ?? 'Invalid OTP',
                'show_otp' => true,
                'email' => $request->email
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'OTP verification failed');
        }
    }

    /* ===============================
        RESEND OTP
    =============================== */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $this->apiPost('/user/resend-email-otp', [
                'email' => $request->email
            ]);

            return back()->with([
                'success' => 'OTP resent successfully',
                'show_otp' => true,
                'email' => $request->email
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resend OTP');
        }
    }
    public function forgotPassword(Request $request)
{
    $response = $this->apiPost('/user/forgot-password', [
        'email' => $request->email
    ]);

    if ($response->successful()) {
        return back()->with('success', 'Reset link sent');
    }

    return back()->with('error', 'Failed to send reset link');
}

    /* ===============================
        LOGOUT
    =============================== */
    public function logout()
    {
        session()->forget([
            'user_token',
            'user_email',
            'user_name',
            'cart_count'
        ]);

        return redirect()->route('home')->with('success', 'Logged out successfully');
    }
}