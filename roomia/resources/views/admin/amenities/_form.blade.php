{{-- resources/views/admin/amenities/_form.blade.php --}}
@php
    /**
     * Form dùng chung cho Create/Edit
     * - $amenity có thể là model hoặc null (create)
     */
    $amenity = $amenity ?? null;

    // Mặc định: đang hoạt động (1)
    $isActive = (int) old('is_active', $amenity?->is_active ?? 1);
@endphp

<div class="row g-3">
    {{-- Tên --}}
    <div class="col-12 col-md-6">
        <label for="name" class="form-label">
            Tên tiện nghi <span class="text-danger">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $amenity->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror" placeholder="Ví dụ: Wi‑Fi miễn phí"
            autocomplete="off">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Code --}}
    <div class="col-12 col-md-6">
        <label for="code" class="form-label">
            Mã (code) <span class="text-danger">*</span>
        </label>
        <input type="text" id="code" name="code" value="{{ old('code', $amenity->code ?? '') }}"
            class="form-control @error('code') is-invalid @enderror" placeholder="wifi, parking, pool..."
            autocomplete="off">
        <div class="form-text">Gợi ý: viết thường, không dấu, không khoảng trắng.</div>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Nhóm --}}
    <div class="col-12 col-md-6">
        <label for="group" class="form-label">Nhóm</label>
        <input type="text" id="group" name="group" value="{{ old('group', $amenity->group ?? '') }}"
            class="form-control @error('group') is-invalid @enderror" placeholder="Ví dụ: Cơ bản, An toàn, Giải trí..."
            autocomplete="off">
        @error('group')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Icon --}}
    <div class="col-12 col-md-6">
        <label for="icon_class" class="form-label">Icon (Font Awesome)</label>
        <input type="text" id="icon_class" name="icon_class" value="{{ old('icon_class', $amenity->icon_class ?? '') }}"
            class="form-control @error('icon_class') is-invalid @enderror" placeholder="fa-solid fa-wifi"
            autocomplete="off">
        <div class="form-text">Ví dụ: <code>fa-solid fa-wifi</code></div>
        @error('icon_class')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Thứ tự --}}
    <div class="col-12 col-md-6">
        <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
            value="{{ old('sort_order', $amenity->sort_order ?? 0) }}"
            class="form-control @error('sort_order') is-invalid @enderror">
        <div class="form-text">Số nhỏ sẽ hiển thị trước.</div>
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Trạng thái --}}
    <div class="col-12 col-md-6">
        <label class="form-label d-block">Trạng thái</label>

        {{-- Hidden đặt trước để khi bật switch sẽ override thành 1 --}}
        <input type="hidden" name="is_active" value="0">

        <div class="form-check form-switch">
            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch"
                id="is_active" name="is_active" value="1" @checked($isActive === 1)>
            <label class="form-check-label" for="is_active">Đang sử dụng</label>

            @error('is_active')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>