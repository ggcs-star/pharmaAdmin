<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        Log::info('Login attempt', ['email' => $request->email]);

        try {

            // ✅ API CALL (clean)
            $response = $this->apiPost('/user/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            Log::info('API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {

                $data = $response->json();

                if (isset($data['token'])) {

                    // ✅ session store
                    session([
                        'user_token' => $data['token'],
                        'user_email' => $request->email,
                        'user_name' => $data['name'] ?? $request->email
                    ]);

                    // ✅ cart count fetch
                    $cartResponse = $this->apiGet('/cart');

                    if ($cartResponse->successful()) {
                        $cart = $cartResponse->json();
                        session(['cart_count' => count($cart['items'] ?? [])]);
                    } else {
                        session(['cart_count' => 0]);
                    }

                    return redirect()->route('home')->with('success', 'Login successful!');
                }

                Log::error('Token missing', ['response' => $data]);

                return back()->with('error', 'Invalid response')->withInput();

            } else {

                if ($response->status() == 401) {
                    return back()->with('error', 'Invalid email or password')->withInput();
                }

                if ($response->status() == 422) {
                    $errors = $response->json();
                    return back()->with('error', 'Validation failed')->withInput();
                }

                return back()->with('error', 'Login failed')->withInput();
            }

        } catch (\Exception $e) {

            Log::error('Login exception', ['message' => $e->getMessage()]);

            return back()->with('error', 'Server not responding')->withInput();
        }
    }

    public function logout()
    {
        session()->forget(['user_token', 'user_email', 'user_name', 'cart_count']);
        return redirect()->route('home')->with('success', 'Logged out');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        try {

            // ✅ API CALL
            $response = $this->apiPost('/user/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            if ($response->successful()) {
                return redirect()->route('login')->with('success', 'Registration successful!');
            }

            return back()->with('error', 'Registration failed')->withInput();

        } catch (\Exception $e) {

            return back()->with('error', 'Service unavailable')->withInput();
        }
    }
}