@extends('backend.layouts.app')

@section('content')
    <div class="card-body">
        <p class="login-box-msg">
            {{ __('Reset your password') }}
        </p>

        {{-- Show validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Password Reset Form --}}
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            {{-- Reset Token --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email Field --}}
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email" value="{{ old('email', $request->email) }}" required autofocus>
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
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="New Password" required autocomplete="new-password">
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

            {{-- Confirm Password Field --}}
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password"
                    required autocomplete="new-password">
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

            {{-- Submit --}}
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </div>
        </form>

        {{-- Back to login --}}
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Back to login</a>
        </div>
    </div>
@endsection
