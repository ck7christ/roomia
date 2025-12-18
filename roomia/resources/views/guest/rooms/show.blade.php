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
    @endphp

    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">{{ $roomName }}</h1>
                <div class="text-muted small">
                    <i class="fa-solid fa-location-dot me-1"></i>
                    {{ $fullAddress ?: ($cityName ?: 'Chưa cập nhật địa chỉ') }}
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('guest.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                </a>
            </div>
        </div>

        @includeIf('partials.general.alerts')

        {{-- Gallery + Summary --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                @include('partials.guest.rooms.gallery', [
                    'room' => $room,
                    'images' => $images,
                ])
            </div>

            <div class="col-12 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i> Tổng quan
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-muted small">Giá từ</div>
                            <div class="fw-semibold">
                                @if ($minPrice)
                                    {{ number_format((float) $minPrice, 0, ',', '.') }} đ
                                    <span class="text-muted small">/ đêm</span>
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        @if (!empty($room->description))
                            <div class="text-muted small">
                                {{ \Illuminate\Support\Str::limit($room->description, 180) }}
                            </div>
                        @endif

                        <hr>

                        <div class="text-muted small mb-2">
                            <i class="fa-solid fa-shield-heart me-2 text-success"></i>
                            Thanh toán an toàn • Theo dõi booking rõ ràng
                        </div>

                        <div class="text-muted small">
                            <i class="fa-solid fa-star me-2 text-warning"></i>
                            Review minh bạch (sau trả phòng)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">
                <i class="fa-solid fa-list-check me-2 text-accent"></i> Tiện nghi
            </div>
            <div class="card-body">
                @if ($amenities->count())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($amenities as $a)
                            <span class="badge text-bg-light border">
                                <i class="fa-solid fa-check me-1"></i>{{ $a->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted">Chưa có tiện nghi.</div>
                @endif
            </div>
        </div>

        {{-- Room types + Booking --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="fa-solid fa-bed me-2 text-primary"></i> Loại phòng
            </div>

            <div class="card-body">
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
