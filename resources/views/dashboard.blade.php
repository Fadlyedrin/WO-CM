@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome back, {{ Auth::user()->name }}!</h4>
                    <p>You are logged in.</p>
                    
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-primary">Browse Packages</a>
                    <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary">View My Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
