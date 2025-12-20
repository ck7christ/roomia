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
                            <h2 class="fw-semibold mb-3">Bạn chưa có tài khoản?</h2>
                            <p class="mb-4 small">Nhập thông tin của bạn và bắt đầu hành trình cùng Roomia</p>

                            <button type="button" class="btn btn-outline-light px-5" data-auth-mode="signup">
                                ĐĂNG KÝ
                            </button>
                        </div>

                        {{-- FORM LOGIN --}}
                        <div class="col-12 col-md-6">
                            <div class="auth-form-panel p-4 p-md-5 h-100 d-flex flex-column justify-content-center">

                                <h3 class="fw-bold mb-1">ĐĂNG NHẬP</h3>
                                <p class="small text-muted mb-4">Dùng email và mật khẩu để tiếp tục.</p>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label class="form-label small text-muted" for="email">EMAIL</label>
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
                                        <label class="form-label small text-muted" for="password">MẬT KHẨU</label>
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
                                                Ghi nhớ đăng nhập
                                            </label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="small text-decoration-none">
                                                Quên mật khẩu?
                                            </a>
                                        @endif
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn auth-primary-btn px-5">
                                            ĐĂNG NHẬP
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

                                <h3 class="fw-bold mb-1">TẠO TÀI KHOẢN</h3>
                                <p class="small text-muted mb-4">Vui lòng điền thông tin bên dưới để đăng ký.</p>

                                <form method="POST" action="{{ route('register.store') }}">
                                    @csrf

                                    {{-- Name --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label small text-muted">HỌ VÀ TÊN</label>
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
                                        <label for="reg_email" class="form-label small text-muted">EMAIL</label>
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
                                        <label for="reg_password" class="form-label small text-muted">MẬT KHẨU</label>
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
                                            XÁC NHẬN MẬT KHẨU
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
                                            ĐĂNG KÝ
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- PANEL PHẢI --}}
                        <div
                            class="col-12 col-md-6 auth-side-panel d-flex flex-column justify-content-center align-items-start">
                            <h2 class="fw-semibold mb-3">Chào mừng bạn quay lại!</h2>
                            <p class="mb-4 small">Bạn đã có tài khoản? Đăng nhập để tiếp tục.</p>

                            <button type="button" class="btn btn-outline-light px-5" data-auth-mode="signin">
                                ĐĂNG NHẬP
                            </button>
                        </div>

                    </div>
                </div>

            </div> {{-- end slider-strip --}}
        </div> {{-- end card --}}
    </div>

@endsection
