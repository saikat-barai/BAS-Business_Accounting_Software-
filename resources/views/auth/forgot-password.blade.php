@extends('backend.layouts.app')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Forgot your password?</p>

    {{-- Info Message --}}
    <div class="alert alert-info">
        {{ __('No problem. Just enter your email address below and we will send you a link to reset your password.') }}
    </div>

    {{-- Session Status (e.g., link sent message) --}}
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Password Reset Form --}}
    <form method="POST" action="{{ route('password.email') }}">
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

        {{-- Submit Button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Email Password Reset Link
                </button>
            </div>
        </div>
    </form>

    {{-- Go back to login --}}
    <div class="text-center mt-3">
        <a href="{{ route('login') }}">Back to login</a>
    </div>
</div>
@endsection
