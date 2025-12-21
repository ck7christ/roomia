{{-- resources/views/host/rooms/_form.blade.php --}}
@csrf

{{-- HÀNG 1: Thông tin phòng + Địa chỉ --}}
<div class="row g-4 mb-4">
    {{-- ===========================
    THÔNG TIN PHÒNG
    =========================== --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3 class="h5 mb-3">Thông tin phòng</h3>

                <div class="row g-3">
                    {{-- Tiêu đề --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $room->title ?? '') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-4">
                        @php
                            $status = old('status', $room->status ?? 'draft');
                        @endphp
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" @selected($status === 'draft')>Nháp</option>
                                <option value="active" @selected($status === 'active')>Đang hoạt động</option>
                                <option value="inactive" @selected($status === 'inactive')>Tạm dừng</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-0">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea id="description" name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $room->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===========================
    ĐỊA CHỈ + MAP
    =========================== --}}
    @php
        $addr = isset($room) ? $room->address ?? null : null;

        // an toàn khi $addr null (create)
        $addrCountryName = old('country_name', data_get($addr, 'country.name', ''));
        $addrCityName = old('city_name', data_get($addr, 'city.name', ''));
        $addrDistrictName = old('district_name', data_get($addr, 'district.name', ''));
    @endphp

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3 class="h5 mb-3">Địa chỉ</h3>

                <div class="row g-3">
                    {{-- Các dropdown vẫn tồn tại nhưng ẩn (để controller/JS có thể set nếu cần) --}}
                    <div class="col-md-4 d-none">
                        <div class="mb-3">
                            <label for="country_id" class="form-label">Quốc gia</label>
                            <select id="country_id" name="country_id"
                                class="form-select @error('country_id') is-invalid @enderror">
                                <option value="">-- Chọn quốc gia --</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id', $addr->country_id ?? null) == $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 d-none">
                        <div class="mb-3">
                            <label for="city_id" class="form-label">Thành phố</label>
                            <select id="city_id" name="city_id"
                                class="form-select @error('city_id') is-invalid @enderror">
                                <option value="">-- Chọn thành phố --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id', $addr->city_id ?? null) == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 d-none">
                        <div class="mb-3">
                            <label for="district_id" class="form-label">Quận / Huyện</label>
                            <select id="district_id" name="district_id"
                                class="form-select @error('district_id') is-invalid @enderror">
                                <option value="">-- Chọn quận/huyện --</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" @selected(old('district_id', $addr->district_id ?? null) == $district->id)>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Địa chỉ đầy đủ (autocomplete) --}}
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="formatted_address" class="form-label">Địa chỉ đầy đủ</label>
                            <input type="text" id="formatted_address" name="formatted_address"
                                class="form-control @error('formatted_address') is-invalid @enderror"
                                value="{{ old('formatted_address', $addr->formatted_address ?? '') }}"
                                placeholder="Nhập địa điểm để gợi ý..." autocomplete="on" />
                            @error('formatted_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Bản đồ --}}
                    <div class="col-12">
                        <div class="mb-0">
                            <label class="form-label">Bản đồ</label>

                            @include('partials.general.map', [
                                'mode' => 'edit',
                                'lat' => old('lat', $addr->lat ?? ''),
                                'lng' => old('lng', $addr->lng ?? ''),
                            
                                'latInput' => '#lat',
                                'lngInput' => '#lng',
                            
                                'autocompleteInput' => '#formatted_address',
                                'formattedInput' => '#formatted_address',
                                'streetInput' => '#street',
                            
                                // để controller resolve ID (optional)
                                'countryNameInput' => '#country_name',
                                'countryCodeInput' => '#country_code',
                                'cityNameInput' => '#city_name',
                                'districtNameInput' => '#district_name',
                            
                                'heightClass' => 'rm-map-h320',
                            ])

                            <div class="form-text">
                                Gõ địa chỉ ở ô “Địa chỉ đầy đủ” để chọn gợi ý, hoặc click/kéo marker để cập nhật vị trí.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- hidden address fields --}}
                <input type="hidden" id="lat" name="lat" value="{{ old('lat', $addr->lat ?? '') }}">
                <input type="hidden" id="lng" name="lng" value="{{ old('lng', $addr->lng ?? '') }}">

                {{-- STREET: controller của bạn có validate & lưu field này --}}
                <input type="hidden" id="street" name="street" value="{{ old('street', $addr->street ?? '') }}">

                {{-- KHÔNG để hidden formatted_address nữa (tránh đè giá trị input text) --}}

                <input type="hidden" id="country_name" name="country_name" value="{{ $addrCountryName }}">
                <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', '') }}">

                <input type="hidden" id="city_name" name="city_name" value="{{ $addrCityName }}">
                <input type="hidden" id="district_name" name="district_name" value="{{ $addrDistrictName }}">

                @error('lat')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                @error('lng')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                @error('formatted_address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- HÀNG 2: Ảnh + Tiện nghi --}}
<div class="row g-4">
    {{-- ===========================
    ẢNH
    =========================== --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3 class="h5 mb-3 d-flex align-items-center">
                    Ảnh
                </h3>

                {{-- Upload ảnh mới --}}
                <div class="mb-3">
                    <label for="images" class="form-label d-flex align-items-center">
                        Chọn ảnh phòng
                    </label>

                    <div id="image-input-wrapper">
                        {{-- Hàng input đầu tiên --}}
                        <div class="input-group mb-2 image-input-row">
                            <input
                                class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                type="file" id="images" name="images[]" multiple accept="image/*">
                            <button class="btn btn-outline-secondary btn-add-image" type="button">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    @error('images')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        Bạn có thể chọn nhiều ảnh một lần. Ảnh đầu tiên sẽ được ưu tiên làm ảnh bìa.
                    </div>
                </div>

                {{-- Ảnh hiện có (khi edit) --}}
                @if (!empty($room) && !empty($room->images) && $room->images->count())
                    <div class="mt-3">
                        <div class="small fw-semibold mb-2">Ảnh hiện có</div>
                        <div class="row g-2">
                            @foreach ($room->images as $img)
                                <div class="col-4 col-md-3">
                                    <div class="border rounded overflow-hidden">
                                        <img src="{{ asset('storage/' . $img->file_path) }}" class="img-fluid"
                                            alt="Room image">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===========================
    TIỆN NGHI
    =========================== --}}
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3 class="h5 mb-3">Tiện nghi</h3>

                <div class="row g-2">
                    @php
                        $selectedAmenities = old(
                            'amenities',
                            isset($room) ? $room->amenities?->pluck('id')->toArray() : [],
                        );
                    @endphp

                    @foreach ($amenities as $a)
                        <div class="col-12 col-md-6">
                            <label class="d-flex align-items-center gap-2 border rounded p-2">
                                <input type="checkbox" name="amenities[]" value="{{ $a->id }}"
                                    class="form-check-input m-0" @checked(in_array($a->id, $selectedAmenities ?? []))>
                                <span class="small">{{ $a->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('amenities')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                @error('amenities.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
