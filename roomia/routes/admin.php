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

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
        Route::resource('amenities', AmenityController::class)
            ->names('amenities');

        Route::get('reviews', [ReviewController::class, 'index'])
            ->name('reviews.index');

        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])
            ->name('reviews.destroy');

        // CRUD quản trị (tạo đủ route name: admin.users.index, ...)
        Route::resource('users', UserController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('hosts', HostController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('vouchers', VoucherController::class);
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
