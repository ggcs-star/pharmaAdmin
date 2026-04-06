@extends('layouts.app')

@section('title', 'Login - MediLife')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bg-white rounded-4 shadow-sm p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle fa-4x text-teal-600"></i>
                    <h2 class="fw-bold mt-3">Welcome Back!</h2>
                    <p class="text-muted">Login to your account</p>
                </div>
                
                <!-- Display validation errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error!</strong> 
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-teal-600"></i>
                            </span>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your email" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-teal-600"></i>
                            </span>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-0">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-decoration-none text-teal-600 fw-semibold">Register here</a>
                    </p>
                    <a href="#" class="text-decoration-none small text-muted">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Show loading state on form submit
    document.getElementById('loginForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
    });
</script>
@endpush
@endsection