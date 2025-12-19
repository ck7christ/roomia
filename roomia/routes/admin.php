<?php
use App\Http\Controllers\Admin\AmenityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\HostController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\VoucherController;
/*
| Admin Routes
| Middleware:
| - auth: yêu cầu đăng nhập
| - role:admin: yêu cầu user có role admin (Spatie)
| Prefix: URL bắt đầu bằng /admin/...
| Name prefix: route name bắt đầu bằng admin.* (vd: admin.users.index)
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard: /admin (route name: admin.dashboard)
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        /*
            Profile (Admin)
            Tách theo role để mỗi role có layout/đường dẫn riêng.
         */
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
        /*
            CRUD Amenities
            Route::resource tạo đầy đủ 7 route chuẩn REST: index, create, store, show, edit, update, destroy
        */
        Route::resource('amenities', AmenityController::class)
            ->names('amenities');
        // Reviews (Admin chỉ cần xem & xóa)
        Route::get('reviews', [ReviewController::class, 'index'])
            ->name('reviews.index');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])
            ->name('reviews.destroy');
        /* 
            CRUD quản trị
            Các module admin quản lý toàn hệ thống.
         */
        Route::resource('users', UserController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('hosts', HostController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('vouchers', VoucherController::class);
        // Reports (ví dụ trang thống kê tổng hợp)
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });