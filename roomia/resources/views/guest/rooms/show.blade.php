{{-- resources/views/guest/rooms/show.blade.php --}}
@extends('layouts.guest')

@section('title', $room->title ?? ($room->name ?? 'Chi tiết chỗ ở'))

@section('content')
    @php
        $roomName = $room->title ?? ($room->name ?? 'Chỗ ở');
        $addr = $room->address ?? null;

        $cityName = optional($addr?->city)->name;
        $districtName = optional($addr?->district)->name;
        $countryName = optional($addr?->country)->name;

        $street =
            data_get($addr, 'street') ?? (data_get($addr, 'address_line') ?? (data_get($addr, 'full_address') ?? null));

        $fullAddress = collect([$street, $districtName, $cityName, $countryName])
            ->filter()
            ->join(', ');

        $images = $room->images ?? collect();
        $amenities = $room->amenities ?? collect();
        $roomTypes = $room->roomTypes ?? collect();

        $minPrice = $roomTypes->min('price_per_night');
        $lat = $addr?->lat;
        $lng = $addr?->lng;
    @endphp

    <div class="container py-4 rm-room-show">
        {{-- Header --}}
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div class="min-w-0">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h1 class="rm-title mb-1 text-truncate">{{ $roomName }}</h1>
                    @if (!empty($room->status))
                        <span class="rm-chip">{{ strtoupper($room->status) }}</span>
                    @endif
                </div>

                <div class="text-muted small d-flex flex-wrap align-items-center gap-2">
                    <span>
                        <i class="fa-solid fa-location-dot me-1 rm-text-primary"></i>
                        {{ $fullAddress ?: ($cityName ?: 'Chưa cập nhật địa chỉ') }}
                    </span>

                    @if ($lat && $lng)
                        <span class="rm-chip">
                            <i class="fa-solid fa-crosshairs me-1"></i>{{ $lat }}, {{ $lng }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @includeIf('partials.general.alerts')

        {{-- Quick stats --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <div class="card rm-card-soft h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Giá từ</div>
                        <div class="fs-5 fw-semibold">
                            @if ($minPrice)
                                {{ number_format((float) $minPrice, 0, ',', '.') }} đ
                                <span class="text-muted small fw-normal">/ đêm</span>
                            @else
                                —
                            @endif
                        </div>
                        <div class="text-muted small mt-2">
                            <i class="fa-solid fa-tags me-1 rm-text-accent"></i>
                            Giá tùy theo loại phòng & ngày
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card rm-card-soft h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Loại phòng</div>
                        <div class="fs-5 fw-semibold">{{ $roomTypes->count() }}</div>
                        <div class="text-muted small mt-2">
                            <i class="fa-solid fa-calendar-check me-1 rm-text-primary"></i>
                            Có lịch & giá theo ngày
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card rm-card-soft h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Tiện nghi</div>
                        <div class="fs-5 fw-semibold">{{ $amenities->count() }}</div>
                        <div class="text-muted small mt-2">
                            <i class="fa-solid fa-shield-heart me-1 text-success"></i>
                            Thanh toán an toàn • Theo dõi booking
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main layout --}}
        <div class="row g-3">
            {{-- Left --}}
            <div class="col-12 col-lg-8">
                {{-- Gallery --}}
                <div class="card rm-card-soft mb-3">
                    <div class="card-body">
                        <div class="rm-section-head mb-2">
                            <div class="rm-section-title">
                                <i class="fa-regular fa-images me-2 rm-text-primary"></i> Hình ảnh
                            </div>
                        </div>

                        @include('partials.guest.rooms.gallery', [
                            'room' => $room,
                            'images' => $images,
                        ])
                    </div>
                </div>

                {{-- Description --}}
                <div class="card rm-card-soft mb-3">
                    <div class="card-body">
                        <div class="rm-section-head mb-2">
                            <div class="rm-section-title">
                                <i class="fa-solid fa-circle-info me-2 rm-text-primary"></i> Giới thiệu
                            </div>
                        </div>

                        @if (!empty($room->description))
                            <div class="rm-text">
                                {{ $room->description }}
                            </div>
                        @else
                            <div class="text-muted">Chưa có mô tả.</div>
                        @endif
                    </div>
                </div>

                {{-- Amenities --}}
                <div class="card rm-card-soft mb-3" id="amenities">
                    <div class="card-body">
                        <div class="rm-section-head mb-2">
                            <div class="rm-section-title">
                                <i class="fa-solid fa-list-check me-2 rm-text-accent"></i> Tiện nghi
                            </div>
                        </div>

                        @if ($amenities->count())
                            <div class="row g-2">
                                @foreach ($amenities as $a)
                                    <div class="col-12 col-md-6">
                                        <div class="rm-amenity">
                                            <i class="fa-solid fa-check rm-text-primary"></i>
                                            <span>{{ $a->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">Chưa có tiện nghi.</div>
                        @endif
                    </div>
                </div>

                {{-- Map --}}
                <div class="card rm-card-soft mb-3" id="map">
                    <div class="card-body">
                        <div class="rm-section-head mb-2">
                            <div class="rm-section-title">
                                <i class="fa-solid fa-map-location-dot me-2 rm-text-primary"></i> Vị trí
                            </div>
                            <div class="text-muted small">
                                {{ $fullAddress ?: 'Chưa cập nhật địa chỉ' }}
                            </div>
                        </div>

                        @if ($lat && $lng)
                            @includeIf('partials.general.map', [
                                'address' => $addr,
                                'mode' => 'show',
                                'heightClass' => 'rm-map-h320',
                            ])
                        @else
                            <div class="rm-empty">
                                Chưa có tọa độ để hiển thị bản đồ.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right (sticky) --}}
            <div class="col-12 col-lg-4">
                <div class="card rm-card-soft rm-sticky">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <div class="text-muted small">Giá từ</div>
                                <div class="fs-4 fw-semibold">
                                    @if ($minPrice)
                                        {{ number_format((float) $minPrice, 0, ',', '.') }} đ
                                    @else
                                        —
                                    @endif
                                </div>
                                <div class="text-muted small">/ đêm</div>
                            </div>

                            <span class="rm-chip">
                                <i class="fa-solid fa-star me-1 rm-text-accent"></i>
                                Review (sau trả phòng)
                            </span>
                        </div>

                        <div class="rm-divider"></div>

                        <div class="d-grid gap-2">
                            <a href="#room-types" class="btn btn-primary">
                                <i class="fa-solid fa-bed me-1"></i> Xem loại phòng
                            </a>

                            <a href="#amenities" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-list-check me-1"></i> Xem tiện nghi
                            </a>

                            <a href="#map" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-map me-1"></i> Xem bản đồ
                            </a>
                        </div>

                        <div class="rm-divider"></div>

                        <div class="text-muted small">
                            <i class="fa-solid fa-shield-heart me-2 text-success"></i>
                            Thanh toán an toàn • Xác nhận minh bạch
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Room types --}}
        <div class="card rm-card-soft mt-3" id="room-types">
            <div class="card-body">
                <div class="rm-section-head mb-2">
                    <div class="rm-section-title">
                        <i class="fa-solid fa-bed me-2 rm-text-primary"></i> Loại phòng
                    </div>
                    <div class="text-muted small">Chọn loại phù hợp và tiếp tục đặt phòng</div>
                </div>

                @if ($roomTypes->count())
                    <div class="row g-3">
                        @foreach ($roomTypes as $rt)
                            <div class="col-12">
                                @include('partials.guest.rooms.roomtype-card', ['roomType' => $rt])
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted">Chỗ ở này chưa có loại phòng.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
