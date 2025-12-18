<h6 class="fw-bold mb-2">BỘ SƯU TẬP NƠI Ở</h6>

<div class="row g-3 mb-4">
    @foreach(($collectionRooms ?? []) as $room)
        @php
            $img = $room->images->first()
                ? asset('storage/' . $room->images->first()->file_path)
                : asset('assets/images/placeholders/room-2.jpg');
        @endphp

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <img src="{{ $img }}" class="card-img-top" alt="room">
                <div class="card-body">
                    <div class="fw-semibold">{{ $room->title ?? $room->name ?? 'NAME' }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>