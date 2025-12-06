@extends('layouts.auth')

@section('title', 'Register - Quotation Management System')

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
        <h4 class="mb-4 text-center">Create Account</h4>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-floating">
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" 
                       placeholder="Your Name" required autofocus>
                <label for="name"><i class="bi bi-person me-2"></i>Full Name</label>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="name@example.com" required>
                <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" 
                       placeholder="Confirm Password" required>
                <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>

        <div class="auth-footer">
            <span class="text-muted">Already have an account?</span>
            <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>
@endsection

