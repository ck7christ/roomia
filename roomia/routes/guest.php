<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\RoomController;
use App\Http\Controllers\Guest\BookingController;
use App\Http\Controllers\Guest\PaymentController;
use App\Http\Controllers\Guest\ReviewController;
use App\Http\Controllers\Guest\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guest\ContactController;
use App\Http\Controllers\Guest\VoucherApplyController;
use App\Http\Controllers\Guest\VoucherController;
/*
|--------------------------------------------------------------------------
| Guest - Public routes
|--------------------------------------------------------------------------
| Các route bên dưới KHÔNG bắt buộc đăng nhập:
| - xem rooms (index/show)
| - contact (show/send)
| - apply voucher (thường dùng trước khi thanh toán)
*/
// Contact: GET form + POST gửi (có throttle chống spam)
Route::get('/contact', [ContactController::class, 'show'])
    ->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send')
    ->middleware('throttle:10,1');
// Danh sách Room (khách sạn / listing) public
Route::get('/rooms', [RoomController::class, 'index'])
    ->name('rooms.index');
// Chi tiết 1 phòng (Route Model Binding: {room})
Route::get('/rooms/{room}', [RoomController::class, 'show'])
    ->name('rooms.show');
Route::get('/vouchers', [VoucherController::class, 'index'])
    ->name('vouchers.index');


/*
|--------------------------------------------------------------------------
| Guest - Authenticated routes
|--------------------------------------------------------------------------
| Các route trong group yêu cầu:
| - auth: đã đăng nhập
| - role:guest: đúng vai trò guest
| Prefix 'guest' để phân tách rõ module guest
*/
Route::middleware(['auth', 'role:guest'])
    ->prefix('guest')
    ->name('guest.')
    ->group(function () {
        // Guest dashboard: /guest -> redirect về home public
        Route::get('/', fn() => redirect()->route('home'))
            ->name('home');
        // Profile guest: /guest/profile
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
        // Guest xem danh sách Room (khách sạn / listing)
        Route::get('/rooms', [RoomController::class, 'index'])
            ->name('rooms.index');
        // Chi tiết 1 Room
        Route::get('/rooms/{room}', [RoomController::class, 'show'])
            ->name('rooms.show');
        // Đặt phòng theo RoomType
        Route::post('/room-types/{roomType}/book', [BookingController::class, 'store'])
            ->name('room-types.book');
        // Tạo booking (Đặt phòng) -> route name: guest.bookings.store
        Route::post('/bookings', [BookingController::class, 'store'])
            ->name('bookings.store');
        // Danh sách booking của guest hiện tại
        Route::get('/bookings', [BookingController::class, 'index'])
            ->name('bookings.index');
        // Chi tiết 1 booking
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])
            ->name('bookings.show');
        // Danh sách payment của guest
        Route::get('payments', [PaymentController::class, 'index'])
            ->name('payments.index');
        // Chi tiết 1 payment
        Route::get('payments/{payment}', [PaymentController::class, 'show'])
            ->name('payments.show');
        // Form chọn phương thức thanh toán cho 1 booking
        Route::get('bookings/{booking}/payments/create', [PaymentController::class, 'create'])
            ->name('bookings.payments.create');
        // Submit thanh toán (COD / online)
        Route::post('bookings/{booking}/payments', [PaymentController::class, 'store'])
            ->name('bookings.payments.store');
        // REVIEW (singular: 1 booking = 1 review)
        Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])
            ->name('bookings.review.create');
        Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])
            ->name('bookings.review.store');
        Route::get('/bookings/{booking}/review/edit', [ReviewController::class, 'edit'])
            ->name('bookings.review.edit');
        Route::patch('/bookings/{booking}/review', [ReviewController::class, 'update'])
            ->name('bookings.review.update');
        Route::delete('/bookings/{booking}/review', [ReviewController::class, 'destroy'])
            ->name('bookings.review.destroy');
        /*
         |--------------------------------------------------------------
         | Wishlist
         |--------------------------------------------------------------
         | Guest lưu phòng yêu thích: index/store/destroy
         | destroy dùng {room} để xóa khỏi wishlist theo room id
         */
        Route::get('wishlist', [WishlistController::class, 'index'])
            ->name('wishlist.index');
        Route::post('wishlist', [WishlistController::class, 'store'])
            ->name('wishlist.store');
        Route::delete('wishlist/{room}', [WishlistController::class, 'destroy'])
            ->name('wishlist.destroy');
        // Guest apply/remove (dùng trong booking form)
        Route::post('/vouchers/apply', [VoucherApplyController::class, 'apply'])
            ->name('vouchers.apply');
        Route::post('/vouchers/remove', [VoucherApplyController::class, 'remove'])
            ->name('vouchers.remove');
    });