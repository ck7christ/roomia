{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.base')

@section('title', 'Đăng nhập - ' . config('app.name'))

@section('layout')
    <div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="auth-card bg-white">
                        <div class="row g-0">
                            {{-- Cột trái: lời mời đăng ký --}}
                            <div class="col-12 col-md-5 auth-card-left d-flex flex-column justify-content-center">
                                <div>
                                    <h2 class="fw-semibold mb-3">Hello, Friend!</h2>
                                    <p class="mb-4 small">
                                        Enter your personal details and start your journey with us
                                    </p>

                                    <a href="{{ route('register') }}" class="btn btn-outline-light px-4">
                                        SIGN UP
                                    </a>
                                </div>
                            </div>

                            {{-- Cột phải: Login form --}}
                            <div class="col-12 col-md-7">
                                <div class="auth-card-right p-4 p-md-5">
                                    <div class="mb-4 text-center text-md-start">
                                        <h3 class="fw-bold mb-1">Sign In to {{ config('app.name') }}</h3>
                                        <p class="small text-muted mb-0">
                                            Use your email and password to continue
                                        </p>
                                    </div>

                                    {{-- Social (tuỳ bạn dùng hay không) --}}
                                    <div class="d-flex justify-content-center justify-content-md-start gap-2 mb-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                            <i class="fab fa-google"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                            <i class="fab fa-facebook-f"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                            <i class="fab fa-github"></i>
                                        </button>
                                    </div>

                                    <div class="text-center mb-3">
                                        <span class="fw-bold small text-muted">OR</span>
                                    </div>

                                    {{-- Form --}}
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="form-label small text-muted">Email</label>
                                            <input id="email" type="email"
                                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required autofocus>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Password --}}
                                        <div class="mb-3">
                                            <label for="password" class="form-label small text-muted">Password</label>
                                            <input id="password" type="password"
                                                class="form-control form-control-sm @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Remember me + Forgot --}}
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="remember">
                                                    Remember me
                                                </label>
                                            </div>

                                            @if (Route::has('password.request'))
                                                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                                    Forgot password?
                                                </a>
                                            @endif
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn auth-primary-btn">
                                                SIGN IN
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div> {{-- /.row --}}
                    </div> {{-- /.auth-card --}}
                </div>
            </div>
        </div>
    </div>
@endsection