<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Guest\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes (Entry point)
|--------------------------------------------------------------------------
| File này khai báo các route public/chung cho toàn hệ thống.
| Sau đó sẽ require các file route theo role: auth, admin, host, guest.
*/
// Trang chủ (public)
Route::get('/', [HomeController::class, 'index'])->name('home');
// Trang tĩnh (public)
Route::view('/about', 'guest.about')->name('about');
/*
|--------------------------------------------------------------------------
| Dashboard chung sau đăng nhập
|--------------------------------------------------------------------------
| Sau khi user đăng nhập + verify email (nếu bạn bật), điều hướng theo role:
| - admin  -> admin.dashboard
| - host   -> host.dashboard
| - guest  -> guest.home
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Spatie Roles: hasRole() để phân quyền theo vai trò
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('host')) {
            return redirect()->route('host.dashboard');
        }
        if ($user->hasRole('guest')) {
            return redirect()->route('guest.home');
        }
        // Fallback nếu user không có role (hiếm) - có thể đổi sang redirect('home')
        return view('home');
    })->name('dashboard');
});
// Tìm kiếm phòng (public)
Route::get('/search', [HomeController::class, 'search'])->name('search.index');
/*
|--------------------------------------------------------------------------
| Stripe Webhook
|--------------------------------------------------------------------------
| Endpoint để Stripe gọi server của khi thanh toán có sự kiện (payment_intent, invoice,...)
| Lưu ý: thường phải "CSRF except" route này trong VerifyCsrfToken (Laravel) vì Stripe không gửi CSRF token.
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
// Nạp routes auth/role theo module
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/host.php';
require __DIR__ . '/guest.php';