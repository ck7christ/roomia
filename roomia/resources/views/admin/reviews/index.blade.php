{{-- resources/views/admin/reviews/index.blade.php --}}
<x-app-layout>
    <h1>Quản lý review</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($reviews->isEmpty())
        <p>Chưa có review nào.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loại phòng</th>
                    <th>Phòng</th>
                    <th>Khách</th>
                    <th>Host</th>
                    <th>Điểm</th>
                    <th>Ngày</th>
                    <th>Nội dung</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($reviews as $review)
                    @php
                        $roomType = $review->roomType;
                        $room = $roomType?->room;
                    @endphp

                    <tr>
                        <td>{{ $review->id }}</td>

                        <td>{{ $roomType->name ?? 'N/A' }}</td>

                        <td>{{ $room->name ?? 'N/A' }}</td>

                        <td>{{ $review->guest->name ?? 'Ẩn danh' }}</td>

                        <td>{{ $review->host->name ?? 'N/A' }}</td>

                        <td>{{ $review->rating }}/5</td>

                        <td>{{ $review->created_at->format('d/m/Y') }}</td>

                        <td>{{ $review->comment ?? '' }}</td>

                        <td>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                                onsubmit="return confirm('Bạn có chắc muốn xoá review này?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit">
                                    Xoá
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reviews->links() }}
    @endif
</x-app-layout>