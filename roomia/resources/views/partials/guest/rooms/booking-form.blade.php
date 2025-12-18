<form method="POST" action="{{ $action }}" class="row g-2">
    @csrf

    <div class="col-12 col-md-4">
        <label class="form-label mb-1">Nhận phòng</label>
        <input type="date" name="check_in" value="{{ old('check_in') }}"
            class="form-control @error('check_in') is-invalid @enderror">
        @error('check_in')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label mb-1">Trả phòng</label>
        <input type="date" name="check_out" value="{{ old('check_out') }}"
            class="form-control @error('check_out') is-invalid @enderror">
        @error('check_out')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label mb-1">Số khách</label>
        <input type="number" min="1" name="guest_count" value="{{ old('guest_count', 1) }}"
            class="form-control @error('guest_count') is-invalid @enderror">
        @error('guest_count')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label mb-1">Voucher (tuỳ chọn)</label>
        <input type="text" name="voucher_code" value="{{ old('voucher_code') }}"
            class="form-control @error('voucher_code') is-invalid @enderror" placeholder="Nhập mã voucher nếu có">
        @error('voucher_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 d-grid">
        <button class="btn btn-success" type="submit">
            <i class="fa-solid fa-arrow-right me-1"></i> Tiếp tục thanh toán
        </button>
    </div>
</form>
