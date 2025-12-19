<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Host\RoomController;
use App\Http\Controllers\Host\RoomCalendarController;
use App\Http\Controllers\Host\RoomTypeController;
use App\Http\Controllers\Host\BookingController;
use App\Http\Controllers\Host\PaymentController;
use App\Http\Controllers\Host\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Host\HomeController;
/*
    Host Routes
    Host là người đăng listing/khách sạn và quản lý room types, calendar, booking, payment, review.
*/
Route::middleware(['auth', 'role:host'])
    ->prefix('host')
    ->name('host.')
    ->group(function () {
        // Dashboard host: /host (route name: host.dashboard)
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
        // Profile host
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // Host quản lý Room (khách sạn / listing)
        Route::resource('rooms', RoomController::class)
            ->names('rooms');
        // Host quản lý RoomType của từng Room
        Route::resource('rooms.room-types', RoomTypeController::class);
        // Lịch giá & tồn kho theo RoomType: Quản lý giá và tồn kho theo ngày.
        Route::get('room-types/{roomType}/calendars', [RoomCalendarController::class, 'index'])
            ->name('room-types.calendars.index');
        Route::post('room-types/{roomType}/calendars', [RoomCalendarController::class, 'store'])
            ->name('room-types.calendars.store');
        Route::delete('room-types/{roomType}/calendars/{calendar}', [RoomCalendarController::class, 'destroy'])
            ->name('room-types.calendars.destroy');
        // Host quản lý booking thuộc các RoomType của mình
        Route::get('bookings', [BookingController::class, 'index'])
            ->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])
            ->name('bookings.show');
        Route::patch('bookings/{booking}', [BookingController::class, 'update'])
            ->name('bookings.update');
        // Payments cho host
        Route::get('payments', [PaymentController::class, 'index'])
            ->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])
            ->name('payments.show');
        // Xác nhận COD (thu tiền mặt) - hành động nghiệp vụ riêng
        Route::post('payments/{payment}/confirm-cod', [PaymentController::class, 'confirmCod'])
            ->name('payments.confirmCod');
        // Reviews liên quan tới room/booking của host (chỉ xem)
        Route::get('reviews', [ReviewController::class, 'index'])
            ->name('reviews.index');
    });