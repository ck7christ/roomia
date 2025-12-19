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
    @endphp
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3 class="h5 mb-3">Địa chỉ</h3>

                <div class="row g-3">
                    {{-- Các dropdown vẫn tồn tại nhưng ẩn (để JS set value) --}}
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

                    {{-- Địa chỉ chi tiết (autocomplete) --}}
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="formatted_address" class="form-label">Địa chỉ đầy đủ</label>
                            <input type="text" id="formatted_address" name="formatted_address"
                                class="form-control @error('formatted_address') is-invalid @enderror"
                                value="{{ old('formatted_address', $addr->formatted_address ?? '') }}"
                                placeholder="Nhập địa điểm để gợi ý..." autocomplete="off" />

                            @error('formatted_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Bản đồ --}}
                    <div class="col-12">
                        <div class="mb-0">
                            <label class="form-label">Bản đồ</label>

                            <div data-room-map data-mode="edit" data-lat="{{ old('lat', $addr->lat ?? '') }}"
                                data-lng="{{ old('lng', $addr->lng ?? '') }}" data-lat-input="#lat"
                                data-lng-input="#lng" data-autocomplete-input="#formatted_address"
                                data-formatted-input="#formatted_address" data-street-input="#street">
                                {{-- giữ class room-map nếu bạn đang có CSS cũ --}}
                                <div class="rm-map-canvas room-map border rounded"></div>
                            </div>

                            <div class="form-text">
                                Gõ địa chỉ ở ô “Địa chỉ đầy đủ” để chọn gợi ý, hoặc click/kéo marker để cập nhật vị trí.
                            </div>
                        </div>
                    </div>

                </div>

                {{-- hidden address fields --}}
                <input type="hidden" id="lat" name="lat" value="{{ old('lat', $addr->lat ?? '') }}">
                <input type="hidden" id="lng" name="lng" value="{{ old('lng', $addr->lng ?? '') }}">
                <input type="hidden" id="formatted_address" name="formatted_address"
                    value="{{ old('formatted_address', $addr->formatted_address ?? '') }}">
                <input type="hidden" id="country_name" name="country_name"
                    value="{{ old('country_name', $addr->country->name ?? '') }}">
                <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', '') }}">

                <input type="hidden" id="city_name" name="city_name"
                    value="{{ old('city_name', $addr->city->name ?? '') }}">
                <input type="hidden" id="district_name" name="district_name"
                    value="{{ old('district_name', $addr->district->name ?? '') }}">
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
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        Có thể chọn nhiều ảnh cùng lúc (JPG, PNG, WebP, ...).
                        Nhấn <i class="fa-solid fa-plus small"></i> để thêm dòng chọn ảnh khác,
                        các ảnh ở dòng trước sẽ không bị mất.
                    </div>
                </div>

                {{-- Preview ảnh khi EDIT --}}
                @if (isset($room) && $room->images && $room->images->count())
                    @include('partials.general.image-gallery', ['room' => $room])
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

                @php
                    $selectedAmenities = old(
                        'amenities',
                        isset($room) && $room->amenities ? $room->amenities->pluck('id')->toArray() : [],
                    );
                @endphp

                <div class="row g-2">
                    @foreach ($amenities as $a)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="amenity-{{ $a->id }}" value="{{ $a->id }}"
                                    @checked(in_array($a->id, $selectedAmenities))>
                                <label class="form-check-label" for="amenity-{{ $a->id }}">
                                    @if (!empty($a->icon))
                                        <i class="{{ $a->icon }} me-1"></i>
                                    @endif
                                    {{ $a->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('amenities')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
