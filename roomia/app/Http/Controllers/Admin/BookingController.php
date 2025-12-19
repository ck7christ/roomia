<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $statuses = [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_COMPLETED,
        ];

        $q = Booking::query()
            ->with([
                // Eager load để tránh N+1
                'guest:id,name,email',
                'roomType:id,room_id,name',
                'roomType.room:id,title,host_id',
                'voucher:id,code,name,type,value',
                'latestPayment', // quan hệ trong Booking model
                'review',
            ]);

        // Filter theo status
        if ($request->filled('status') && in_array($request->status, $statuses, true)) {
            $q->where('status', $request->status);
        }

        // Filter theo check_in từ ngày -> đến ngày
        if ($request->filled('from')) {
            $q->whereDate('check_in', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('check_in', '<=', $request->to);
        }

        // Search keyword (guest name/email hoặc room title)
        if ($request->filled('q')) {
            $kw = trim($request->q);

            $q->where(function ($x) use ($kw) {
                $x->whereHas('guest', function ($g) use ($kw) {
                    $g->where('name', 'like', "%{$kw}%")
                        ->orWhere('email', 'like', "%{$kw}%");
                })->orWhereHas('roomType.room', function ($r) use ($kw) {
                    $r->where('title', 'like', "%{$kw}%");
                });
            });
        }

        $bookings = $q->latest()->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'statuses'));
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
        $booking->load([
            'guest',
            'roomType.room',
            'voucher',
            'latestPayment',
            'review',
        ]);

        return view('admin.bookings.show', compact('booking'));
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
        $data = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([
                    Booking::STATUS_PENDING,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CANCELLED,
                    Booking::STATUS_COMPLETED,
                ]),
            ],
        ]);

        $newStatus = $data['status'];

        // Nếu admin hủy
        if ($newStatus === Booking::STATUS_CANCELLED) {
            $booking->status = Booking::STATUS_CANCELLED;
            $booking->cancelled_at = now();
        } else {
            $booking->status = $newStatus;

            // Nếu trước đó bị hủy mà admin đổi lại status khác => xoá cancelled_at
            if ($booking->getOriginal('status') === Booking::STATUS_CANCELLED) {
                $booking->cancelled_at = null;
            }
        }

        $booking->save();

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Cập nhật trạng thái booking thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Đã xoá booking.');
    }
    public function cancel(Booking $booking)
    {
        $booking->status = Booking::STATUS_CANCELLED;
        $booking->cancelled_at = now();
        $booking->save();

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Đã huỷ booking.');
    }
}
