<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WishlistItem;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        $items = WishlistItem::with('room')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('guest.wishlist.index', compact('items'));
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
        $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        WishlistItem::firstOrCreate(
            [
                'user_id' => $user->id,
                'room_id' => $request->room_id,
            ],
            [
                'note' => $request->note,
            ]
        );

        return back()->with('success', 'Đã thêm phòng vào wishlist.');
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
    public function destroy(Room $room)
    {
        //
        $user = Auth::user();

        WishlistItem::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->delete();

        return back()->with('success', 'Đã xoá phòng khỏi wishlist.');
    }
}
