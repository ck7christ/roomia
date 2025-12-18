<h6 class="fw-bold mb-1">KHÁM PHÁ VIỆT NAM</h6>
<div class="text-muted small mb-2">Các điểm đến phổ biến này có nhiều điều chờ đón bạn</div>

<div class="row g-3 mb-4">
    @foreach(($exploreRooms ?? []) as $room)
        @php
            $img = $room->images->first()
                ? asset('storage/' . $room->images->first()->file_path)
                : asset('assets/images/placeholders/room-3.jpg');
        @endphp

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100">
                <img src="{{ $img }}" class="card-img-top" alt="room">
                <div class="card-body">
                    <div class="fw-semibold small">{{ $room->title ?? $room->name ?? 'NAME' }}</div>
                    <div class="text-muted small">0 chỗ nghỉ</div>
                </div>
            </div>
        </div>
    @endforeach
</div>