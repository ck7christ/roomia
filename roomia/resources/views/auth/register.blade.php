{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.base')

@section('title', 'Đăng ký - ' . config('app.name'))

@section('layout')
    <div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="auth-card bg-white">
                        <div class="row g-0">
                            {{-- Cột trái: Welcome Back --}}
                            <div class="col-12 col-md-5 auth-card-left d-flex flex-column justify-content-center">
                                <div>
                                    <h2 class="fw-semibold mb-3">Welcome Back!</h2>
                                    <p class="mb-4 small">
                                        Provide your personal details to use all features
                                    </p>

                                    <a href="{{ route('login') }}" class="btn btn-outline-light px-4">
                                        SIGN IN
                                    </a>
                                </div>
                            </div>

                            {{-- Cột phải: Register form --}}
                            <div class="col-12 col-md-7">
                                <div class="auth-card-right p-4 p-md-5">
                                    {{-- Social --}}
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                                        <h3 class="fw-bold mb-3 mb-md-0">Register With</h3>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                                <i class="fab fa-google"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                                <i class="fab fa-facebook-f"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                                <i class="fab fa-github"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm auth-social-btn">
                                                <i class="fab fa-linkedin-in"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="text-center mb-3">
                                        <span class="fw-bold">OR</span>
                                    </div>

                                    <p class="text-center text-muted small mb-4">
                                        Fill out the following info for registration
                                    </p>

                                    {{-- Form --}}
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        {{-- Name --}}
                                        <div class="mb-3">
                                            <label for="name" class="form-label small text-muted">Name</label>
                                            <input id="name" type="text"
                                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" required autofocus>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="form-label small text-muted">Email</label>
                                            <input id="email" type="email"
                                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Password --}}
                                        <div class="mb-4">
                                            <label for="password" class="form-label small text-muted">Password</label>
                                            <input id="password" type="password"
                                                class="form-control form-control-sm @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Confirm password (ẩn trên hình nhưng nên có) --}}
                                        <div class="mb-4">
                                            <label for="password_confirmation" class="form-label small text-muted">
                                                Confirm Password
                                            </label>
                                            <input id="password_confirmation" type="password"
                                                class="form-control form-control-sm" name="password_confirmation" required
                                                autocomplete="new-password">
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn auth-primary-btn">
                                                SIGN UP
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