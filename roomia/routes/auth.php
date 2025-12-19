<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
| - guest middleware: chỉ cho người CHƯA đăng nhập truy cập
| - auth middleware: chỉ cho người ĐÃ đăng nhập truy cập
*/
Route::middleware('guest')->group(function () {
    /*
     |--------------------------------------------------------------
     | Register
     |--------------------------------------------------------------
     | Redirect GET /register về /login (tức là không cho hiển thị form đăng ký).
     */
    Route::get('/register', function () {
        return redirect()->route('login');
    })->middleware('guest')->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
    /*
     |--------------------------------------------------------------
     | Login
     |--------------------------------------------------------------
     | GET /login: hiển thị form login (bạn dùng view 'auth.auth-slider')
     | POST /login: xử lý đăng nhập (AuthenticatedSessionController@store)
     */
    Route::get('/login', function () {
        return view('auth.auth-slider');
    })->middleware('guest')->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // Quên mật khẩu
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Xác thực email
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    // Xác thực mật khẩu
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    // Đổi mật khẩu trong profile
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    // Đăng xuất
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});