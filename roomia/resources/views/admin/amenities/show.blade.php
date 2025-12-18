<x-app-layout>
    <h1>Chi tiết tiện nghi #{{ $amenity->id }}</h1>

    <p><strong>Tên:</strong> {{ $amenity->name }}</p>
    <p><strong>Mã:</strong> {{ $amenity->code }}</p>
    <p><strong>Nhóm:</strong> {{ $amenity->group }}</p>
    <p><strong>Icon class:</strong> {{ $amenity->icon_class }}</p>
    <p><strong>Hoạt động:</strong> {{ $amenity->is_active ? 'Đang dùng' : 'Tạm ẩn' }}</p>
    <p><strong>Thứ tự:</strong> {{ $amenity->sort_order }}</p>

    <p>
        <a href="{{ route('admin.amenities.edit', $amenity) }}">Sửa</a> |
        <a href="{{ route('admin.amenities.index') }}">Quay lại danh sách</a>
    </p>
</x-app-layout>