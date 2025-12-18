<x-app-layout>
    <h1>Danh sách tiện nghi</h1>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <p>
        <a href="{{ route('admin.amenities.create') }}">+ Thêm tiện nghi mới</a>
    </p>

    <table border="1" cellpadding="4" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mã</th>
                <th>Nhóm</th>
                <th>Icon</th>
                <th>Trạng thái</th>
                <th>Thứ tự</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($amenities as $amenity)
            <tr>
                <td>{{ $amenity->id }}</td>
                <td>{{ $amenity->name }}</td>
                <td>{{ $amenity->code }}</td>
                <td>{{ $amenity->group }}</td>
                <td>
                    @if($amenity->icon_class)
                        <i class="{{ $amenity->icon_class }}"></i>
                        ({{ $amenity->icon_class }})
                    @else
                        -
                    @endif
                </td>
                <td>{{ $amenity->is_active ? 'Đang dùng' : 'Tạm ẩn' }}</td>
                <td>{{ $amenity->sort_order }}</td>
                <td>
                    <a href="{{ route('admin.amenities.show', $amenity) }}">Xem</a> |
                    <a href="{{ route('admin.amenities.edit', $amenity) }}">Sửa</a> |
                    <form action="{{ route('admin.amenities.destroy', $amenity) }}"
                          method="POST"
                          onsubmit="return confirm('Xóa tiện nghi này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Chưa có tiện nghi nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-app-layout>
