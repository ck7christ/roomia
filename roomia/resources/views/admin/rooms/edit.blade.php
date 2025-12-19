@extends('layouts.admin')
@section('title', 'Sửa Room')

@section('content')
    @php
        $addr = $room->address;
        $selectedAmenities = old('amenity_ids', $room->amenities?->pluck('id')->toArray() ?? []);
    @endphp

    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Sửa Room #{{ $room->id }}</h4>
                <div class="text-muted small">{{ $room->title }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-eye me-1"></i> Xem
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    {{-- Basic --}}
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Thông tin cơ bản</div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Host</label>
                                    <select name="host_id" class="form-select @error('host_id') is-invalid @enderror">
                                        @foreach ($hosts ?? [] as $h)
                                            <option value="{{ $h->id }}" @selected((string) old('host_id', $room->host_id) === (string) $h->id)>
                                                {{ $h->name }} ({{ $h->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('host_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        @foreach ($statuses ?? [] as $st)
                                            <option value="{{ $st }}" @selected(old('status', $room->status) === $st)>
                                                {{ strtoupper($st) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tiêu đề</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $room->title) }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Mô tả phòng...">{{ old('description', $room->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Amenities --}}
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Tiện ích</div>

                            @if (($amenities ?? collect())->count())
                                <div class="row g-2">
                                    @foreach ($amenities as $am)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="amenity_ids[]"
                                                    value="{{ $am->id }}" id="am-{{ $am->id }}"
                                                    @checked(in_array($am->id, $selectedAmenities))>
                                                <label class="form-check-label" for="am-{{ $am->id }}">
                                                    @if (!empty($am->icon_class))
                                                        <i class="{{ $am->icon_class }} me-1"></i>
                                                    @endif
                                                    {{ $am->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted">Chưa có amenities.</div>
                            @endif

                            @error('amenity_ids')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Địa chỉ</div>

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                        <option value="">—</option>
                                        @foreach ($countries ?? [] as $co)
                                            <option value="{{ $co->id }}" @selected((string) old('country_id', $addr?->country_id) === (string) $co->id)>
                                                {{ $co->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label">City</label>
                                    <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                        <option value="">—</option>
                                        @foreach ($cities ?? [] as $c)
                                            <option value="{{ $c->id }}" @selected((string) old('city_id', $addr?->city_id) === (string) $c->id)>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label">District</label>
                                    <select name="district_id"
                                        class="form-select @error('district_id') is-invalid @enderror">
                                        <option value="">—</option>
                                        @foreach ($districts ?? [] as $d)
                                            <option value="{{ $d->id }}" @selected((string) old('district_id', $addr?->district_id) === (string) $d->id)>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Street</label>
                                    <input type="text" name="street"
                                        class="form-control @error('street') is-invalid @enderror"
                                        value="{{ old('street', $addr?->street) }}">
                                    @error('street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Zip code</label>
                                    <input type="text" name="zip_code"
                                        class="form-control @error('zip_code') is-invalid @enderror"
                                        value="{{ old('zip_code', $addr?->zip_code) }}">
                                    @error('zip_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Formatted address</label>
                                    <input type="text" name="formatted_address"
                                        class="form-control @error('formatted_address') is-invalid @enderror"
                                        value="{{ old('formatted_address', $addr?->formatted_address) }}">
                                    @error('formatted_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 col-md-3">
                                    <label class="form-label">Lat</label>
                                    <input type="text" name="lat"
                                        class="form-control @error('lat') is-invalid @enderror"
                                        value="{{ old('lat', $addr?->lat) }}">
                                    @error('lat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 col-md-3">
                                    <label class="form-label">Lng</label>
                                    <input type="text" name="lng"
                                        class="form-control @error('lng') is-invalid @enderror"
                                        value="{{ old('lng', $addr?->lng) }}">
                                    @error('lng')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
                                        </button>
                                        <a href="{{ route('admin.rooms.show', $room) }}"
                                            class="btn btn-outline-secondary">
                                            Hủy
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: images (read-only) --}}
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Ảnh (xem)</div>

                            @if ($room->coverImage?->file_path)
                                <div class="mb-2">
                                    <div class="text-muted small mb-1">Cover</div>
                                    <img src="{{ asset('storage/' . $room->coverImage->file_path) }}"
                                        class="img-fluid rounded border" alt="cover">
                                </div>
                            @endif

                            @if ($room->images?->count())
                                <div class="row g-2">
                                    @foreach ($room->images as $img)
                                        <div class="col-6">
                                            <img src="{{ asset('storage/' . $img->file_path) }}"
                                                class="img-fluid rounded border" alt="img">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted">Chưa có ảnh.</div>
                            @endif

                            <div class="text-muted small mt-2">
                                (Module upload ảnh nếu có thì làm riêng controller/action.)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
