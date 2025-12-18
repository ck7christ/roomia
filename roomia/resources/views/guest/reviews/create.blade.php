{{-- resources/views/guest/reviews/create.blade.php --}}
<x-app-layout>
    <h1>Viết đánh giá</h1>

    <p>
        <a href="{{ route('guest.bookings.show', $booking->id) }}">← Quay lại chi tiết booking</a>
    </p>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h2>Thông tin đặt phòng</h2>
    <p><strong>Mã booking:</strong> {{ $booking->id }}</p>

    @php
        $roomType = $roomType ?? $booking->roomType;
        $room = $roomType?->room;
    @endphp

    @if($room)
        <p><strong>Chỗ nghỉ:</strong> {{ $room->name }}</p>
        @if(!empty($room->address))
            <p><strong>Địa chỉ:</strong> {{ $room->address }}</p>
        @endif
    @endif

    @if($roomType)
        <p><strong>Loại phòng:</strong> {{ $roomType->name }}</p>
    @endif

    <hr>

    <h2>Đánh giá của bạn</h2>

    <form method="POST" action="{{ route('guest.reviews.store', $booking->id) }}">
        @csrf

        <div>
            <label for="rating">Điểm đánh giá (1 - 5)</label><br>
            <input type="number" id="rating" name="rating" min="1" max="5" value="{{ old('rating', 5) }}">
            @error('rating')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="comment">Nhận xét</label><br>
            <textarea id="comment" name="comment" rows="4" cols="50">{{ old('comment') }}</textarea>
            @error('comment')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 10px;">
            <button type="submit">
                Gửi đánh giá
            </button>
        </div>
    </form>
</x-app-layout>