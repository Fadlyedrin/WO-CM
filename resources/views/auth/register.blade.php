@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 3rem; margin-bottom: 4rem;">
    <div class="card elegant-card overflow-hidden" style="max-width: 900px; width: 100%;">
        <div class="row g-0 flex-row-reverse">
            <!-- Right Side Image -->
            <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1606800052052-a08af7148866?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover no-repeat; min-height: 600px;">
            </div>
            <!-- Left Side Form -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="font-family: var(--font-heading); color: var(--text-main);">Create Account</h2>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number (WhatsApp)</label>
                            <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required autocomplete="tel">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="position-relative">
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                                <i class="bi bi-eye-slash position-absolute" onclick="togglePassword('password', this)" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                            </div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="position-relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                <i class="bi bi-eye-slash position-absolute" onclick="togglePassword('password_confirmation', this)" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-elegant w-100 py-3">Register</button>
                    </form>
                    
                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="text-muted small">Already have an account?</span> 
                        <a href="{{ route('login') }}" class="small fw-bold text-decoration-none" style="color: var(--text-main);">Log In here</a>
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