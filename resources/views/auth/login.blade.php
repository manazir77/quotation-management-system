@extends('layouts.auth')

@section('title', 'Login - Quotation Management System')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="logo">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <h1>QuotePro</h1>
        <p>Quotation Management System</p>
    </div>
    <div class="auth-body">
        <h4 class="mb-4 text-center">Welcome Back!</h4>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="name@example.com" required autofocus>
                <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="auth-footer">
            <span class="text-muted">Don't have an account?</span>
            <a href="{{ route('register') }}">Create Account</a>
        </div>
    </div>
</div>
@endsection

