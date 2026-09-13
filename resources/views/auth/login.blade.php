@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 3rem;">
    <div class="card elegant-card overflow-hidden" style="max-width: 900px; width: 100%;">
        <div class="row g-0">
            <!-- Left Side Image -->
            <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover no-repeat; min-height: 500px;">
            </div>
            <!-- Right Side Form -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="font-family: var(--font-heading); color: var(--text-main);">Welcome Back</h2>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="position-relative">
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                                <i class="bi bi-eye-slash position-absolute" onclick="togglePassword('password', this)" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                            </div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input rounded-0" name="remember">
                                <label for="remember_me" class="form-check-label small text-muted">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-muted text-decoration-none">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-elegant w-100 py-3">Sign In</button>
                    </form>
                    
                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="text-muted small">Don't have an account?</span> 
                        <a href="{{ route('register') }}" class="small fw-bold text-decoration-none" style="color: var(--text-main);">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endpush