<h6 class="fw-bold mb-2">NHỮNG ĐIỂM DỪNG CHÂN ĐANG CHỜ BẠN KHÁM PHÁ</h6>

<div class="row g-3 mb-4">
    @forelse(($featuredRooms ?? []) as $room)
        @php
            $img = $room->images->first()
                ? asset('storage/' . $room->images->first()->file_path)
                : asset('assets/images/placeholders/room-1.jpg');

            $rating = data_get($roomRatings, $room->id . '.avg', 0);
            $count = data_get($roomRatings, $room->id . '.count', 0);
        @endphp

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
                <img src="{{ $img }}" class="card-img-top" alt="room">
                <div class="card-body">
                    <div class="fw-semibold">{{ $room->title ?? $room->name ?? 'NAME' }}</div>
                    <div class="text-muted small">{{ $room->city ?? 'CITY' }}, {{ $room->country ?? 'COUNTRY' }}</div>

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div>
                            <span class="badge text-bg-primary">{{ number_format((float) $rating, 1) }}</span>
                            <span class="text-muted small ms-1">RATING</span>
                            <div class="text-muted small">{{ $count }} đánh giá</div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        @for($i = 0; $i < 4; $i++)
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100">
                    <img src="{{ asset('assets/images/placeholders/room-1.jpg') }}" class="card-img-top" alt="room">
                    <div class="card-body">
                        <div class="fw-semibold">NAME</div>
                        <div class="text-muted small">CITY, COUNTRY</div>
                        <div class="mt-2">
                            <span class="badge text-bg-primary">0.0</span>
                            <span class="text-muted small ms-1">RATING</span>
                            <div class="text-muted small">0 đánh giá</div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    @endforelse
</div>