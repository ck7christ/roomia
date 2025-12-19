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
        $this->authorizeBooking($booking);

        // eager load review nếu chưa
        $booking->loadMissing('review', 'roomType.room');

        return view('guest.reviews.create', [
            'booking' => $booking,
            'review' => $booking->review,
            'canReview' => $this->canReview($booking),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        if (!$this->canReview($booking)) {
            return back()->with('review_error', 'Bạn chỉ có thể đánh giá sau khi đã trả phòng.');
        }

        if ($booking->review) {
            return redirect()
                ->route('guest.bookings.review.edit', $booking)
                ->with('review_error', 'Booking này đã được đánh giá.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('guest.bookings.show', $booking)
            ->with('review_success', 'Cảm ơn bạn đã đánh giá!');
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
    public function edit(Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        $booking->loadMissing('review', 'roomType.room');

        if (!$booking->review) {
            return redirect()
                ->route('guest.bookings.review.create', $booking)
                ->with('review_error', 'Bạn chưa có đánh giá để chỉnh sửa.');
        }

        return view('guest.reviews.edit', [
            'booking' => $booking,
            'review' => $booking->review,
            'canReview' => $this->canReview($booking),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        $booking->loadMissing('review');

        if (!$booking->review) {
            return redirect()
                ->route('guest.bookings.review.create', $booking)
                ->with('review_error', 'Bạn chưa có đánh giá để cập nhật.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking->review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('guest.bookings.show', $booking)
            ->with('review_success', 'Đã cập nhật đánh giá.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        $booking->loadMissing('review');

        if ($booking->review) {
            $booking->review->delete();
        }

        return redirect()
            ->route('guest.bookings.show', $booking)
            ->with('review_success', 'Đã xóa đánh giá.');
    }
    protected function authorizeBooking(Booking $booking): void
    {
        abort_unless($booking->user_id === auth()->id(), 403);
    }

    protected function canReview(Booking $booking): bool
    {
        if (empty($booking->check_out))
            return false;
        return Carbon::parse($booking->check_out)->endOfDay()->isPast();
    }

}
