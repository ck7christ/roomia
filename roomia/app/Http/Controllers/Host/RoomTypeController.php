<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\Auth;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Room $room)
    {
        //
        $this->authorizeRoom($room);

        $room->load('roomTypes');

        return view('host.roomTypes.index', [
            'room' => $room,
            'roomTypes' => $room->roomTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Room $room)
    {
        //
        $this->authorizeRoom($room);

        return view('host.roomTypes.create', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Room $room)
    {
        //
        $this->authorizeRoom($room);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'total_units' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,inactive'],
        ]);

        $data['room_id'] = $room->id;

        RoomType::create($data);

        return redirect()
            ->route('host.rooms.room-types.index', $room)
            ->with('success', 'Tạo loại phòng thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room, RoomType $roomType)
    {
        //
        $this->authorizeRoom($room);

        if ($roomType->room_id !== $room->id) {
            abort(404);
        }

        $roomType->load(['calendars', 'bookings']);

        return view('host.roomTypes.show', [
            'room' => $room,
            'roomType' => $roomType,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room, RoomType $roomType)
    {
        //
        $this->authorizeRoom($room);

        if ($roomType->room_id !== $room->id) {
            abort(404);
        }

        return view('host.roomTypes.edit', compact('room', 'roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room, RoomType $roomType)
    {
        //
        $this->authorizeRoom($room);

        if ($roomType->room_id !== $room->id) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'total_units' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,inactive'],
        ]);

        $roomType->update($data);

        return redirect()
            ->route('host.rooms.room-types.index', $room)
            ->with('success', 'Cập nhật loại phòng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room, RoomType $roomType)
    {
        //
        $this->authorizeRoom($room);

        if ($roomType->room_id !== $room->id) {
            abort(404);
        }

        if ($roomType->bookings()->exists()) {
            return back()->withErrors([
                'room_type' => 'Loại phòng đã có booking, không thể xóa.',
            ]);
        }

        $roomType->delete();

        return redirect()
            ->route('host.rooms.room-types.index', $room)
            ->with('success', 'Đã xóa loại phòng.');
    }

    private function authorizeRoom(Room $room)
    {
        if ($room->host_id !== Auth::id()) {
            abort(403, 'Không có quyền truy cập.');
        }
    }
}
