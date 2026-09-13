@extends('admin.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">My Profile</h2>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-bold">Profile Information</h5>
                    <p class="text-muted small mb-4">Update your admin account's profile information and email address.</p>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required autofocus>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success small ms-2"><i class="bi bi-check-circle"></i> Saved</span>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-bold">Update Password</h5>
                    <p class="text-muted small mb-4">Ensure your account is using a long, random password to stay secure.
                    </p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Current Password</label>
                            <div class="input-group">
                                <input id="admin_current" type="password" name="current_password" class="form-control"
                                    required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="toggleAdminPwd('admin_current', this)"><i class="bi bi-eye-slash"></i></button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">New Password</label>
                            <div class="input-group">
                                <input id="admin_new" type="password" name="password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="toggleAdminPwd('admin_new', this)"><i class="bi bi-eye-slash"></i></button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase fw-bold">Confirm Password</label>
                            <div class="input-group">
                                <input id="admin_conf" type="password" name="password_confirmation" class="form-control"
                                    required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="toggleAdminPwd('admin_conf', this)"><i class="bi bi-eye-slash"></i></button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Password</button>

                        @if (session('status') === 'password-updated')
                            <span class="text-success small ms-2"><i class="bi bi-check-circle"></i> Updated</span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleAdminPwd(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
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
