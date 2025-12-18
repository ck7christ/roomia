<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hostId = Auth::id();

        $bookings = Booking::with(['roomType.room', 'guest', 'payment'])
            ->whereHas('roomType.room', function ($q) use ($hostId) {
                $q->where('host_id', $hostId);
            })
            ->latest()
            ->paginate(20);

        return view('host.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        $booking->load(['roomType.room', 'guest', 'payment']);

        return view('host.bookings.show', compact('booking'));
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
    public function update(Request $request, Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        // Chỉ cho phép host chỉnh 3 trạng thái này
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_COMPLETED,
            ]),
        ]);

        // Nếu booking đã bị hủy thì không cho sửa nữa
        if ($booking->status === Booking::STATUS_CANCELLED) {
            return back()->with('error', 'Booking này đã bị hủy, không thể cập nhật trạng thái.');
        }

        $booking->status = $request->input('status');
        $booking->save();

        return redirect()
            ->route('host.bookings.show', $booking)
            ->with('success', 'Cập nhật trạng thái booking thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    protected function authorizeBooking(Booking $booking): void
    {
        $hostId = Auth::id();

        if (
            !$booking->roomType ||
            !$booking->roomType->room ||
            $booking->roomType->room->host_id !== $hostId
        ) {
            abort(403);
        }
    }
}
