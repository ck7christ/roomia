@extends('layouts.base')

@section('title', 'Forgot Password')

@section('layout')

    <div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
        <div class="auth-slider-card p-4 p-md-5" style="max-width: 480px; width: 100%;">

            {{-- Title --}}
            <div class="mb-4 text-center">
                <h3 class="fw-bold mb-1">Forgot Your Password?</h3>
                <p class="text-muted small mb-0">
                    Enter your email and we’ll send you a reset link.
                </p>
            </div>

            {{-- Status message --}}
            @if (session('status'))
                <div class="alert alert-success small" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label small text-muted" for="email">Email</label>

                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            <i class="fa-solid fa-envelope"></i>
                        </span>

                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required autofocus>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn auth-primary-btn px-5">
                        SEND
                    </button>
                </div>

                {{-- Back to login --}}
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="small text-decoration-none">
                        Back to Sign In
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection
