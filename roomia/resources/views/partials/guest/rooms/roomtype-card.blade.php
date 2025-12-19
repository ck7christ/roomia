@php
    $rt = $roomType;
    $bookRoute = \Illuminate\Support\Facades\Route::has('guest.bookings.store') ? route('guest.bookings.store') : null;

    $collapseId = 'bookFormRt' . ($rt->id ?? 'x');
    $shouldShow = (string) old('room_type_id') === (string) ($rt->id ?? '');
@endphp
<div class="card border">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
                <div class="fw-semibold">{{ $rt->name ?? 'Loại phòng' }}</div>

                @if (!empty($rt->description))
                    <div class="text-muted small">{{ $rt->description }}</div>
                @endif

                <div class="text-muted small mt-2">
                    <i class="fa-solid fa-user-group me-1"></i>
                    Tối đa: {{ (int) ($rt->max_guests ?? 0) }} khách
                </div>
            </div>

            <div class="text-end">
                <div class="text-muted small">Giá / đêm</div>
                <div class="fw-semibold">
                    {{ number_format((float) ($rt->price_per_night ?? 0), 0, ',', '.') }} đ
                </div>

                <button class="btn btn-primary btn-sm mt-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $shouldShow ? 'true' : 'false' }}"
                    aria-controls="{{ $collapseId }}">
                    <i class="fa-solid fa-calendar-check me-1"></i> Đặt phòng
                </button>
            </div>
        </div>

        <div class="collapse mt-3 {{ $shouldShow ? 'show' : '' }}" id="{{ $collapseId }}">
            @if ($bookRoute)
                @include('partials.guest.rooms.booking-form', [
                    'action' => $bookRoute,
                    'roomTypeId' => $rt->id,
                ])
            @else
                <div class="alert alert-warning mb-0">
                    Thiếu route guest.room-types.book (đặt phòng theo RoomType).
                </div>
            @endif
        </div>
    </div>
</div>
