<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Guest\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'guest.about')->name('about');
Route::view('/contact', 'guest.contact')->name('contact');

// Dashboard chung
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('host')) {
            return redirect()->route('host.dashboard');
        }

        if ($user->hasRole('guest')) {
            return redirect()->route('guest.home');
        }

        return view('home');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:guest'])->get('/guest/profile', fn() => redirect()->route('profile.edit'));
Route::middleware(['auth', 'role:host'])->get('/host/profile', fn() => redirect()->route('profile.edit'));
Route::middleware(['auth', 'role:admin'])->get('/admin/profile', fn() => redirect()->route('profile.edit'));

Route::get('/search', [HomeController::class, 'search'])->name('search.index');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/test-partials', function () {
    return view('test-partials');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/host.php';
require __DIR__ . '/guest.php';