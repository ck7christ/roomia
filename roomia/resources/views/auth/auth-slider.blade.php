@extends('layouts.base')

@section('title', 'Đăng nhập / Đăng ký ')
@section('layout')

    @php
        $openSignup = $errors->has('name') || $errors->has('password_confirmation');
    @endphp

    <div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
        <div class="auth-slider-card {{ $openSignup ? 'auth-mode-signup' : '' }}" id="authSliderCard">

            <div class="auth-slider-strip">

                {{-- =====================================================
                VIEW 1 : SIGN IN
                ===================================================== --}}
                <div class="auth-view">
                    <div class="row g-0 w-100">

                        {{-- PANEL TRÁI --}}
                        <div
                            class="col-12 col-md-6 auth-side-panel d-flex flex-column justify-content-center align-items-start">
                            <h2 class="fw-semibold mb-3">New here?</h2>
                            <p class="mb-4 small">Enter your details and start your journey with us.</p>

                            <button type="button" class="btn btn-outline-light px-5" data-auth-mode="signup">
                                SIGN UP
                            </button>
                        </div>

                        {{-- FORM LOGIN --}}
                        <div class="col-12 col-md-6">
                            <div class="auth-form-panel p-4 p-md-5 h-100 d-flex flex-column justify-content-center">

                                <h3 class="fw-bold mb-1">Sign In</h3>
                                <p class="small text-muted mb-4">Use your email and password to continue.</p>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label class="form-label small text-muted" for="email">Email</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror" required
                                                autofocus>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Password --}}
                                    <div class="mb-3">
                                        <label class="form-label small text-muted" for="password">Password</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                            <input id="password" type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" required
                                                autocomplete="current-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Remember + Forgot --}}
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                @checked(old('remember'))>
                                            <label class="form-check-label small" for="remember">
                                                Remember me
                                            </label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="small text-decoration-none">
                                                Forgot password?
                                            </a>
                                        @endif
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn auth-primary-btn px-5">
                                            SIGN IN
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- =====================================================
                VIEW 2 : SIGN UP
                ===================================================== --}}
                <div class="auth-view">
                    <div class="row g-0 w-100">

                        {{-- FORM REGISTER --}}
                        <div class="col-12 col-md-6">
                            <div class="auth-form-panel p-4 p-md-5 h-100 d-flex flex-column justify-content-center">

                                <h3 class="fw-bold mb-1">Create Account</h3>
                                <p class="small text-muted mb-4">Fill out the information below to register.</p>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    {{-- Name --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label small text-muted">Name</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label for="reg_email" class="form-label small text-muted">Email</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input id="reg_email" type="email" name="email" value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Password --}}
                                    <div class="mb-3">
                                        <label for="reg_password" class="form-label small text-muted">Password</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                            <input id="reg_password" type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" required
                                                autocomplete="new-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Confirm --}}
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label small text-muted">
                                            Confirm Password
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>
                                            <input id="password_confirmation" type="password"
                                                name="password_confirmation" class="form-control" required
                                                autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn auth-primary-btn px-5">
                                            SIGN UP
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- PANEL PHẢI --}}
                        <div
                            class="col-12 col-md-6 auth-side-panel d-flex flex-column justify-content-center align-items-start">
                            <h2 class="fw-semibold mb-3">Welcome Back!</h2>
                            <p class="mb-4 small">Already have an account? Sign in to continue.</p>

                            <button type="button" class="btn btn-outline-light px-5" data-auth-mode="signin">
                                SIGN IN
                            </button>
                        </div>

                    </div>
                </div>

            </div> {{-- end slider-strip --}}
        </div> {{-- end card --}}
    </div>

@endsection
