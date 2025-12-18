{{-- resources/views/host/roomTypes/show.blade.php --}}
<x-app-layout>
    <h1>Chi tiết loại phòng: {{ $roomType->name }}</h1>

    <p>
        <a href="{{ route('host.rooms.room-types.index', $room) }}">← Quay lại danh sách loại phòng</a> |
        <a href="{{ route('host.rooms.show', $room) }}">Xem phòng</a> |
        <a href="{{ route('host.rooms.room-types.edit', [$room, $roomType]) }}">Sửa loại phòng</a> |
        <a href="{{ route('host.room-types.calendars.index', $roomType) }}">Quản lý lịch</a>
    </p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <h3>Thông tin cơ bản</h3>
    <p><strong>ID:</strong> {{ $roomType->id }}</p>
    <p><strong>Tên loại phòng:</strong> {{ $roomType->name }}</p>
    <p><strong>Mô tả:</strong> {{ $roomType->description }}</p>
    <p><strong>Số khách tối đa:</strong> {{ $roomType->max_guests }}</p>
    <p><strong>Số lượng phòng (total units):</strong> {{ $roomType->total_units }}</p>
    <p><strong>Giá / đêm:</strong> {{ number_format($roomType->price_per_night) }} đ</p>
    <p><strong>Trạng thái:</strong> {{ $roomType->status }}</p>

    <h3>Thuộc phòng (Room / Khách sạn)</h3>
    <p><strong>ID phòng:</strong> {{ $room->id }}</p>
    <p><strong>Tiêu đề:</strong> {{ $room->title }}</p>

    <h3>Tổng quan lịch phòng</h3>
    @if($roomType->calendars && $roomType->calendars->count())
        <p>Có {{ $roomType->calendars->count() }} ngày đã cấu hình riêng.</p>
        <ul>
            @foreach($roomType->calendars->sortBy('date')->take(10) as $calendar)
                <li>
                    {{ $calendar->date->format('d/m/Y') }} -
                    Giá:
                    @if(!is_null($calendar->price_per_night))
                        {{ number_format($calendar->price_per_night) }} đ
                    @else
                        (mặc định {{ number_format($roomType->price_per_night) }} đ)
                    @endif
                    -
                    Số phòng:
                    @if(!is_null($calendar->available_units))
                        {{ $calendar->available_units }}
                    @else
                        (mặc định {{ $roomType->total_units }})
                    @endif
                    -
                    Đóng: {{ $calendar->is_closed ? 'Có' : 'Không' }}
                </li>
            @endforeach
        </ul>
        @if($roomType->calendars->count() > 10)
            <p>... và còn {{ $roomType->calendars->count() - 10 }} ngày khác.</p>
        @endif
    @else
        <p>Chưa cấu hình lịch riêng, đang dùng giá & tồn kho mặc định.</p>
    @endif

    <h3>Tổng quan booking cho loại phòng này</h3>
    @if($roomType->bookings && $roomType->bookings->count())
        <p>Có {{ $roomType->bookings->count() }} booking.</p>
        <ul>
            @foreach($roomType->bookings->sortByDesc('created_at')->take(10) as $booking)
                <li>
                    #{{ $booking->id }} -
                    {{ $booking->check_in?->format('d/m/Y') }}
                    →
                    {{ $booking->check_out?->format('d/m/Y') }}
                    |
                    {{ $booking->guest_count }} khách
                    |
                    {{ number_format($booking->total_price) }} đ
                    |
                    {{ ucfirst($booking->status) }}
                </li>
            @endforeach
        </ul>
        <p>
            Xem chi tiết booking tại trang:
            <a href="{{ route('host.bookings.index') }}">Danh sách booking của host</a>
        </p>
    @else
        <p>Chưa có booking nào cho loại phòng này.</p>
    @endif
</x-app-layout>