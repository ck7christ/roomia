<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;
use Illuminate\Support\Facades\Auth;

use App\Models\Country;
use App\Models\City;
use App\Models\District;
use App\Models\RoomAddress;
use Illuminate\Support\Facades\DB;

use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;

use App\Models\Amenity;

use App\Models\RoomType;
use Illuminate\Support\Str;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Danh sách phòng
        $rooms = Room::with([
            'address.country',
            'address.city',
            'address.district',
            'coverImage',
            'images'
        ])
            ->where('host_id', Auth::id())
            ->latest()
            ->get();

        return view('host.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Form tạo phòng
        $roomTypes = RoomType::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $amenities = Amenity::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $room = new Room();

        return view('host.rooms.create', compact('room', 'countries', 'cities', 'districts', 'amenities', 'roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Lưu phòng mới
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            // status (nếu muốn chọn ngay lúc tạo)
            'status' => 'nullable|in:draft,active,inactive',

            // address
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'street' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'formatted_address' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',

            'country_name' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'city_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',


            // images
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // amenities
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);
        $validated = $this->resolveAddressIds($validated);

        $validated['host_id'] = Auth::id();

        // Nếu bảng rooms có cột status thì set mặc định
        if (empty($validated['status'])) {
            $validated['status'] = 'draft';
        }

        DB::transaction(function () use ($request, $validated) {
            // Tạo room
            $room = Room::create([
                'host_id' => $validated['host_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            // Address (nếu có)
            if ($this->hasAddressData($validated)) {
                RoomAddress::create([
                    'room_id' => $room->id,
                    'country_id' => $validated['country_id'] ?? null,
                    'city_id' => $validated['city_id'] ?? null,
                    'district_id' => $validated['district_id'] ?? null,
                    'street' => $validated['street'] ?? null,
                    'zip_code' => $validated['zip_code'] ?? null,
                    'formatted_address' => $validated['formatted_address'] ?? null,
                    'lat' => $validated['lat'] ?? null,
                    'lng' => $validated['lng'] ?? null,
                ]);
            }

            // Images (upload ngay khi tạo phòng)
            if ($request->hasFile('images')) {
                $isFirst = true;

                foreach ($request->file('images') as $file) {
                    if (!$file->isValid()) {
                        continue;
                    }

                    $path = $file->store('rooms', 'public');

                    RoomImage::create([
                        'room_id' => $room->id,
                        'file_path' => $path,
                        'is_cover' => $isFirst,
                        'sort_order' => 0,
                    ]);

                    $isFirst = false;
                }
            }

            // Gắn amenities cho room
            if (!empty($validated['amenities'])) {
                $room->amenities()->sync($validated['amenities']);
            }
        });

        return redirect()
            ->route('host.rooms.index')
            ->with('success', 'Tạo phòng thành công!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //Chi tiết phòng
        $this->authorizeRoom($room);

        $room->load([
            'address.country',
            'address.city',
            'address.district',
            'images',
            'coverImage',
        ]);

        return view('host.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //Form sửa phòng

        $this->authorizeRoom($room);

        $roomTypes = RoomType::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $amenities = Amenity::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // load luôn address để đổ vào form
        $room->load('address', 'images', 'amenities');

        return view('host.rooms.edit', compact('room', 'countries', 'cities', 'districts', 'amenities', 'roomTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        // Lưu phòng đã sửa
        $this->authorizeRoom($room);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',

            // address
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'street' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'formatted_address' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',

            'country_name' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'city_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',


            // images mới upload thêm
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // id ảnh muốn xóa
            'delete_images.*' => 'nullable|integer|exists:room_images,id',

            // amenities
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
        ]);
        $validated = $this->resolveAddressIds($validated);


        DB::transaction(function () use ($request, $validated, $room) {
            // Cập nhật room
            $room->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            // Cập nhật / tạo mới địa chỉ
            $addressData = [
                'country_id' => $validated['country_id'] ?? null,
                'city_id' => $validated['city_id'] ?? null,
                'district_id' => $validated['district_id'] ?? null,
                'street' => $validated['street'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'formatted_address' => $validated['formatted_address'] ?? null,
                'lat' => $validated['lat'] ?? null,
                'lng' => $validated['lng'] ?? null,
            ];

            if ($this->hasAddressData($addressData)) {
                RoomAddress::updateOrCreate(
                    ['room_id' => $room->id],
                    $addressData
                );
            } else {
                $room->address()->delete();
            }

            // Xóa ảnh được chọn
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = RoomImage::where('room_id', $room->id)
                    ->whereIn('id', $validated['delete_images'])
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->file_path);
                    $img->delete();
                }
            }

            // Thêm ảnh mới (nếu có upload)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if (!$file->isValid()) {
                        continue;
                    }

                    $path = $file->store('rooms', 'public');

                    RoomImage::create([
                        'room_id' => $room->id,
                        'file_path' => $path,
                        'is_cover' => false,
                        'sort_order' => 0,
                    ]);
                }
            }

            // Cập nhật amenities
            if (!empty($validated['amenities'])) {
                $room->amenities()->sync($validated['amenities']);
            } else {
                $room->amenities()->sync([]);
            }
        });

        return redirect()
            ->route('host.rooms.index')
            ->with('success', 'Cập nhật phòng thành công!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //Xóa phòng
        $this->authorizeRoom($room);

        DB::transaction(function () use ($room) {
            // Xóa địa chỉ (nếu chưa set cascade)
            $room->address()->delete();

            // Xóa file ảnh
            foreach ($room->images as $img) {
                Storage::disk('public')->delete($img->file_path);
            }

            $room->delete();
        });

        return redirect()->route('host.rooms.index')->with('success', 'Xóa phòng thành công!');
    }

    //==========Helper==========
    // Check quyền host
    private function authorizeRoom(Room $room)
    {
        if ($room->host_id !== Auth::id()) {
            abort(403, 'Không có quyền truy cập.');
        }
    }
    // Kiểm tra form có nhập dữ liệu địa chỉ không
    private function hasAddressData(array $data): bool
    {
        return !empty($data['country_id'])
            || !empty($data['city_id'])
            || !empty($data['district_id'])
            || !empty($data['street'])
            || !empty($data['zip_code'])
            || !empty($data['formatted_address'])
            || !empty($data['lat'])
            || !empty($data['lng']);
    }

    /**
     * Chuẩn hoá chuỗi để so khớp tên địa danh:
     * - lowercase
     * - bỏ dấu (ascii)
     * - bỏ tiền tố hành chính (thành phố, quận, huyện,...)
     */
    private function normalizePlace(?string $value): ?string
    {
        if (!$value)
            return null;

        // lower + bỏ dấu
        $v = Str::of($value)->lower()->ascii()->toString();

        // BỎ DẤU CÂU (fix case "tp." còn lại dấu chấm)
        $v = preg_replace('/[^a-z0-9\s]/u', ' ', $v);

        // Bỏ tiền tố hành chính (thêm \s* để bắt cả "thanhpho")
        $v = preg_replace('/\b(thanh\s*pho|tp|tinh|quan|huyen|thi\s*xa|thi\s*tran|phuong|xa)\b/u', ' ', $v);

        // gọn khoảng trắng
        $v = preg_replace('/\s+/u', ' ', $v);

        $v = trim($v);

        return $v !== '' ? $v : null;
    }


    /**
     * Nếu JS không set được dropdown id (do lệch text), ta dùng name/code để tự map ra id.
     */
    private function resolveAddressIds(array $data): array
    {
        // Resolve country
        if (empty($data['country_id'])) {
            $countryName = $this->normalizePlace($data['country_name'] ?? null);
            $countryCode = strtoupper(trim($data['country_code'] ?? ''));

            // Special-case Vietnam
            if ($countryCode === 'VN' || in_array($countryName, ['vietnam', 'viet nam'], true)) {
                $vn = Country::query()->whereIn('name', ['Việt Nam', 'Vietnam', 'Viet Nam'])->first();
                if ($vn)
                    $data['country_id'] = $vn->id;
            } elseif ($countryName) {
                foreach (Country::select('id', 'name')->get() as $c) {
                    if ($this->normalizePlace($c->name) === $countryName) {
                        $data['country_id'] = $c->id;
                        break;
                    }
                }
            }
        }

        // Resolve city
        if (empty($data['city_id'])) {
            $cityName = $this->normalizePlace($data['city_name'] ?? null);

            if ($cityName) {
                $citiesQ = City::select('id', 'name');

                // nếu bảng cities có country_id thì bật dòng này
                // if (!empty($data['country_id'])) $citiesQ->where('country_id', $data['country_id']);

                foreach ($citiesQ->get() as $c) {
                    $candidate = $this->normalizePlace($c->name);

                    if (
                        $candidate === $cityName
                        || str_contains($candidate, $cityName)
                        || str_contains($cityName, $candidate)
                    ) {
                        $data['city_id'] = $c->id;
                        break;
                    }
                }
            }
        }

        // Resolve district
        if (empty($data['district_id'])) {
            $districtName = $this->normalizePlace($data['district_name'] ?? null);

            if ($districtName) {
                $districtsQ = District::select('id', 'name');
                if (!empty($data['city_id'])) {
                    $districtsQ->where('city_id', $data['city_id']);
                }

                foreach ($districtsQ->get() as $d) {
                    $candidate = $this->normalizePlace($d->name);

                    if (
                        $candidate === $districtName
                        || str_contains($candidate, $districtName)
                        || str_contains($districtName, $candidate)
                    ) {
                        $data['district_id'] = $d->id;
                        break;
                    }
                }
            }
        }

        // Fallback: nếu vẫn chưa ra district_id, thử dò từ formatted_address (rất hợp case "Thành phố ...")
        if (empty($data['district_id']) && !empty($data['city_id']) && !empty($data['formatted_address'])) {
            $haystack = $this->normalizePlace($data['formatted_address']);

            if ($haystack) {
                $districts = District::select('id', 'name')
                    ->where('city_id', $data['city_id'])
                    ->get();

                foreach ($districts as $d) {
                    $needle = $this->normalizePlace($d->name);

                    if ($needle && str_contains($haystack, $needle)) {
                        $data['district_id'] = $d->id;
                        break;
                    }
                }
            }
        }

        return $data;
    }

}
