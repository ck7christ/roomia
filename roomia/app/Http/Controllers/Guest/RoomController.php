<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\City;
use App\Models\Amenity;
use App\Models\Review;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Danh sách phòng cho guest xem

        $query = Room::with([
            'address.city',
            'address.country',
            'coverImage',
            'amenities',
            'roomTypes',
        ])->where('status', 'active');

        if ($request->filled('city_id')) {
            $query->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
            });
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $rooms = $query->latest()->paginate(12)->withQueryString();

        $cities = City::orderBy('name')->get();
        $amenities = Amenity::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('guest.rooms.index', compact('rooms', 'cities', 'amenities'));
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
    public function show(Room $room)
    {
        // Chi tiết 1 phòng
        if ($room->status !== 'active') {
            abort(404);
        }

        $room->load([
            'address.country',
            'address.city',
            'address.district',
            'images',
            'coverImage',
            'amenities',
            'roomTypes',
        ]);

        $ratingStats = Review::whereHas('booking.roomType', function ($q) use ($room) {
            $q->where('room_id', $room->id);
        })
            ->selectRaw('
            COUNT(*) as total,
            AVG(rating) as avg_rating,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as star5,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as star4,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as star3,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as star2,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as star1
        ')
            ->first();

        // Tổng review
        $totalReviews = $ratingStats->total ?? 0;

        // Điểm trung bình theo thang 5 sao, làm tròn 1 chữ số
        $averageRating = $totalReviews > 0
            ? round($ratingStats->avg_rating, 1)
            : null;

        /*
         |--------------------------------------------------------------------------
         | ⭐ LẤY DANH SÁCH REVIEW ĐỂ HIỂN THỊ
         |--------------------------------------------------------------------------
         */
        $reviews = Review::with(['booking.guest', 'booking.roomType'])
            ->whereHas('booking.roomType', function ($q) use ($room) {
                $q->where('room_id', $room->id);
            })
            ->latest()
            ->get();

        return view('guest.rooms.show', [
            'room' => $room,
            'averageRating' => $averageRating,
            'ratingStats' => $ratingStats,
            'totalReviews' => $totalReviews,
            'reviews' => $reviews,
        ]);
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
