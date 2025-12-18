<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Recent searches (demo + lấy từ session nếu bạn muốn lưu)
        $recentSearches = collect(session('recent_searches', []))
            ->filter(fn($x) => is_array($x) && !empty($x['location']))
            ->map(function ($x) {
                $checkIn = $x['check_in'] ?? null;
                $checkOut = $x['check_out'] ?? null;

                $dateLabel = null;
                if ($checkIn && $checkOut) {
                    $dateLabel = $checkIn . ' → ' . $checkOut;
                } elseif ($checkIn) {
                    $dateLabel = $checkIn;
                }

                return [
                    'location' => $x['location'] ?? null,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'adults' => (int) ($x['adults'] ?? 2),
                    'children' => (int) ($x['children'] ?? 0),
                    'rooms' => (int) ($x['rooms'] ?? 1),
                    'signature' => $x['signature'] ?? null,
                    'date_label' => $dateLabel,
                ];
            })
            ->unique(fn($x) => $x['signature'] ?: md5(json_encode($x)))
            ->values()
            ->take(6);
        // Featured rooms (demo DB)
        $featuredRooms = Room::with([
            'images' => function ($q) {
                $q->orderBy('id');
            }
        ])
            ->latest()
            ->take(20)
            ->get();

        // Subquery: tính avg rating + count theo room (vì reviews gắn booking_id)
        $ratingSub = DB::table('rooms')
            ->join('room_types', 'room_types.room_id', '=', 'rooms.id')
            ->join('bookings', 'bookings.room_type_id', '=', 'room_types.id')
            ->join('reviews', 'reviews.booking_id', '=', 'bookings.id')
            ->select(
                'rooms.id as room_id',
                DB::raw('AVG(reviews.rating) as avg_rating'),
                DB::raw('COUNT(reviews.id) as reviews_count')
            )
            ->groupBy('rooms.id');

        //  Collections: ưu tiên phòng rating cao (không trùng featured)
        $collectionRooms = Room::with(['images' => fn($q) => $q->orderBy('id')])
            ->where('rooms.status', 'active')
            ->leftJoinSub($ratingSub, 'rr', fn($join) => $join->on('rooms.id', '=', 'rr.room_id'))
            ->select('rooms.*')
            ->orderByRaw('COALESCE(rr.avg_rating, 0) DESC')
            ->orderByRaw('COALESCE(rr.reviews_count, 0) DESC')
            ->latest('rooms.created_at')
            ->take(8)
            ->get();

        // Explore Vietnam 
        $exploreCitiesRaw = DB::table('cities')
            ->join('room_addresses', 'room_addresses.city_id', '=', 'cities.id')
            ->join('rooms', 'rooms.id', '=', 'room_addresses.room_id')
            ->where('rooms.status', 'active')
            ->select(
                'cities.id',
                'cities.name',
                DB::raw('COUNT(DISTINCT rooms.id) as rooms_count')
            )
            ->groupBy('cities.id', 'cities.name')
            ->orderByDesc('rooms_count')
            ->limit(5)
            ->get();

        $popularDestinations = $exploreCitiesRaw->map(function ($c) {
            $slug = Str::slug($c->name);

            return [
                'id' => $c->id,
                'name' => $c->name,
                'rooms_count' => (int) $c->rooms_count,
                'image' => "assets/images/cities/{$slug}.jpg",
                'url' => route('rooms.index', ['city_id' => $c->id]),
            ];
        });

        // Rating map (nếu có reviews + booking_id)
        $roomRatings = collect();
        $roomIds = $featuredRooms->pluck('id')
            ->merge($collectionRooms->pluck('id'))
            ->unique()
            ->values();

        if ($roomIds->count() && Schema::hasTable('reviews') && Schema::hasTable('bookings') && Schema::hasTable('room_types')) {
            $hasBookingId = Schema::hasColumn('reviews', 'booking_id') && Schema::hasColumn('reviews', 'rating');
            $hasRoomId = Schema::hasColumn('reviews', 'room_id') && Schema::hasColumn('reviews', 'rating');

            if ($hasRoomId) {
                $rows = DB::table('reviews')
                    ->select('room_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as reviews_count'))
                    ->whereIn('room_id', $roomIds)
                    ->groupBy('room_id')
                    ->get();

                $roomRatings = $rows->keyBy('room_id')->map(fn($r) => [
                    'avg' => (float) $r->avg_rating,
                    'count' => (int) $r->reviews_count,
                ]);
            } elseif ($hasBookingId && Schema::hasColumn('bookings', 'room_type_id') && Schema::hasColumn('room_types', 'room_id')) {
                $rows = DB::table('rooms')
                    ->join('room_types', 'room_types.room_id', '=', 'rooms.id')
                    ->join('bookings', 'bookings.room_type_id', '=', 'room_types.id')
                    ->join('reviews', 'reviews.booking_id', '=', 'bookings.id')
                    ->whereIn('rooms.id', $roomIds)
                    ->select('rooms.id as room_id', DB::raw('AVG(reviews.rating) as avg_rating'), DB::raw('COUNT(reviews.id) as reviews_count'))
                    ->groupBy('rooms.id')
                    ->get();

                $roomRatings = $rows->keyBy('room_id')->map(fn($r) => [
                    'avg' => (float) $r->avg_rating,
                    'count' => (int) $r->reviews_count,
                ]);
            }
        }

        // Static demo blocks (như ảnh)
        $whyRoomia = collect([
            ['icon' => 'fa-solid fa-clock', 'title' => 'Đặt ngay bây giờ, thanh toán tại chỗ nghỉ', 'desc' => 'Miễn phí hủy cho hầu hết chỗ ở.'],
            ['icon' => 'fa-solid fa-thumbs-up', 'title' => 'Hơn 300 triệu đánh giá từ khách', 'desc' => 'Tham khảo thông tin đáng tin cậy từ khách hàng.'],
            ['icon' => 'fa-solid fa-earth-asia', 'title' => 'Hơn 20 ngàn chỗ nghỉ toàn cầu', 'desc' => 'Khách sạn, guest house, căn hộ và nhiều lựa chọn.'],
            ['icon' => 'fa-solid fa-headset', 'title' => 'Dịch vụ khách hàng đáng tin cậy', 'desc' => 'Hỗ trợ 24/7, sẵn sàng giúp đỡ bạn.'],
        ]);

        $deals = collect([
            [
                'badge' => 'Ưu đãi Cuối Năm',
                'title' => 'Vui là chính, không cần dài',
                'desc' => 'Tận hưởng thêm chút nắng vàng cuối mùa với giảm giá tới 15%',
                'image' => 'assets/images/banners/deal-1.jpg',
                'cta' => 'TÌM ƯU ĐÃI',
                'href' => '#',
            ],
            [
                'badge' => 'Nhà Nghỉ Dưỡng',
                'title' => 'Nghỉ dưỡng trong ngôi nhà mơ ước',
                'desc' => 'Vô vàn lựa chọn từ nhà gỗ, biệt thự và hơn thế nữa',
                'image' => 'assets/images/banners/deal-2.jpg',
                'cta' => 'ĐẶT NGAY',
                'href' => '#',
            ],
        ]);

        $exploreCities = collect([
            ['name' => 'Vũng Tàu', 'image' => 'assets/images/destinations/vung-tau.jpg', 'url' => route('rooms.index', ['district_id' => 539])],
            ['name' => 'Đà Nẵng', 'image' => 'assets/images/destinations/da-nang.jpg', 'url' => route('rooms.index', ['city_id' => 32])],
            ['name' => 'Hội An', 'image' => 'assets/images/destinations/hoi-an.jpg', 'url' => route('rooms.index', ['district_id' => 364])],
            ['name' => 'Nha Trang', 'image' => 'assets/images/destinations/nha-trang.jpg', 'url' => route('rooms.index', ['district_id' => 412])],
            ['name' => 'Hà Nội', 'image' => 'assets/images/destinations/ha-noi.jpg', 'url' => route('rooms.index', ['city_id' => 1])],
        ]);
        $wishlistRoomIds = auth()->check()
            ? WishlistItem::where('user_id', auth()->id())->pluck('room_id')->all()
            : [];
        return view('guest.home', compact(
            'recentSearches',
            'featuredRooms',
            'collectionRooms',
            'exploreCities',
            'roomRatings',
            'whyRoomia',
            'deals',
            'popularDestinations',
            'wishlistRoomIds'

        ));
    }
    public function search(Request $request)
    {
        $data = $request->validate([
            'location' => ['nullable', 'string', 'max:255'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'rooms' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $this->storeRecentSearchToSession($data);

        $targets = [
            'guest.rooms.index',
            'guest.rooms.search',
            'rooms.index',
            'search.index',
        ];

        foreach ($targets as $name) {
            if (Route::has($name)) {
                return redirect()->route($name, $data);
            }
        }

        return back()->withInput();
    }

    private function storeRecentSearchToSession(array $data, int $limit = 6): void
    {
        if (empty($data['location'])) {
            return;
        }

        $payload = [
            'location' => $data['location'] ?? null,
            'check_in' => $data['check_in'] ?? null,
            'check_out' => $data['check_out'] ?? null,
            'adults' => (int) ($data['adults'] ?? 2),
            'children' => (int) ($data['children'] ?? 0),
            'rooms' => (int) ($data['rooms'] ?? 1),
        ];

        $signature = md5(json_encode($payload));
        $payload['signature'] = $signature;

        $list = session('recent_searches', []);
        $list = is_array($list) ? $list : [];

        $list = array_values(array_filter($list, fn($x) => is_array($x) && (($x['signature'] ?? '') !== $signature)));
        array_unshift($list, $payload);
        $list = array_slice($list, 0, $limit);

        session(['recent_searches' => $list]);
    }
}
