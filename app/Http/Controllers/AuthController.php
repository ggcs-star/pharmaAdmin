<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | AUTH PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('auth.login-register');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([

            'email' => 'required|email',

            'password' => 'required|min:6'
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | DEVICE ID
            |--------------------------------------------------------------------------
            */

            $deviceId =
                $request->header(
                    'X-Device-ID'
                );

            /*
            |--------------------------------------------------------------------------
            | LOGIN API
            |--------------------------------------------------------------------------
            */

            $response = $this->apiPost(

                '/user/login',

                [

                    'email' =>
                        $request->email,

                    'password' =>
                        $request->password
                ],

                [

                    'X-Device-ID' =>
                        $deviceId
                ]
            );

            Log::info('LOGIN API RESPONSE', [

                'status' =>
                    $response->status(),

                'body' =>
                    $response->body()
            ]);

            $data =
                $response->json();

            /*
            |--------------------------------------------------------------------------
            | LOGIN SUCCESS
            |--------------------------------------------------------------------------
            */

            if (
                $response->successful()
            ) {

                /*
                |--------------------------------------------------------------------------
                | TOKEN CHECK
                |--------------------------------------------------------------------------
                */

                if (
                    !isset($data['token'])
                ) {

                    return response()->json([

                        'success' => false,

                        'message' =>
                            'Invalid login response'

                    ], 500);
                }

                /*
                |--------------------------------------------------------------------------
                | SESSION STORE
                |--------------------------------------------------------------------------
                */

                session([
    'user_token' => $data['token'],
    'user_email' => $data['user']['email'] ?? '',
    'user_name'  => $data['user']['name'] ?? '',
    'device_id'  => $deviceId
]);
session()->save();

Log::info('LOGIN SESSION SAVED', [
    'device_id' => session('device_id'),
    'token' => session('user_token')
]);
                /*
                |--------------------------------------------------------------------------
                | CART COUNT
                |--------------------------------------------------------------------------
                */

                try {

                    $cartResponse =
                        $this->apiGet(
                            '/cart'
                        );

                    if (
                        $cartResponse
                            ->successful()
                    ) {

                        $cart =
                            $cartResponse
                            ->json();

                        session([

                            'cart_count' =>
                                count(
                                    $cart['items']
                                    ?? []
                                )
                        ]);

                    } else {

                        session([
                            'cart_count' => 0
                        ]);
                    }

                } catch (\Exception $e) {

                    session([
                        'cart_count' => 0
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | RESPONSE
                |--------------------------------------------------------------------------
                */

                return response()->json([

                    'success' => true,

                    'message' =>
                        'Login successful',

                    'token' =>
                        $data['token'],

                    'user' =>
                        $data['user'],

                    'trusted_device' =>
                        $data['trusted_device']
                        ?? false,

                    'device_verification_required' =>
                        $data['device_verification_required']
                        ?? false
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | LOGIN FAILED
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => false,

                'message' =>
                    $data['message']
                    ?? 'Login failed'

            ], $response->status());

        } catch (\Exception $e) {

            Log::error('LOGIN EXCEPTION', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'Server error, try again'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([

            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email',

            'password' =>
                'required|min:6|confirmed'
        ]);
        

        try {

            $deviceId =
                $request->header(
                    'X-Device-ID'
                );

            /*
            |--------------------------------------------------------------------------
            | REGISTER API
            |--------------------------------------------------------------------------
            */

           $response = $this->apiPost(
    '/user/register',
    [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'password_confirmation' => $request->password_confirmation,
        'device_id' => $deviceId
    ],
    [
        'X-Device-ID' => $deviceId
    ]
);

            Log::info('REGISTER API RESPONSE', [

                'status' =>
                    $response->status(),

                'body' =>
                    $response->body()
            ]);

            $data =
                $response->json();

            if (
                $response->successful()
            ) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'OTP sent successfully'
                ]);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    $data['message']
                    ?? 'Registration failed'

            ], $response->status());

        } catch (\Exception $e) {

            Log::error('REGISTER EXCEPTION', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'Server error, try again'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY EMAIL OTP
    |--------------------------------------------------------------------------
    */

    public function verifyOtp(Request $request)
    {
        $request->validate([

            'email' =>
                'required|email',

            'otp' =>
                'required'
        ]);

        try {

            $deviceId =
                $request->header(
                    'X-Device-ID'
                );

$response = $this->apiPost(
    '/user/verify-email-otp',
    [
        'email' => $request->email,
        'otp' => $request->otp,
        'device_id' => $deviceId
    ],
    [
        'X-Device-ID' => $deviceId
    ]
);
            $data =
                $response->json();

            if (
                $response->successful()
            ) {

               session([
    'user_token' => $data['token'] ?? '',
    'user_email' => $data['user']['email'] ?? $request->email,
    'user_name'  => $data['user']['name'] ?? 'User',
    'device_id'  => $deviceId
]);

                return response()->json([

                    'success' => true,

                    'message' =>
                        'OTP verified successfully'
                ]);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    $data['message']
                    ?? 'Invalid OTP'

            ], 422);

        } catch (\Exception $e) {

            Log::error('VERIFY OTP EXCEPTION', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'OTP verification failed'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY DEVICE OTP
    |--------------------------------------------------------------------------
    */

    public function verifyDeviceOtp(
        Request $request
    ) {

        try {

            $deviceId =
                $request->header(
                    'X-Device-ID'
                );

            $response = $this->apiPost(

                '/device/verify-otp',

                [

                    'otp' =>
                        $request->otp
                ],

                [

                    'Authorization' =>
                        'Bearer ' .
                        session('user_token'),

                    'X-Device-ID' =>
                        $deviceId
                ]
            );

            $data =
                $response->json();

            return response()->json(
                $data,
                $response->status()
            );

        } catch (\Exception $e) {

            Log::error('VERIFY DEVICE OTP', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'Device verification failed'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESEND OTP
    |--------------------------------------------------------------------------
    */
public function checkAuth(Request $request)
{
    $token = session('user_token');

    return response()->json([
        'status' => !empty($token),
        'authenticated' => !empty($token),
        'user' => !empty($token) ? [
            'name' => session('user_name'),
            'email' => session('user_email')
        ] : null
    ]);
}
    public function resendOtp(
        Request $request
    ) {

        $request->validate([

            'email' =>
                'required|email'
        ]);

        try {

            $response = $this->apiPost(

                '/user/resend-email-otp',

                [

                    'email' =>
                        $request->email
                ]
            );

            $data =
                $response->json();

            if (
                $response->successful()
            ) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'OTP resent successfully'
                ]);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    $data['message']
                    ?? 'Failed to resend OTP'

            ], $response->status());

        } catch (\Exception $e) {

            Log::error('RESEND OTP EXCEPTION', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'Failed to resend OTP'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(
        Request $request
    ) {

        try {

            $response = $this->apiPost(

                '/user/forgot-password',

                [

                    'email' =>
                        $request->email
                ]
            );

            $data =
                $response->json();

            if (
                $response->successful()
            ) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'Reset OTP sent successfully'
                ]);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    $data['message']
                    ?? 'Failed to send reset OTP'

            ], $response->status());

        } catch (\Exception $e) {

            Log::error('FORGOT PASSWORD EXCEPTION', [

                'message' =>
                    $e->getMessage()
            ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'Server error'

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */

    public function googleLogin(
        Request $request
    ) {

        $token =
            $request->token;

        if (!$token) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Google login failed'

            ], 401);
        }

        session([
            'user_token' => $token
        ]);

        return response()->json([

            'success' => true,

            'message' =>
                'Google login successful'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        session()->forget([

            'user_token',

            'user_email',

            'user_name',

            'cart_count'
        ]);

        return response()->json([

            'success' => true,

            'message' =>
                'Logged out successfully'
        ]);
    }


 
    /*
|--------------------------------------------------------------------------
| SEND DEVICE OTP
|--------------------------------------------------------------------------
*/

public function sendDeviceOtp(
    Request $request
) {

    try {

        /*
        |--------------------------------------------------------------------------
        | TOKEN
        |--------------------------------------------------------------------------
        */

        $token =
            session('user_token');

        /*
        |--------------------------------------------------------------------------
        | FALLBACK TOKEN
        |--------------------------------------------------------------------------
        */

        if (!$token) {

            $authHeader =
                $request->header(
                    'Authorization'
                );

            if (
                $authHeader &&
                str_starts_with(
                    $authHeader,
                    'Bearer '
                )
            ) {

                $token =
                    str_replace(
                        'Bearer ',
                        '',
                        $authHeader
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DEVICE ID
        |--------------------------------------------------------------------------
        */

        $deviceId =
            $request->header(
                'X-Device-ID'
            );

        /*
        |--------------------------------------------------------------------------
        | DEBUG LOG
        |--------------------------------------------------------------------------
        */

        Log::info(
            'SEND DEVICE OTP DEBUG',
            [

                'session_token' =>
                    session('user_token'),

                'final_token' =>
                    $token,

                'device_id' =>
                    $deviceId,

                'authorization_header' =>
                    $request->header(
                        'Authorization'
                    )
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if (!$token) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Authentication token missing'

            ], 401);
        }

        if (!$deviceId) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Device ID missing'

            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | API REQUEST
        |--------------------------------------------------------------------------
        */

        $response = $this->apiPost(

            '/device/send-otp',

            [],

            [

                'Authorization' =>
                    'Bearer ' . $token,

                'X-Device-ID' =>
                    $deviceId
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RESPONSE LOG
        |--------------------------------------------------------------------------
        */

        Log::info(
            'DEVICE OTP API RESPONSE',
            [

                'status' =>
                    $response->status(),

                'body' =>
                    $response->body()
            ]
        );

        return response()->json(
            $response->json(),
            $response->status()
        );

    } catch (\Exception $e) {

        Log::error(
            'SEND DEVICE OTP ERROR',
            [

                'message' =>
                    $e->getMessage()
            ]
        );

        return response()->json([

            'success' => false,

            'message' =>
                'Unable to send device OTP',

            'error' =>
                $e->getMessage()

        ], 500);
    }
}}
