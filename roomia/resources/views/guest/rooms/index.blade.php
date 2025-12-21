{{-- resources/views/guest/rooms/index.blade.php --}}
@extends('layouts.guest')

@section('title', 'Lưu trú')

@section('content')
    @php
        $total = method_exists($rooms, 'total') ? $rooms->total() : (is_countable($rooms) ? count($rooms) : 0);
    @endphp

    <div class="container py-4 rm-rooms-index">
        {{-- Header --}}
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
            <div class="min-w-0">
                <h1 class="rm-title mb-1">Lưu trú</h1>
                <div class="text-muted small">
                    {{ number_format($total) }} kết quả
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                {{-- Sort (tuỳ bạn có xử lý request sort hay chưa) --}}
                <form method="GET" action="{{ route('guest.rooms.index') }}" class="d-flex gap-2">
                    @foreach(request()->except('sort', 'page') as $k => $v)
                        @if(is_array($v))
                            @foreach($v as $vv)
                                <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach

                    <select name="sort" class="form-select form-select-sm rm-select" onchange="this.form.submit()">
                        <option value="">Sắp xếp</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Mới nhất</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Recent searches (nếu bạn có partial) --}}
        @include('partials.guest.home.search')
        @include('partials.guest.home.recent-searches')
        {{-- Results --}}
        @if($rooms && $rooms->count())
            <div class="row g-3">
                @foreach($rooms as $room)
                    @php
                        $addr = $room->address ?? null;

                        $city = optional($addr?->city)->name;
                        $district = optional($addr?->district)->name;
                        $country = optional($addr?->country)->name;

                        $location = collect([$district, $city, $country])->filter()->implode(', ');

                        $coverPath = $room->coverImage?->file_path
                            ?? ($room->images?->first()?->file_path ?? null);

                        $roomTypes = $room->roomTypes ?? collect();
                        $minPrice = $roomTypes->min('price_per_night');
                        $activeTypes = $roomTypes->where('status', 'active')->count();
                        $amenities = $room->amenities ?? collect();
                    @endphp
                    {{-- Danh sách phòng --}}
                    <div class="col-12 col-lg-4">
                        <div class="card rm-card-soft rm-room-card h-100">
                            {{-- Cover --}}
                            <a href="{{ route('guest.rooms.show', $room) }}" class="rm-room-cover">
                                <div class="ratio ratio-4x3 rm-cover-frame">
                                    @if($coverPath)
                                        <img src="{{ asset('storage/' . $coverPath) }}" class="w-100 h-100 rm-cover-img" alt="cover">
                                    @else
                                        <div class="rm-cover-empty">
                                            <i class="fa-regular fa-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="rm-room-badges">
                                    @if(!empty($room->status))
                                        <span class="rm-badge">{{ strtoupper($room->status) }}</span>
                                    @endif
                                    @if($activeTypes)
                                        <span class="rm-badge rm-badge-soft">
                                            <i class="fa-solid fa-bed me-1"></i>{{ $activeTypes }} loại phòng
                                        </span>
                                    @endif
                                </div>
                                <div>

                                </div>
                            </a>

                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                    <div class="min-w-0">
                                        <a href="{{ route('guest.rooms.show', $room) }}" class="rm-room-title">
                                            {{ $room->title ?? $room->name ?? 'Chỗ ở' }}
                                        </a>

                                        <div class="text-muted small mt-1">
                                            <i class="fa-solid fa-location-dot me-1 rm-text-primary"></i>
                                            {{ $location ?: 'Chưa cập nhật địa chỉ' }}
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="text-muted small">Giá từ</div>
                                        <div class="rm-price">
                                            @if($minPrice)
                                                {{ number_format((float) $minPrice, 0, ',', '.') }} đ
                                            @else
                                                —
                                            @endif
                                        </div>
                                        <div class="text-muted small">/ đêm</div>
                                    </div>
                                </div>

                                {{-- Amenities preview --}}
                                @if($amenities->count())
                                    <div class="rm-amenities-preview mt-2">
                                        @foreach($amenities->take(3) as $a)
                                            <span class="rm-pill">
                                                @if(!empty($a->icon_class))
                                                    <i class="{{ $a->icon_class }} me-1"></i>
                                                @else
                                                    <i class="fa-solid fa-check me-1 rm-text-primary"></i>
                                                @endif
                                                {{ $a->name }}
                                            </span>
                                        @endforeach

                                        @if($amenities->count() > 3)
                                            <span class="rm-pill rm-pill-muted">+{{ $amenities->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="rm-divider my-3"></div>

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="text-muted small">
                                        <i class="fa-solid fa-shield-heart me-1 text-success"></i>
                                        Xác nhận rõ ràng • Thanh toán an toàn
                                    </div>

                                    <a href="{{ route('guest.rooms.show', $room) }}" class="btn btn-primary btn-sm">
                                        Xem chi tiết <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $rooms->links() }}
            </div>
        @else
            <div class="card rm-card-soft">
                <div class="card-body text-center py-5">
                    <div class="display-6 mb-2">😕</div>
                    <h2 class="h5 mb-1">Không tìm thấy chỗ ở phù hợp</h2>
                    <div class="text-muted mb-3">Thử đổi từ khóa hoặc chọn thành phố khác.</div>
                    <a href="{{ route('guest.rooms.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Xem tất cả
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection