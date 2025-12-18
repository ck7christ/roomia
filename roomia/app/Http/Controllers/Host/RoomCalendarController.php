<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\RoomCalendar;
use Illuminate\Http\Request;
use App\Models\RoomType;
use Illuminate\Support\Facades\Auth;

class RoomCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RoomType $roomType)
    {
        //
        $this->authorizeRoomType($roomType);

        $roomType->load('room');

        $calendars = $roomType->calendars()
            ->orderBy('date')
            ->get();

        return view('host.roomTypes.calendars.index', [
            'roomType' => $roomType,
            'room' => $roomType->room,
            'calendars' => $calendars,
        ]);
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
    public function store(Request $request, RoomType $roomType)
    {
        //
        $this->authorizeRoomType($roomType);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'price_per_night' => ['nullable', 'integer', 'min:0'],
            'available_units' => ['nullable', 'integer', 'min:0'],
            'is_closed' => ['nullable', 'boolean'],
        ]);

        RoomCalendar::updateOrCreate(
            [
                'room_type_id' => $roomType->id,
                'date' => $data['date'],
            ],
            [
                'price_per_night' => $data['price_per_night'] ?? null,
                'available_units' => $data['available_units'] ?? null,
                'is_closed' => $request->boolean('is_closed'),
            ]
        );

        return back()->with('success', 'Cập nhật lịch phòng thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomCalendar $roomCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomCalendar $roomCalendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomCalendar $roomCalendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType, RoomCalendar $calendar)
    {
        //
        $this->authorizeRoomType($calendar->roomType);

        $calendar->delete();

        return back()->with('success', 'Đã xóa cấu hình ngày, dùng lại giá & tồn kho mặc định.');
    }

    private function authorizeRoomType(RoomType $roomType)
    {
        if ($roomType->room->host_id !== Auth::id()) {
            abort(403, 'Không có quyền truy cập.');
        }
    }
}
