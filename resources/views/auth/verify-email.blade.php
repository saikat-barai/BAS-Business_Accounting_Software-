@extends('backend.layouts.app')

@section('content')
    <div class="card-body">
        <p class="login-box-msg">
            {{ __('Thanks for signing up! Please verify your email address by clicking the link sent to your inbox. If you didn\'t receive it, we can resend it.') }}
        </p>

        {{-- Status Alert --}}
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4">
            {{-- Resend Verification Email --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
@endsection
