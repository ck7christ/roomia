@php
    $cover = $room->coverImage ?? $room->images?->first();
    $addr = $room->address ?? null;
    $cityName = optional($addr?->city)->name;

    $minPrice = $room->roomTypes?->min('price_per_night');
    $amenities = $room->amenities ?? collect();
@endphp

<div class="card rm-card h-100 shadow-sm">
    <a href="{{ route('guest.rooms.show', $room) }}" class="text-decoration-none">
        <div class="room-thumb-wrapper position-relative">
            @if ($cover)
                <img src="{{ asset('storage/' . $cover->file_path) }}" alt="{{ $room->title ?? $room->name }}">
            @else
                <div class="room-thumb-overlay">Không có ảnh</div>
            @endif
        </div>
    </a>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div class="flex-grow-1">
                <h2 class="h6 mb-1">
                    <a class="text-decoration-none text-dark" href="{{ route('guest.rooms.show', $room) }}">
                        {{ $room->title ?? ($room->name ?? 'Chỗ ở') }}
                    </a>
                </h2>

                <div class="text-muted small">
                    <i class="fa-solid fa-location-dot me-1"></i>
                    {{ $cityName ?: 'Chưa cập nhật địa chỉ' }}
                </div>
            </div>

            <div class="text-end">
                <div class="small text-muted">Giá từ</div>
                <div class="fw-semibold">
                    @if ($minPrice)
                        {{ number_format((float) $minPrice, 0, ',', '.') }} đ
                    @else
                        —
                    @endif
                    <span class="small text-muted">/ đêm</span>
                </div>
            </div>
        </div>

        <div class="mt-3">
            @if ($amenities->count())
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($amenities->take(6) as $a)
                        <span class="badge text-bg-light border">
                            <i class="fa-solid fa-check me-1"></i>{{ $a->name }}
                        </span>
                    @endforeach

                    @if ($amenities->count() > 6)
                        <span class="badge text-bg-light border">+{{ $amenities->count() - 6 }}</span>
                    @endif
                </div>
            @else
                <div class="text-muted small">Chưa có tiện nghi.</div>
            @endif
        </div>
    </div>

    <div class="card-footer bg-white border-0 pt-0 pb-3">
        <div class="d-grid">
            <a href="{{ route('guest.rooms.show', $room) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-right me-1"></i> Xem chi tiết
            </a>
        </div>
    </div>
</div>
