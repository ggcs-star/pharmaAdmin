<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediQuick | Login or Signup</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #eef2f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .auth-card {
            background: #fff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-wrap: wrap;
        }

        .auth-brand {
            flex: 1.2;
            background: #fff8f8;
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            border-right: 1px solid #ffe0db;
        }

        .brand-logo {
            width: 160px;
            margin: 0 auto 20px auto;
            display: block;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 800;
            color: #1e1e2a;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .brand-tagline {
            color: #5a5a6e;
            font-size: 14px;
            line-height: 1.7;
            margin-top: 12px;
        }

        .brand-features {
            margin-top: 32px;
            text-align: left;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #2d2d3a;
        }

        .feature-item i {
            width: 28px;
            height: 28px;
            background: #ff6f61;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .auth-forms {
            flex: 1.5;
            padding: 44px 40px;
            background: #ffffff;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: #212121;
            margin-bottom: 6px;
        }

        .form-header p {
            color: #6c6c7a;
            font-size: 14px;
        }

        .auth-tabs {
            display: flex;
            background: #f5f5f5;
            border-radius: 14px;
            padding: 4px;
            margin-bottom: 28px;
        }

        .auth-tab {
            flex: 1;
            height: 48px;
            border: none;
            background: transparent;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            transition: 0.2s;
            cursor: pointer;
            color: #5a5a6e;
        }

        .auth-tab.active {
            background: #fff;
            color: #ff6f61;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .input-group-custom {
            margin-bottom: 20px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            pointer-events: none;
        }

        .auth-input {
            width: 100%;
            height: 52px;
            border: 1px solid #e8e8ec;
            border-radius: 14px;
            padding: 0 16px 0 44px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            background: #fefefe;
        }

        .auth-input:focus {
            border-color: #ff6f61;
            box-shadow: 0 0 0 3px rgba(255, 111, 97, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .auth-input {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #888;
            cursor: pointer;
            font-size: 18px;
            z-index: 2;
        }

        .forgot-link {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 22px;
        }

        .forgot-link a {
            font-size: 13px;
            font-weight: 600;
            color: #ff6f61;
            text-decoration: none;
            cursor: pointer;
        }

        .auth-submit {
            width: 100%;
            height: 52px;
            background: #ff6f61;
            border: none;
            border-radius: 14px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            transition: 0.2s;
            margin-top: 8px;
        }

        .auth-submit:hover {
            background: #e55a4e;
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: #999;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ececec;
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .google-btn {
            width: 100%;
            height: 52px;
            border: 1px solid #e0e0e6;
            background: white;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: 0.2s;
            cursor: pointer;
        }

        .google-btn:hover {
            background: #fafafa;
            border-color: #ff6f61;
        }

        .password-hint {
            background: #fff7f6;
            border: 1px solid #ffe0db;
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 6px;
            margin-bottom: 16px;
        }

        .password-hint .hint-title {
            font-size: 12px;
            font-weight: 700;
            color: #ff6f61;
            margin-bottom: 6px;
        }

        .password-hint ul {
            margin: 0;
            padding-left: 18px;
            font-size: 11px;
            color: #666;
            line-height: 1.5;
        }

        /* OTP Input Styles */
        .otp-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 24px;
        }
        .otp-input {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            border: 2px solid #e8e8ec;
            border-radius: 12px;
            outline: none;
        }
        .otp-input:focus {
            border-color: #ff6f61;
        }
        .resend-otp {
            text-align: center;
            margin-top: 12px;
        }
        .resend-otp button {
            background: none;
            border: none;
            color: #ff6f61;
            font-weight: 600;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            color: #666;
            font-size: 14px;
            text-decoration: none;
        }

        .back-link:hover {
            color: #ff6f61;
        }

        @media (max-width: 800px) {
            .auth-card {
                flex-direction: column;
                border-radius: 24px;
            }
            .auth-brand {
                border-right: none;
                border-bottom: 1px solid #ffe0db;
                padding: 32px 24px;
            }
            .auth-forms {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        
        <!-- LEFT SIDE: Branding -->
        <div class="auth-brand">
            <img src="{{ asset('images/mediquick-logo.png') }}" alt="MediQuick" class="brand-logo">
            <h1 class="brand-title">Make Healthcare Simpler</h1>
            <p class="brand-tagline">Get medicine information, order medicines, book lab tests and consult doctors online.</p>
            
            <div class="brand-features">
                <div class="feature-item">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>Free delivery on orders ₹499+</span>
                </div>
                <div class="feature-item">
                    <i class="fa-solid fa-shield-heart"></i>
                    <span>100% genuine medicines</span>
                </div>
                <div class="feature-item">
                    <i class="fa-regular fa-clock"></i>
                    <span>30-min express delivery*</span>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Dynamic Forms -->
        <div class="auth-forms">
            <!-- Normal Login/Register Section -->
            <div id="mainAuthSection">
                <div class="form-header">
                    <h2 id="dynamicTitle">Login</h2>
                    <p>Access your orders, lab tests & healthcare services</p>
                </div>

                <div class="auth-tabs">
                    <button type="button" class="auth-tab active" id="tabLoginBtn">Login</button>
                    <button type="button" class="auth-tab" id="tabRegisterBtn">Signup</button>
                </div>

                <!-- LOGIN FORM -->
                <div id="loginSection">
                    <form id="loginForm">
                        @csrf
                        <div class="input-group-custom input-icon">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" class="auth-input" placeholder="Email Address" required>
                        </div>
                        <div class="input-group-custom password-wrapper">
                            <i class="fa-solid fa-lock" style="left: 16px; position: absolute; top: 50%; transform: translateY(-50%); z-index: 1; color: #aaa;"></i>
                            <input type="password" name="password" id="loginPassword" class="auth-input" placeholder="Password" required style="padding-left: 44px;">
                            <button type="button" class="password-toggle" data-target="loginPassword">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="forgot-link">
                            <a href="#" id="forgotPasswordBtn">Forgot Password?</a>
                        </div>
                        <button type="submit" class="auth-submit">Continue</button>
                    </form>
                </div>

                <!-- REGISTER FORM -->
                <div id="registerSection" class="hidden">
                    <form id="registerForm">
                        @csrf
                        <div class="input-group-custom input-icon">
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="name" class="auth-input" placeholder="Full Name" required>
                        </div>
                        <div class="input-group-custom input-icon">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" class="auth-input" placeholder="Email Address" required>
                        </div>
                        <div class="input-group-custom password-wrapper">
                            <i class="fa-solid fa-lock" style="left: 16px; position: absolute; top: 50%; transform: translateY(-50%); z-index: 1; color: #aaa;"></i>
                            <input type="password" name="password" id="regPassword" class="auth-input" placeholder="Create Password" required style="padding-left: 44px;">
                            <button type="button" class="password-toggle" data-target="regPassword">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-group-custom password-wrapper">
                            <i class="fa-solid fa-lock" style="left: 16px; position: absolute; top: 50%; transform: translateY(-50%); z-index: 1; color: #aaa;"></i>
                            <input type="password" name="password_confirmation" id="regConfirmPassword" class="auth-input" placeholder="Confirm Password" required style="padding-left: 44px;">
                            <button type="button" class="password-toggle" data-target="regConfirmPassword">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="password-hint">
                            <div class="hint-title">Password must contain:</div>
                            <ul>
                                <li>Minimum 8 characters</li>
                                <li>1 Uppercase letter (A-Z)</li>
                                <li>1 Lowercase letter (a-z)</li>
                                <li>1 Number (0-9)</li>
                                <li>1 Special character (@$!%*?&)</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="auth-submit">Create Account</button>
                    </form>
                </div>

                <div class="divider">OR</div>
                
                <button class="google-btn" id="googleAuthBtn">
                    <i class="fab fa-google"></i> Continue with Google
                </button>
            </div>

            <!-- OTP Verification Section (Hidden by default) -->
            <div id="otpSection" class="hidden">
                <div class="form-header">
                    <h2>Verify OTP</h2>
                    <p>Please enter the verification code sent to your email</p>
                </div>
                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-input" id="otp1" autofocus>
                    <input type="text" maxlength="1" class="otp-input" id="otp2">
                    <input type="text" maxlength="1" class="otp-input" id="otp3">
                    <input type="text" maxlength="1" class="otp-input" id="otp4">
                    <input type="text" maxlength="1" class="otp-input" id="otp5">
                    <input type="text" maxlength="1" class="otp-input" id="otp6">
                </div>
                <div class="resend-otp">
                    <p>Didn't receive code? <button type="button" id="resendOtpBtn">Resend OTP</button></p>
                </div>
                <button type="button" id="verifyOtpBtn" class="auth-submit">Verify & Continue</button>
                <button type="button" id="backToRegisterBtn" class="back-link" style="margin-top: 16px;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Signup
                </button>
            </div>

            <!-- Forgot Password Section (Hidden by default) -->
            <div id="forgotSection" class="hidden">
                <div class="form-header">
                    <h2>Reset Password</h2>
                    <p>Enter your email to receive OTP</p>
                </div>
                <form id="forgotForm">
                    @csrf
                    <div class="input-group-custom input-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" id="forgotEmail" class="auth-input" placeholder="Email Address" required>
                    </div>
                    <button type="submit" class="auth-submit">Send OTP</button>
                </form>
                <button type="button" id="backToLoginBtn" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Login
                </button>
            </div>

            <!-- Reset Password with OTP Section -->
            <div id="resetPasswordSection" class="hidden">
                <div class="form-header">
                    <h2>Set New Password</h2>
                    <p>Enter OTP and your new password</p>
                </div>
                <form id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="email" id="resetEmail">
                    <div class="otp-container" style="margin-bottom: 20px;">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp1">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp2">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp3">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp4">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp5">
                        <input type="text" maxlength="1" class="otp-input" id="resetOtp6">
                    </div>
                    <div class="input-group-custom password-wrapper">
                        <i class="fa-solid fa-lock" style="left: 16px; position: absolute; top: 50%; transform: translateY(-50%); z-index: 1; color: #aaa;"></i>
                        <input type="password" name="password" id="newPassword" class="auth-input" placeholder="New Password" required style="padding-left: 44px;">
                        <button type="button" class="password-toggle" data-target="newPassword">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-group-custom password-wrapper">
                        <i class="fa-solid fa-lock" style="left: 16px; position: absolute; top: 50%; transform: translateY(-50%); z-index: 1; color: #aaa;"></i>
                        <input type="password" name="password_confirmation" id="confirmNewPassword" class="auth-input" placeholder="Confirm New Password" required style="padding-left: 44px;">
                        <button type="button" class="password-toggle" data-target="confirmNewPassword">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <button type="submit" class="auth-submit">Reset Password</button>
                    <button type="button" id="backToForgotBtn" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                </form>
            </div>

            <a href="{{ url('/') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {




    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const mainAuthSection =
        document.getElementById('mainAuthSection');

    const otpSection =
        document.getElementById('otpSection');

    const forgotSection =
        document.getElementById('forgotSection');

    const resetPasswordSection =
        document.getElementById('resetPasswordSection');

    const loginSection =
        document.getElementById('loginSection');

    const registerSection =
        document.getElementById('registerSection');

    const tabLogin =
        document.getElementById('tabLoginBtn');

    const tabRegister =
        document.getElementById('tabRegisterBtn');

    const dynamicTitle =
        document.getElementById('dynamicTitle');

    const loginForm =
        document.getElementById('loginForm');

    const registerForm =
        document.getElementById('registerForm');

    let tempEmail = '';

    /*
    |--------------------------------------------------------------------------
    | DEVICE ID
    |--------------------------------------------------------------------------
    */

    function getOrCreateDeviceId() {

        let deviceId =
            localStorage.getItem('device_id');

        if (!deviceId) {

            deviceId = crypto.randomUUID();

            localStorage.setItem(
                'device_id',
                deviceId
            );
        }

        return deviceId;
    }

    /*
    |--------------------------------------------------------------------------
    | TOKEN
    |--------------------------------------------------------------------------
    */

    function saveToken(token) {

        localStorage.setItem(
            'auth_token',
            token
        );
    }

    function getToken() {

        return localStorage.getItem(
            'auth_token'
        );
    }

    function removeToken() {

        localStorage.removeItem(
            'auth_token'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO LOGIN CHECK
    |--------------------------------------------------------------------------
    */
async function checkAuth() {

    const token = getToken();

    if (!token) {
        return;
    }

    try {

        const response = await apiRequest(
            '/auth/check',
            'GET'
        );

        if (
            response.ok &&
            response.data &&
            response.data.user
        ) {

            console.log(
                'User already authenticated'
            );

            window.location.href = '/';
            return;
        }

        console.log(
            'Token exists but user not authenticated'
        );

    } catch (e) {

        console.error(e);
    }
}

checkAuth();
    /*
    |--------------------------------------------------------------------------
    | REQUEST
    |--------------------------------------------------------------------------
    */

    async function apiRequest(
        endpoint,
        method = 'POST',
        body = null
    ) {

        const headers = {

            'Content-Type':
                'application/json',

            'Accept':
                'application/json',

            'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,

            'X-Device-ID':
                getOrCreateDeviceId()
        };

        const token = getToken();

        if (token) {

            headers['Authorization'] =
                'Bearer ' + token;
        }

  const response = await fetch(
    endpoint,
            {
                method,
                headers,
                body:
                    body
                    ? JSON.stringify(body)
                    : null
            }
        );

        let data = {};

        try {

            data =
                await response.json();

        } catch (e) {

            data = {};
        }

        /*
        |--------------------------------------------------------------------------
        | AUTO LOGOUT IF TOKEN INVALID
        |--------------------------------------------------------------------------
        */

        if (
    response.status === 401 &&
    window.location.pathname !== '/login'
) {

    console.log('401 received');

    removeToken();

    localStorage.removeItem(
        'user_data'
    );

    window.location.href =
        '/login';
}

        return {
            ok: response.ok,
            status: response.status,
            data
        };
    }

    /*
    |--------------------------------------------------------------------------
    | ALERT
    |--------------------------------------------------------------------------
    */

    function showAlert(
        icon,
        title,
        text
    ) {

        Swal.fire({

            icon,
            title,
            text,

            confirmButtonColor:
                '#ff6f61'
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UI
    |--------------------------------------------------------------------------
    */

    function showMainAuth() {

        mainAuthSection
            ?.classList
            .remove('hidden');

        otpSection
            ?.classList
            .add('hidden');

        forgotSection
            ?.classList
            .add('hidden');

        resetPasswordSection
            ?.classList
            .add('hidden');
    }

    function showOtpSection(
        email = ''
    ) {

        tempEmail = email;

        mainAuthSection
            ?.classList
            .add('hidden');

        otpSection
            ?.classList
            .remove('hidden');

        for (
            let i = 1;
            i <= 6;
            i++
        ) {

            const input =
                document.getElementById(
                    `otp${i}`
                );

            if (input) {

                input.value = '';
            }
        }

        document
            .getElementById(
                'otp1'
            )
            ?.focus();
    }

    function showLogin() {

        loginSection
            ?.classList
            .remove('hidden');

        registerSection
            ?.classList
            .add('hidden');

        tabLogin
            ?.classList
            .add('active');

        tabRegister
            ?.classList
            .remove('active');

        if (dynamicTitle) {

            dynamicTitle.innerText =
                'Login';
        }
    }

    function showRegister() {

        loginSection
            ?.classList
            .add('hidden');

        registerSection
            ?.classList
            .remove('hidden');

        tabRegister
            ?.classList
            .add('active');

        tabLogin
            ?.classList
            .remove('active');

        if (dynamicTitle) {

            dynamicTitle.innerText =
                'Signup';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | OTP VALUE
    |--------------------------------------------------------------------------
    */

    function getOtpValue() {

        let otp = '';

        for (
            let i = 1;
            i <= 6;
            i++
        ) {

            otp += document
                .getElementById(
                    `otp${i}`
                )?.value || '';
        }

        return otp;
    }

    /*
    |--------------------------------------------------------------------------
    | OTP INPUT AUTO MOVE
    |--------------------------------------------------------------------------
    */

    for (
        let i = 1;
        i <= 6;
        i++
    ) {

        const input =
            document.getElementById(
                `otp${i}`
            );

        input?.addEventListener(
            'input',
            function() {

                if (
                    this.value.length === 1 &&
                    i < 6
                ) {

                    document
                        .getElementById(
                            `otp${i + 1}`
                        )
                        ?.focus();
                }
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TABS
    |--------------------------------------------------------------------------
    */

    tabLogin?.addEventListener(
        'click',
        showLogin
    );

    tabRegister?.addEventListener(
        'click',
        showRegister
    );

    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */

    document
    .getElementById(
        'googleAuthBtn'
    )
    ?.addEventListener(
        'click',
        function() {

            window.location.href =
                '/auth-success';
        }
    );

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    loginForm?.addEventListener(
        'submit',
        async function(e) {

            e.preventDefault();

            const email =
                loginForm
                .querySelector(
                    'input[name="email"]'
                )
                .value
                .trim();

            const password =
                loginForm
                .querySelector(
                    'input[name="password"]'
                )
                .value;

            try {

                const response =
                    await apiRequest(
                        '/auth/login',
                        'POST',
                        {
                            email,
                            password
                        }
                    );

                if (!response.ok) {

                    showAlert(
                        'error',
                        'Login Failed',
                        response.data.message ||
                        'Invalid credentials'
                    );

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | SAVE TOKEN
                |--------------------------------------------------------------------------
                */

                if (
                    response.data.token
                ) {

                    saveToken(
                        response.data.token
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DEVICE VERIFICATION
                |--------------------------------------------------------------------------
                */

                if (
                    response.data
                    .device_verification_required
                ) {

                    tempEmail = email;

                    const otpResponse =
                        await fetch(
                            '/send-device-otp',
                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        document.querySelector(
                                            'meta[name="csrf-token"]'
                                        ).content,

                                    'Authorization':
                                        'Bearer ' +
                                        response.data.token,

                                    'X-Device-ID':
                                        getOrCreateDeviceId()
                                }
                            }
                        );

                    const otpData =
                        await otpResponse.json();

                    if (!otpResponse.ok) {

                        showAlert(
                            'error',
                            'OTP Error',
                            otpData.message ||
                            'Unable to send OTP'
                        );

                        return;
                    }

                    showOtpSection(
                        email
                    );

                    showAlert(
                        'warning',
                        'Verify Device',
                        'OTP sent to your email'
                    );

                    return;
                }

                showAlert(
                    'success',
                    'Login Successful',
                    'Welcome back'
                );

                setTimeout(() => {

                    window.location.href =
                        '/';

                }, 1200);

            } catch (error) {

                console.error(error);

                showAlert(
                    'error',
                    'Server Error',
                    'Something went wrong'
                );
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    registerForm?.addEventListener(
        'submit',
        async function(e) {

            e.preventDefault();

            const name =
                registerForm
                .querySelector(
                    'input[name="name"]'
                )
                .value
                .trim();

            const email =
                registerForm
                .querySelector(
                    'input[name="email"]'
                )
                .value
                .trim();

            const password =
                registerForm
                .querySelector(
                    'input[name="password"]'
                )
                .value;

            const confirmPassword =
                registerForm
                .querySelector(
                    'input[name="password_confirmation"]'
                )
                .value;

            if (
                password !==
                confirmPassword
            ) {

                showAlert(
                    'error',
                    'Password Mismatch',
                    'Passwords do not match'
                );

                return;
            }

            try {

               const response =
    await apiRequest(
        '/auth/register',
        'POST',
        {
            name,
            email,
            password,
            password_confirmation:
                confirmPassword,
            device_id:
                getOrCreateDeviceId()
        }
    );

                if (!response.ok) {

                    showAlert(
                        'error',
                        'Registration Failed',
                        response.data.message ||
                        'Unable to register'
                    );

                    return;
                }

                showOtpSection(
                    email
                );

                showAlert(
                    'success',
                    'OTP Sent',
                    'Verification code sent to your email'
                );

            } catch (error) {

                console.error(error);

                showAlert(
                    'error',
                    'Server Error',
                    'Unable to register'
                );
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */

    document
    .getElementById(
        'verifyOtpBtn'
    )
    ?.addEventListener(
        'click',
        async function() {

            const otp =
                getOtpValue();

            try {

             let response =
    await apiRequest(
        '/verify-otp',
        'POST',
        {
            email:
                tempEmail,
            otp,
            device_id:
                getOrCreateDeviceId()
        }
    );

                if (
                    response.ok
                ) {

                    showAlert(
                        'success',
                        'Email Verified',
                        'Please login now'
                    );

                    setTimeout(() => {

                        location.reload();

                    }, 1200);

                    return;
                }

                response =
                    await apiRequest(
                        '/verify-device-otp',
                        'POST',
                        {
                            otp
                        }
                    );

                if (
                    !response.ok
                ) {

                    showAlert(
                        'error',
                        'Verification Failed',
                        response.data.message ||
                        'Invalid OTP'
                    );

                    return;
                }

                showAlert(
                    'success',
                    'Device Verified',
                    'Your device is trusted now'
                );

                setTimeout(() => {

                    window.location.href =
                        '/';

                }, 1200);

            } catch (error) {

                console.error(error);

                showAlert(
                    'error',
                    'Verification Error',
                    'Unable to verify OTP'
                );
            }
        }
    );

});
</script>

</body>
</html>