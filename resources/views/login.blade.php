{{-- resources/views/auth/saas-auth.blade.php --}}
@extends('layouts.app')

@section('title', 'MediLife - Authentication')

@section('content')
<style>
    :root {
        --gradient-start: #0f766e;
        --gradient-end: #14b8a6;
        --card-bg: rgba(255, 255, 255, 0.92);
        --input-bg: rgba(255, 255, 255, 0.9);
        --shadow-sm: 0 25px 45px -12px rgba(0, 0, 0, 0.15);
        --shadow-md: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
        --border-radius: 28px;
    }

    body {
        background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 50%, #80cbc4 100%);
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, sans-serif;
        position: relative;
        overflow-x: hidden;
    }

    /* Subtle background pattern */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    /* Glassmorphism card */
    .auth-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        padding: 2rem 2rem 2.5rem;
        width: 100%;
        max-width: 480px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .auth-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* Header icon illustration */
    .auth-icon {
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        width: 70px;
        height: 70px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 12px 20px -10px rgba(15, 118, 110, 0.4);
    }

    .auth-icon i {
        font-size: 2.5rem;
        color: white;
    }

    /* Modern Tabs */
    .auth-tabs {
        display: flex;
        gap: 0.5rem;
        background: rgba(0, 0, 0, 0.04);
        padding: 0.5rem;
        border-radius: 60px;
        margin-bottom: 2rem;
    }

    .auth-tab {
        flex: 1;
        text-align: center;
        padding: 0.7rem 1rem;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        background: transparent;
        color: #4b5563;
        border: none;
        font-size: 0.95rem;
    }

    .auth-tab.active {
        background: white;
        color: #0f766e;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Modern Inputs with floating label simulation */
    .input-group-icon {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .input-group-icon i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        transition: all 0.2s;
        z-index: 2;
        pointer-events: none;
    }

    .modern-input {
        width: 100%;
        padding: 0.9rem 1rem 0.9rem 2.8rem;
        font-size: 0.95rem;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: var(--input-bg);
        transition: all 0.2s ease;
        outline: none;
        font-weight: 500;
    }

    .modern-input:focus {
        border-color: #14b8a6;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.2);
        background: white;
    }

    .modern-input-floating {
        width: 100%;
        padding: 1.1rem 1rem 0.4rem 2.8rem;
        font-size: 0.95rem;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: var(--input-bg);
        transition: all 0.2s;
        outline: none;
    }

    .modern-input-floating:focus {
        border-color: #14b8a6;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.2);
    }

    .floating-label {
        position: absolute;
        left: 2.8rem;
        top: 0.95rem;
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
        transition: 0.15s ease-out;
        background: transparent;
    }

    .input-group-icon.filled .floating-label {
        top: 0.3rem;
        font-size: 0.7rem;
        color: #0f766e;
    }

    /* Password toggle */
  .password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0,0,0,0.03);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.password-toggle:hover {
    background: rgba(20, 184, 166, 0.15);
    color: #0f766e;
}

