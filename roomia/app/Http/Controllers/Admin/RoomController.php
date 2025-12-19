<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Amenity;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $statuses = $this->statuses();

        $query = Room::query()
            ->with([
                'host:id,name,email',
                'address.country:id,name',
                'address.city:id,name',
                'address.district:id,name',
                'coverImage:id,room_id,file_path,is_cover,sort_order',
                'roomTypes:id,room_id,name,price_per_night,status',
            ]);

        // lọc theo status
        if ($request->filled('status') && in_array($request->status, $statuses, true)) {
            $query->where('status', $request->status);
        }

        // lọc theo host
        if ($request->filled('host_id')) {
            $query->where('host_id', (int) $request->host_id);
        }

        // lọc theo city (dựa theo address)
        if ($request->filled('city_id')) {
            $query->whereHas('address', function ($q) use ($request) {
                $q->where('city_id', (int) $request->city_id);
            });
        }

        // tìm theo địa điểm (destination) giống guest
        if ($request->filled('destination')) {
            $term = $this->normalizePlace($request->destination);

            if ($term) {
                $query->where(function ($qq) use ($term) {
                    $qq->whereHas('address.city', fn($q) => $q->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('address.district', fn($q) => $q->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('address.country', fn($q) => $q->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('address', fn($q) => $q->where('formatted_address', 'like', "%{$term}%"));
                });
            }
        }

        // tìm theo title
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . trim($request->q) . '%');
        }

        $rooms = $query->latest()->paginate(20)->withQueryString();

        // data cho filter (tuỳ bạn có cần hiển thị dropdown filter không)
        $hosts = User::query()
            ->select('id', 'name', 'email')
            ->when(method_exists(User::class, 'role'), fn($q) => $q->role('host')) // nếu có spatie macro
            ->orderBy('name')
            ->get();

        $cities = City::query()->select('id', 'name')->orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms', 'statuses', 'hosts', 'cities'));
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
        //
        $room->load([
            'host:id,name,email',
            'address.country',
            'address.city',
            'address.district',
            'images',
            'coverImage',
            'amenities',
            'roomTypes.calendars', // nếu cần xem lịch nhanh
        ]);

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
        $room->load(['address', 'amenities', 'images', 'coverImage', 'roomTypes']);

        $statuses = $this->statuses();

        $hosts = User::query()
            ->select('id', 'name', 'email')
            ->when(method_exists(User::class, 'role'), fn($q) => $q->role('host'))
            ->orderBy('name')
            ->get();

        $countries = Country::query()->select('id', 'name')->orderBy('name')->get();
        $cities = City::query()->select('id', 'name', 'country_id')->orderBy('name')->get();
        $districts = District::query()->select('id', 'name', 'city_id')->orderBy('name')->get();

        $amenities = Amenity::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.rooms.edit', compact(
            'room',
            'statuses',
            'hosts',
            'countries',
            'cities',
            'districts',
            'amenities'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
        $data = $request->validate([
            // room fields
            'host_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in($this->statuses())],

            // amenities (many-many)
            'amenity_ids' => ['nullable', 'array'],
            'amenity_ids.*' => ['integer', Rule::exists('amenities', 'id')],

            // address fields (hasOne)
            'country_id' => ['nullable', 'integer', Rule::exists('countries', 'id')],
            'city_id' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'district_id' => ['nullable', 'integer', Rule::exists('districts', 'id')],
            'street' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:30'],
            'formatted_address' => ['nullable', 'string', 'max:500'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        // 1) update room
        $room->update([
            'host_id' => (int) $data['host_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        // 2) sync amenities (nếu không gửi thì detach hết hoặc giữ nguyên tuỳ ý bạn)
        $amenityIds = $data['amenity_ids'] ?? [];
        $room->amenities()->sync($amenityIds);

        // 3) update/create address (nếu bạn muốn cho admin chỉnh địa chỉ)
        $addressData = [
            'country_id' => $data['country_id'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'street' => $data['street'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'formatted_address' => $data['formatted_address'] ?? null,
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
        ];

        // Chỉ tạo/update nếu có ít nhất 1 field address được nhập
        $hasAnyAddress = collect($addressData)->filter(fn($v) => $v !== null && $v !== '')->isNotEmpty();

        if ($hasAnyAddress) {
            $room->address()->updateOrCreate(['room_id' => $room->id], $addressData);
        }

        return redirect()
            ->route('admin.rooms.edit', $room)
            ->with('success', 'Cập nhật phòng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
        try {
            // detach pivot trước
            $room->amenities()->detach();

            // xoá quan hệ 1-n/1-1 (tuỳ DB cascade, làm thủ công cho chắc)
            $room->images()->delete();
            $room->address()->delete();

            // roomTypes có thể đang dính calendars/bookings -> có FK thì sẽ fail
            // Nếu bạn muốn xoá luôn calendars trước:
            // foreach ($room->roomTypes as $rt) { $rt->calendars()->delete(); }
            // $room->roomTypes()->delete();

            $room->delete();

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Đã xoá phòng.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Không thể xoá phòng (có thể đang có booking / ràng buộc dữ liệu).');
        }
    }
    /**
     * Danh sách status hợp lệ (điều chỉnh theo dự án của bạn)
     */
    private function statuses(): array
    {
        // ít nhất dự án bạn đang dùng "active" (guest chỉ show active)
        return ['draft', 'pending', 'active', 'inactive', 'blocked'];
    }
    private function normalizePlace(?string $value): ?string
    {
        if (!$value)
            return null;

        $v = Str::of($value)->lower()->ascii()->toString();
        $v = preg_replace('/[^a-z0-9\s]/u', ' ', $v);
        $v = preg_replace('/\b(thanh\s*pho|tp|tinh|quan|huyen|thi\s*xa|thi\s*tran|phuong|xa)\b/u', ' ', $v);
        $v = preg_replace('/\s+/u', ' ', trim($v));

        return $v !== '' ? $v : null;
    }
}
