{{-- resources/views/guest/wishlist/index.blade.php --}}
{{--
    DANH SÁCH YÊU THÍCH (GUEST)
    - Dữ liệu mong đợi: $items (Collection/Paginator) gồm WishlistItem
    - Khuyến nghị Controller:
        $items = WishlistItem::with('room.address.city')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
--}}

@extends('layouts.guest')

@section('content')
    <div class="py-3">

        {{-- Tiêu đề trang --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Danh Sách Yêu Thích</h4>
                <div class="text-muted small">Các chỗ ở bạn đã lưu để xem lại sau.</div>
            </div>

            @php
                $hasRoomsIndex = \Illuminate\Support\Facades\Route::has('guest.rooms.index');
            @endphp

            @if ($hasRoomsIndex)
                <a href="{{ route('guest.rooms.index') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    Tìm Chỗ Ở
                </a>
            @endif
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-start gap-2">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @php
            $hasRoomShow = \Illuminate\Support\Facades\Route::has('guest.rooms.show');
        @endphp

        <div class="card rm-card">
            <div class="card-header bg-white">
                <div class="fw-semibold">
                    <i class="fa-regular fa-heart me-1"></i>
                    Danh Sách
                </div>
            </div>

            <div class="card-body">
                @if ($items->isEmpty())
                    {{-- Empty state --}}
                    <div class="text-center text-muted py-4">
                        Bạn Chưa Lưu Chỗ Ở Nào.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Chỗ Ở</th>
                                    <th style="width: 260px;">Ghi Chú</th>
                                    <th style="width: 170px;">Ngày Lưu</th>
                                    <th class="text-end" style="width: 180px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $room = $item->room;
                                        $roomTitle = $room->title ?? 'Chỗ Ở';
                                        $cityName = data_get($room, 'address.city.name');
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $roomTitle }}</div>
                                            @if ($cityName)
                                                <div class="text-muted small">
                                                    <i class="fa-solid fa-location-dot me-1"></i>
                                                    {{ $cityName }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-muted">
                                            {{ $item->note ?: '—' }}
                                        </td>

                                        <td class="text-muted small">
                                            {{ optional($item->created_at)->format('d/m/Y H:i') ?? '—' }}
                                        </td>

                                        <td class="text-end">
                                            <div class="d-inline-flex gap-2">
                                                {{-- Xem phòng --}}
                                                @if ($room && $hasRoomShow)
                                                    <a href="{{ route('guest.rooms.show', $room) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa-regular fa-eye" title="Xem"></i>
                                                    </a>
                                                @endif

                                                {{-- Xoá khỏi wishlist --}}
                                                @if ($room)
                                                    <form method="POST"
                                                        action="{{ route('guest.wishlist.destroy', $room) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa-solid fa-trash" title="Xóa"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang nếu dùng paginate() --}}
                    @if (method_exists($items, 'links'))
                        <div class="mt-3">
                            {{ $items->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>
@endsection
