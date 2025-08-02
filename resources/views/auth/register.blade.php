@extends('backend.layouts.app')

@section('content')
    <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="input-group mb-3">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Full name" value="{{ old('name') }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email" value="{{ old('email') }}" required>
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

            {{-- Password --}}
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password" required>
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

            {{-- Confirm Password --}}
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Retype password"
                    required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Terms --}}
            <div class="row mb-3">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="agreeTerms" name="terms" value="agree"
                            {{ old('terms') ? 'checked' : '' }}>
                        <label for="agreeTerms">
                            I agree to the <a href="#">terms</a>
                        </label>
                    </div>
                    @error('terms')
                        <div class="text-danger small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Register Button --}}
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-block btn-primary">
                        Register
                    </button>
                </div>
            </div>
        </form>

        {{-- Link to login --}}
        <div class="social-auth-links text-center mt-3">
            <a href="{{ route('login') }}" class="btn btn-block btn-danger">
                Sign In
            </a>
        </div>
    </div>
@endsection
