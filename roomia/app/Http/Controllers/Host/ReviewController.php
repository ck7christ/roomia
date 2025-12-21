<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hostId = auth()->id();

        $reviews = Review::query()
            ->with([
                'booking:id,guest_id,room_type_id,check_in,check_out',
                'booking.guest:id,name,email',
                'booking.roomType:id,room_id,name',
                'booking.roomType.room:id,host_id,title',
            ])
            ->whereHas('booking.roomType.room', function ($q) use ($hostId) {
                $q->where('host_id', $hostId);
            })
            ->latest()
            ->paginate(20);

        return view('host.reviews.index', compact('reviews'));
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
