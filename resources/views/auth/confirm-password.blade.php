@extends('backend.layouts.app')

@section('content')
<div class="card-body">
    <p class="login-box-msg">
        {{ __('Please confirm your password before continuing.') }}
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

    {{-- Confirm Password Form --}}
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Password Field --}}
        <div class="input-group mb-3">
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password"
                   required autocomplete="current-password">
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

        {{-- Submit --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Confirm Password') }}
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
