@extends('layouts.app')
@section('content')
<div class="bg-light py-5">
    <div class="container py-4">
        <h2 class="section-title mb-4">My Profile</h2>
        
        <div class="row g-4">
            <div class="col-md-6">
                <!-- Update Profile Info -->
                <div class="card elegant-card border-0 h-100 p-4">
                    <h4 class="mb-4" style="font-family: 'Playfair Display', serif;">Profile Information</h4>
                    <p class="text-muted small mb-4">Update your account's profile information and email address.</p>
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="mb-3">
                            <label class="form-label small text-uppercase">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-uppercase">Phone Number (WhatsApp)</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required autocomplete="tel">
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-elegant">Save Changes</button>
                        
                        @if (session('status') === 'profile-updated')
                            <p class="text-success small mt-2"><i class="bi bi-check-circle"></i> Saved successfully.</p>
                        @endif
                    </form>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Update Password -->
                <div class="card elegant-card border-0 h-100 p-4">
                    <h4 class="mb-4" style="font-family: 'Playfair Display', serif;">Update Password</h4>
                    <p class="text-muted small mb-4">Ensure your account is using a long, random password to stay secure.</p>
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="mb-3">
                            <label class="form-label small text-uppercase">Current Password</label>
                            <div class="position-relative">
                                <input id="current_password" type="password" name="current_password" class="form-control" required autocomplete="current-password">
                                <i class="bi bi-eye-slash" style="cursor:pointer; position:absolute; right:15px; top:50%; transform:translateY(-50%); color:#888;" onclick="togglePwd('current_password', this)"></i>
                            </div>
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small text-uppercase">New Password</label>
                            <div class="position-relative">
                                <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                                <i class="bi bi-eye-slash" style="cursor:pointer; position:absolute; right:15px; top:50%; transform:translateY(-50%); color:#888;" onclick="togglePwd('password', this)"></i>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Confirm Password</label>
                            <div class="position-relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                <i class="bi bi-eye-slash" style="cursor:pointer; position:absolute; right:15px; top:50%; transform:translateY(-50%); color:#888;" onclick="togglePwd('password_confirmation', this)"></i>
                            </div>
                            @error('password_confirmation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-elegant">Update Password</button>
                        
                        @if (session('status') === 'password-updated')
                            <p class="text-success small mt-2"><i class="bi bi-check-circle"></i> Password updated.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePwd(inputId, icon) {
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
@endsection