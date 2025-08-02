@extends('backend.layouts.app')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Sign in to start your session</p>

    {{-- Session Status (like password reset message) --}}
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email Field --}}
        <div class="input-group mb-3">
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Email"
                   value="{{ old('email') }}"
                   required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password Field --}}
        <div class="input-group mb-3">
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password"
                   required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="row mb-3">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        Remember Me
                    </label>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-block btn-primary">
                    Sign In
                </button>
            </div>
        </div>
    </form>

    {{-- Other Links --}}
    <div class="social-auth-links text-center mt-3">
        <a href="{{ route('register') }}" class="btn btn-block btn-danger">
            Register a new membership
        </a>
    </div>

    <p class="mb-1 text-center mt-2">
        <a href="{{ route('password.request') }}">I forgot my password</a>
    </p>
</div>
@endsection
