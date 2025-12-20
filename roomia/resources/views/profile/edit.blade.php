@php
    // Chọn layout theo role để trang profile dùng chung nhưng vẫn đúng navbar/giao diện
    $layout =
        auth()->check() && auth()->user()->hasRole('admin')
            ? 'layouts.admin'
            : (auth()->check() && auth()->user()->hasRole('host')
                ? 'layouts.host'
                : 'layouts.guest');
@endphp

@extends($layout)
@section('title', 'Hồ Sơ Cá Nhân')
@php
    use Illuminate\Support\Facades\Route;

    // Xác định prefix theo role (Spatie)
    $prefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('host') ? 'host.' : 'guest.');

    // Tạo route name theo prefix
    $editRoute = $prefix . 'profile.edit';
    $updateRoute = $prefix . 'profile.update';
    $destroyRoute = $prefix . 'profile.destroy';

@endphp

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h1 class="h3 mb-1">Tài khoản & Bảo mật</h1>
                <p class="text-muted mb-0">Cập nhật thông tin cá nhân, đổi mật khẩu và quản lý tài khoản.</p>
            </div>
        </div>

        {{-- Flash / status --}}
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Cập nhật hồ sơ thành công.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Đổi mật khẩu thành công.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status') === 'verification-link-sent')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                Đã gửi lại email xác minh.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Global errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Có lỗi xảy ra:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile"
                    type="button" role="tab" aria-controls="pane-profile" aria-selected="true">
                    <i class="fas fa-user me-1"></i> Hồ sơ
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-password" data-bs-toggle="tab" data-bs-target="#pane-password"
                    type="button" role="tab" aria-controls="pane-password" aria-selected="false">
                    <i class="fas fa-key me-1"></i> Mật khẩu
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-danger" id="tab-danger" data-bs-toggle="tab" data-bs-target="#pane-danger"
                    type="button" role="tab" aria-controls="pane-danger" aria-selected="false">
                    <i class="fas fa-exclamation-triangle me-1"></i> Nguy hiểm
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ================= PROFILE ================= --}}
            <div class="tab-pane fade show active" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Cập nhật thông tin</strong>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route($updateRoute) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required autocomplete="name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                            </div>

                            {{-- Email verification (Breeze) --}}
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">Email chưa xác minh</div>
                                        <div class="small">Vui lòng xác minh email để sử dụng đầy đủ tính năng.</div>
                                    </div>

                                    <button type="submit" form="send-verification" class="btn btn-outline-dark btn-sm">
                                        Gửi lại
                                    </button>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </form>

                        {{-- form gửi lại verification link (Breeze expects this route) --}}
                        @if (Route::has('verification.send'))
                            <form id="send-verification" method="POST" action="{{ route('verification.send') }}"
                                class="d-none">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= PASSWORD ================= --}}
            <div class="tab-pane fade" id="pane-password" role="tabpanel" aria-labelledby="tab-password">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Đổi mật khẩu</strong>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control"
                                    autocomplete="current-password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control" autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nhập lại mật khẩu mới</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    autocomplete="new-password">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-1"></i> Cập nhật mật khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ================= DANGER ZONE ================= --}}
            <div class="tab-pane fade" id="pane-danger" role="tabpanel" aria-labelledby="tab-danger">
                <div class="card border-danger shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <strong>Xóa tài khoản</strong>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Hành động này không thể hoàn tác. Vui lòng nhập mật khẩu để xác nhận xóa tài khoản.
                        </p>

                        <form method="POST" action="{{ route($destroyRoute) }}" class="js-confirm"
                            data-confirm="Bạn chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác.">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control"
                                    autocomplete="current-password">
                            </div>

                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Xóa tài khoản
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
