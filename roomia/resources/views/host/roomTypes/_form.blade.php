{{-- resources/views/host/roomTypes/_form.blade.php --}}
@csrf

<div class="row g-3">

    {{-- Tên loại phòng --}}
    <div class="col-12">
        <label class="form-label">Tên loại phòng</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $roomType->name ?? '') }}" placeholder="Ví dụ: Deluxe Double, Superior Twin...">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Mô tả --}}
    <div class="col-12">
        <label class="form-label">Mô tả</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
            placeholder="Nhập mô tả ngắn gọn về loại phòng...">{{ old('description', $roomType->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Số khách tối đa --}}
    <div class="col-md-3">
        <label class="form-label">Số khách tối đa</label>
        <input type="number" name="max_guests" min="1" class="form-control @error('max_guests') is-invalid @enderror"
            value="{{ old('max_guests', $roomType->max_guests ?? 1) }}">
        @error('max_guests')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Số lượng phòng --}}
    <div class="col-md-3">
        <label class="form-label">Số lượng phòng</label>
        <input type="number" name="total_units" min="1" class="form-control @error('total_units') is-invalid @enderror"
            value="{{ old('total_units', $roomType->total_units ?? 1) }}">
        @error('total_units')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Giá mỗi đêm --}}
    <div class="col-md-3">
        <label class="form-label">Giá mỗi đêm (VNĐ)</label>
        <input type="number" name="price_per_night" min="0"
            class="form-control @error('price_per_night') is-invalid @enderror"
            value="{{ old('price_per_night', $roomType->price_per_night ?? 0) }}">
        @error('price_per_night')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Trạng thái --}}
    <div class="col-md-3">
        <label class="form-label">Trạng thái</label>
        @php 
            $status = old('status', $roomType->status ?? 'draft'); 
        @endphp

        <select name="status"  class="form-select @error('status') is-invalid @enderror">
            <option value="draft" @selected($status == 'draft')>Nháp</option>
            <option value="active" @selected($status == 'active')>Đang mở bán</option>
            <option value="inactive" @selected($status == 'inactive')>Tạm dừng</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

{{-- Nút submit dùng chung --}}
<div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-save me-1"></i> 
        {{ isset($roomType) ? 'Cập nhật' : 'Tạo mới' }}
    </button>
</div>