.password-toggle i {
    font-size: 14px;
}

    /* OTP boxes */
    .otp-container {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin: 1.5rem 0;
    }

    .otp-box {
        width: 52px;
        height: 60px;
        text-align: center;
        font-size: 1.6rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        border-radius: 18px;
        background: white;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .otp-box:focus {
        border-color: #14b8a6;
        box-shadow: 0 0 0 3px rgba(20,184,166,0.25);
        outline: none;
    }

    /* Gradient button */
    .btn-gradient {
        background: linear-gradient(105deg, var(--gradient-start), var(--gradient-end));
        border: none;
        padding: 0.85rem 1.5rem;
        border-radius: 40px;
        font-weight: 600;
        font-size: 0.95rem;
        color: white;
        transition: all 0.25s ease;
        width: 100%;
        box-shadow: 0 6px 14px -6px rgba(15, 118, 110, 0.4);
    }

    .btn-gradient:hover:not(:disabled) {
        transform: scale(1.02);
        box-shadow: 0 10px 20px -8px rgba(15, 118, 110, 0.6);
        filter: brightness(1.02);
    }

    .btn-gradient:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-outline-google {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 40px;
        padding: 0.7rem;
        font-weight: 500;
        color: #334155;
        transition: all 0.2s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-outline-google:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Animations */
    .form-panel {
        transition: opacity 0.25s ease, transform 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        opacity: 1;
        transform: translateY(0);
    }

    .form-panel.fade-out {
        opacity: 0;
        transform: translateY(8px);
        pointer-events: none;
    }

    /* Spinner */
    .spinner-border-sm {
        width: 1.2rem;
        height: 1.2rem;
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0 1rem;
        color: #94a3b8;
        font-size: 0.8rem;
    }

    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .divider::before {
        margin-right: 1rem;
    }

    .divider::after {
        margin-left: 1rem;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        .auth-card {
            padding: 1.5rem;
        }
        .otp-box {
            width: 44px;
            height: 52px;
            font-size: 1.3rem;
        }
        .otp-container {
            gap: 8px;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Header with illustration -->
        <div class="text-center">
            <div class="auth-icon">
<i class="fas fa-tablets"></i>            </div>
            <h2 class="fw-bold mb-1" id="formTitle" style="color: #0f172a;">Welcome Back</h2>
            <p class="text-muted small" id="formSub">Sign in to continue to MediLife</p>
        </div>

        <!-- Modern Tab Switch -->
        <div class="auth-tabs mt-3">
            <button class="auth-tab active" data-tab="login">Login</button>
            <button class="auth-tab" data-tab="register">Register</button>
        </div>

        <!-- Alert container for dynamic messages -->
        <div id="alertContainer"></div>

        <!-- ================= LOGIN FORM ================= -->
        <div id="loginPanel" class="form-panel">
            <form method="POST" action="{{ route('login.submit') }}" id="loginFormElem">
                @csrf
                <div class="input-group-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="modern-input" placeholder="Email address" required autocomplete="email">
                </div>
                <div class="input-group-icon" style="position: relative;">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="loginPassword" class="modern-input" placeholder="Password" required>
                    <button type="button" class="password-toggle toggle-password" data-target="loginPassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="text-end mb-3">
                    <a href="#" id="forgotLink" class="small text-decoration-none" style="color:#0f766e;">Forgot password?</a>
                </div>
                <button type="submit" class="btn-gradient" id="loginBtn">
                    <span class="btn-text">Sign in</span>
                    <span class="btn-spinner d-none"><i class="fas fa-spinner fa-spin"></i> </span>
                </button>
                <div class="divider">or continue with</div>
                <button type="button" class="btn-outline-google" id="googleMockBtn">
                    <i class="fab fa-google"></i> Google
                </button>
            </form>
        </div>

        <!-- ================= REGISTER FORM ================= -->
        <div id="registerPanel" class="form-panel" style="display: none;">
            <form method="POST" action="{{ route('register.submit') }}" id="registerFormElem">
                @csrf
                <div class="input-group-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="modern-input" placeholder="Full name" required>
                </div>
                <div class="input-group-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="modern-input" placeholder="Email address" required>
                </div>
                <div class="input-group-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="regPassword" class="modern-input" placeholder="Password" required>
                    <button type="button" class="password-toggle toggle-password" data-target="regPassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="input-group-icon">
                    <i class="fas fa-check-circle"></i>
                    <input type="password" name="password_confirmation" class="modern-input" placeholder="Confirm password" required>
                </div>
                <button type="submit" class="btn-gradient" id="registerBtn">
                    <span class="btn-text">Create account</span>
                    <span class="btn-spinner d-none"><i class="fas fa-spinner fa-spin"></i> </span>
                </button>
                <div class="divider">or continue with</div>
                <button type="button" class="btn-outline-google" id="googleMockRegBtn">
                    <i class="fab fa-google"></i> Google
                </button>
            </form>
        </div>

        <!-- ================= OTP FORM (hidden by default, dynamic) ================= -->
        <div id="otpPanel" class="form-panel" style="display: none;">
            <form method="POST" action="{{ route('verify.otp') }}" id="otpFormElem">
                @csrf
                <input type="hidden" name="email" id="otpEmail" value="">
                <div class="text-center mb-2">
                    <p class="small text-muted">We've sent a code to <span id="otpEmailDisplay"></span></p>
                </div>
                <div class="otp-container" id="otpInputs">
                    <input type="text" maxlength="1" class="otp-box" data-idx="0" autofocus>
                    <input type="text" maxlength="1" class="otp-box" data-idx="1">
                    <input type="text" maxlength="1" class="otp-box" data-idx="2">
                    <input type="text" maxlength="1" class="otp-box" data-idx="3">
                    <input type="text" maxlength="1" class="otp-box" data-idx="4">
                    <input type="text" maxlength="1" class="otp-box" data-idx="5">
                </div>
                <input type="hidden" name="otp" id="otpCombined">
                <button type="submit" class="btn-gradient" id="verifyOtpBtn">
                    <span class="btn-text">Verify OTP</span>
                    <span class="btn-spinner d-none"><i class="fas fa-spinner fa-spin"></i> </span>
                </button>
                <div class="text-center mt-3">
                    <button type="button" id="resendOtpBtn" class="btn btn-link small text-decoration-none">Resend OTP</button>
                    <span class="mx-1">|</span>
                    <a href="#" id="backToLoginFromOtp" class="small text-decoration-none">Back to Login</a>
                </div>
            </form>
            <form method="POST" action="{{ route('resend.otp') }}" id="resendFormElem" style="display: none;">
                @csrf
                <input type="hidden" name="email" id="resendEmail">
            </form>
        </div>

        <!-- ================= FORGOT PASSWORD FORM ================= -->
        <div id="forgotPanel" class="form-panel" style="display: none;">
            <form id="forgotFormElem" method="POST" action="{{ route('forgot.password') }}">
                @csrf
                <div class="input-group-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="modern-input" placeholder="Email address" required>
                </div>
                <button type="submit" class="btn-gradient" id="forgotBtn">
                    <span class="btn-text">Send reset link</span>
                    <span class="btn-spinner d-none"><i class="fas fa-spinner fa-spin"></i> </span>
                </button>
                <div class="text-center mt-3">
                    <a href="#" id="backToLoginFromForgot" class="small text-decoration-none">← Back to login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ======================== UI TRANSITIONS ========================
    const panels = {
        login: document.getElementById('loginPanel'),
        register: document.getElementById('registerPanel'),
        otp: document.getElementById('otpPanel'),
        forgot: document.getElementById('forgotPanel')
    };

    const tabs = document.querySelectorAll('.auth-tab');
    const titleEl = document.getElementById('formTitle');
    const subEl = document.getElementById('formSub');

    function setActiveTab(active) {
        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.getAttribute('data-tab') === active);
        });
    }

    function switchToPanel(panelName, skipAnimation = false) {
        // hide all panels with fade-out
        for (let key in panels) {
            if (panels[key] && panels[key].style.display !== 'none') {
                if (!skipAnimation) panels[key].classList.add('fade-out');
                setTimeout(() => {
                    panels[key].style.display = 'none';
                    panels[key].classList.remove('fade-out');
                }, skipAnimation ? 0 : 150);
            } else if (panels[key]) {
                panels[key].style.display = 'none';
            }
        }
        setTimeout(() => {
            if (panels[panelName]) {
                panels[panelName].style.display = 'block';
                panels[panelName].classList.remove('fade-out');
            }
            // update header
            if (panelName === 'login') {
                titleEl.innerText = 'Welcome Back';
                subEl.innerText = 'Sign in to your account';
                setActiveTab('login');
            } else if (panelName === 'register') {
                titleEl.innerText = 'PharmaLabs';
                subEl.innerText = 'Start your journey with us';
                setActiveTab('register');
            } else if (panelName === 'otp') {
                titleEl.innerText = 'Verify OTP';
                subEl.innerText = 'Enter the 6-digit code';
                setActiveTab(null);
            } else if (panelName === 'forgot') {
                titleEl.innerText = 'Reset Password';
                subEl.innerText = 'We will send you a reset link';
                setActiveTab(null);
            }
        }, skipAnimation ? 0 : 150);
    }

    // Tab click handlers
    document.querySelector('[data-tab="login"]').addEventListener('click', () => switchToPanel('login'));
    document.querySelector('[data-tab="register"]').addEventListener('click', () => switchToPanel('register'));
    document.getElementById('forgotLink')?.addEventListener('click', (e) => { e.preventDefault(); switchToPanel('forgot'); });
    document.getElementById('backToLoginFromForgot')?.addEventListener('click', (e) => { e.preventDefault(); switchToPanel('login'); });
    document.getElementById('backToLoginFromOtp')?.addEventListener('click', (e) => { e.preventDefault(); switchToPanel('login'); });

    // Helper for alerts
    function showAlert(message, type = 'danger') {
        const container = document.getElementById('alertContainer');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        container.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Loading state buttons
    function setButtonLoading(btn, isLoading) {
        const textSpan = btn.querySelector('.btn-text');
        const spinnerSpan = btn.querySelector('.btn-spinner');
        if (isLoading) {
            textSpan.classList.add('d-none');
            spinnerSpan.classList.remove('d-none');
            btn.disabled = true;
        } else {
            textSpan.classList.remove('d-none');
            spinnerSpan.classList.add('d-none');
            btn.disabled = false;
        }
    }

    // Handle login form submit with loading
    const loginForm = document.getElementById('loginFormElem');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            setButtonLoading(btn, true);
            // actual submit will continue, but if validation fails re-enable
            setTimeout(() => { setButtonLoading(btn, false); }, 3000);
        });
    }

    const registerForm = document.getElementById('registerFormElem');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            setButtonLoading(btn, true);
        });
    }

    // OTP inputs logic
    function initOtpInputs() {
        const inputs = document.querySelectorAll('.otp-box');
        inputs.forEach((input, idx) => {
            input.removeEventListener('input', otpInputHandler);
            input.removeEventListener('keydown', otpKeydownHandler);
            input.addEventListener('input', otpInputHandler);
            input.addEventListener('keydown', otpKeydownHandler);
        });
    }

    function otpInputHandler(e) {
        const input = e.target;
        let val = input.value;
        if (val.length > 1) input.value = val[0];
        const idx = parseInt(input.getAttribute('data-idx'));
        if (input.value.length === 1 && idx < 5) {
            const next = document.querySelector(`.otp-box[data-idx="${idx+1}"]`);
            if (next) next.focus();
        }
        updateOtpCombined();
    }

    function otpKeydownHandler(e) {
        const input = e.target;
        const idx = parseInt(input.getAttribute('data-idx'));
        if (e.key === 'Backspace' && input.value === '' && idx > 0) {
            const prev = document.querySelector(`.otp-box[data-idx="${idx-1}"]`);
            if (prev) prev.focus();
        }
    }

    function updateOtpCombined() {
        let combined = '';
        document.querySelectorAll('.otp-box').forEach(box => { combined += box.value; });
        document.getElementById('otpCombined').value = combined;
    }

    // Show OTP panel dynamically
    window.showOtpPanel = function(email) {
        document.getElementById('otpEmail').value = email;
        document.getElementById('otpEmailDisplay').innerText = email;
        document.getElementById('resendEmail').value = email;
        switchToPanel('otp');
        setTimeout(() => {
            const firstOtp = document.querySelector('.otp-box');
            if (firstOtp) firstOtp.focus();
            initOtpInputs();
        }, 200);
    }

    // Resend OTP handler
    const resendBtn = document.getElementById('resendOtpBtn');
    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            const email = document.getElementById('otpEmail').value;
            if (!email) return;
            const form = document.getElementById('resendFormElem');
            if (form) {
                setButtonLoading(document.getElementById('verifyOtpBtn'), true);
                fetch(form.action, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    body: JSON.stringify({ email: email })
                }).then(() => {
                    showAlert('OTP resent successfully!', 'success');
                    setButtonLoading(document.getElementById('verifyOtpBtn'), false);
                }).catch(() => {
                    showAlert('Failed to resend OTP', 'danger');
                    setButtonLoading(document.getElementById('verifyOtpBtn'), false);
                });
            }
        });
    }

    // OTP verification loading
    const otpFormElem = document.getElementById('otpFormElem');
    if (otpFormElem) {
        otpFormElem.addEventListener('submit', function(e) {
            const btn = document.getElementById('verifyOtpBtn');
            setButtonLoading(btn, true);
            updateOtpCombined();
        });
    }

    // Password toggles
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);

        if (input) {
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="far fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="far fa-eye"></i>';
            }
        }
    });
});

    // Google Mock
    document.getElementById('googleMockBtn')?.addEventListener('click', () => showAlert('Google authentication demo (no backend)', 'info'));
    document.getElementById('googleMockRegBtn')?.addEventListener('click', () => showAlert('Google authentication demo (no backend)', 'info'));

    // If session shows OTP already (from server validation)
    @if(session('show_otp'))
        window.addEventListener('DOMContentLoaded', () => {
            showOtpPanel('{{ session('email') }}');
        });
    @endif

    // Forgot form loading simulation
    const forgotFormElem = document.getElementById('forgotFormElem');
    if (forgotFormElem) {
        forgotFormElem.addEventListener('submit', function(e) {
            const btn = document.getElementById('forgotBtn');
            setButtonLoading(btn, true);
        });
    }

    // Loader auto-reset on pages with errors (fallback)
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error')) {
            setButtonLoading(document.getElementById('loginBtn'), false);
            setButtonLoading(document.getElementById('registerBtn'), false);
        }
    });
</script>
@endsection