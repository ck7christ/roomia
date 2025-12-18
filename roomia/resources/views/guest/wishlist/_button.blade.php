{{-- resources/views/partials/guest/wishlist/_button.blade.php --}}
{{--
    NÚT WISHLIST (DÙNG CHUNG)
    - Input: $room (Room model)
    - Optional: $inWishlist (bool) -> nếu controller đã tính sẵn thì truyền vào để tránh query trong view
    - Nếu chưa truyền $inWishlist thì view sẽ fallback query (không khuyến nghị nếu list nhiều phòng).
--}}

@php
    // Fallback: nếu controller chưa truyền $inWishlist thì tự kiểm tra trong DB
    // (Lưu ý: sẽ tốn query nếu render nhiều room trong 1 trang)
    $inWishlist =
        $inWishlist ??
        (auth()->check()
            ? \App\Models\WishlistItem::where('user_id', auth()->id())
                ->where('room_id', $room->id)
                ->exists()
            : false);

    $hasLogin = \Illuminate\Support\Facades\Route::has('login');
@endphp

@if (!auth()->check())
    {{-- Người dùng chưa đăng nhập: không cho thao tác wishlist --}}
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">
            <i class="fa-regular fa-heart me-1"></i>
            Đăng Nhập Để Lưu Yêu Thích
        </span>

        @if ($hasLogin)
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                Đăng Nhập
            </a>
        @endif
    </div>
@else
    @if ($inWishlist)
        {{-- Đã có trong wishlist -> nút xoá (DELETE) --}}
        <form method="POST" action="{{ route('guest.wishlist.destroy', $room) }}" class="d-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fa-solid fa-heart-crack me-1"></i>
                Bỏ Yêu Thích
            </button>
        </form>
    @else
        {{-- Chưa có -> form thêm wishlist (POST) --}}
        <form method="POST" action="{{ route('guest.wishlist.store') }}" class="d-flex flex-column gap-2">
            @csrf

            {{-- room_id: bắt buộc để controller biết đang lưu phòng nào --}}
            <input type="hidden" name="room_id" value="{{ $room->id }}">

            {{-- Ghi chú tuỳ chọn --}}
            <div class="input-group input-group-sm">
                <span class="input-group-text">
                    <i class="fa-regular fa-note-sticky"></i>
                </span>
                <input type="text" class="form-control" name="note" id="wishlist_note_{{ $room->id }}"
                    placeholder="Ghi Chú (Tuỳ Chọn)" value="{{ old('note') }}">
            </div>

            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="fa-regular fa-heart me-1"></i>
                Thêm Vào Yêu Thích
            </button>
        </form>
    @endif
@endif
