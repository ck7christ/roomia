<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Review;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Booking $booking)
    {
        //
        $user = Auth::user();

        // Booking phải thuộc về guest hiện tại
        if ($booking->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền đánh giá booking này.');
        }

        // Đã có review rồi thì không cho nữa
        if ($booking->review) {
            return redirect()
                ->route('guest.bookings.show', $booking->id)
                ->with('error', 'Booking này đã được đánh giá rồi.');
        }

        // Chỉ cho review khi đã trả phòng
        // Giả sử check_out được cast sang Carbon trong model Booking
        if (!$booking->check_out || $booking->check_out->isFuture()) {
            return redirect()
                ->route('guest.bookings.show', $booking->id)
                ->with('error', 'Bạn chỉ có thể đánh giá sau khi đã trả phòng.');
        }

        // Để hiển thị thông tin phòng trên form
        $booking->load('roomType.room');

        return view('guest.reviews.create', [
            'booking' => $booking,
            'roomType' => $booking->roomType,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        //
        $user = Auth::user();

        if ($booking->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền đánh giá booking này.');
        }

        if ($booking->review) {
            return redirect()
                ->route('guest.bookings.show', $booking->id)
                ->with('error', 'Booking này đã được đánh giá rồi.');
        }

        if (!$booking->check_out || $booking->check_out->isFuture()) {
            return redirect()
                ->route('guest.bookings.show', $booking->id)
                ->with('error', 'Bạn chỉ có thể đánh giá sau khi đã trả phòng.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('guest.bookings.show', $booking->id)
            ->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
