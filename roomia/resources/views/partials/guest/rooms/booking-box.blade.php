{{-- resources/views/partials/guest/room/booking-box.blade.php --}}
@props(['room'])

<form method="POST" action="{{ route('guest.bookings.store') ?? '#' }}">
    @csrf

    <input type="hidden" name="room_id" value="{{ $room->id ?? null }}">

    <div class="border">
        <div class="p-2">
            @if (isset($room->price_per_night))
                <div>
                    <strong>{{ number_format($room->price_per_night) }}</strong>
                    <span>/ đêm</span>
                </div>
            @endif
        </div>

        <div class="p-2">
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label">Check-in</label>
                    <input type="date" name="check_in" class="form-control">
                </div>
                <div class="col-6">
                    <label class="form-label">Check-out</label>
                    <input type="date" name="check_out" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Số khách</label>
                    <input type="number" name="guests" class="form-control" min="1" value="1">
                </div>
            </div>
        </div>

        <div class="p-2">
            <button type="submit" class="btn btn-primary w-100">
                Đặt phòng
            </button>
        </div>
    </div>
</form>