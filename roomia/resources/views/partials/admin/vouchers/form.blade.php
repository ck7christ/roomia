@php
    $voucher = $voucher ?? new \App\Models\Voucher();
    $types = $types ?? \App\Models\Voucher::TYPES;
@endphp

@csrf

<div class="row g-3">
    <div class="col-12 col-md-4">
        <label class="form-label">Code</label>
        <input name="code" value="{{ old('code', $voucher->code) }}"
            class="form-control @error('code') is-invalid @enderror">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Loại</label>
        <select name="type" class="form-select @error('type') is-invalid @enderror">
            @foreach ($types as $t)
                <option value="{{ $t }}" @selected(old('type', $voucher->type) === $t)>{{ $t }}</option>
            @endforeach
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Giá trị</label>
        <input name="value" type="number" step="0.01" value="{{ old('value', $voucher->value) }}"
            class="form-control @error('value') is-invalid @enderror">
        @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">percent = %, fixed = số tiền giảm.</div>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Tên (tuỳ chọn)</label>
        <input name="name" value="{{ old('name', $voucher->name) }}"
            class="form-control @error('name') is-invalid @enderror">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Trạng thái</label>
        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" @selected(old('is_active', (int) $voucher->is_active) === 1)>Active</option>
            <option value="0" @selected(old('is_active', (int) $voucher->is_active) === 0)>Inactive</option>
        </select>
        @error('is_active')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Mô tả (tuỳ chọn)</label>
        <textarea name="description" rows="3"
            class="form-control @error('description') is-invalid @enderror">{{ old('description', $voucher->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Min subtotal</label>
        <input name="min_subtotal" type="number" step="0.01" value="{{ old('min_subtotal', $voucher->min_subtotal) }}"
            class="form-control @error('min_subtotal') is-invalid @enderror">
        @error('min_subtotal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Max discount (cho percent)</label>
        <input name="max_discount" type="number" step="0.01" value="{{ old('max_discount', $voucher->max_discount) }}"
            class="form-control @error('max_discount') is-invalid @enderror">
        @error('max_discount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Usage limit</label>
        <input name="usage_limit" type="number" value="{{ old('usage_limit', $voucher->usage_limit) }}"
            class="form-control @error('usage_limit') is-invalid @enderror">
        @error('usage_limit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Per user limit</label>
        <input name="per_user_limit" type="number" value="{{ old('per_user_limit', $voucher->per_user_limit) }}"
            class="form-control @error('per_user_limit') is-invalid @enderror">
        @error('per_user_limit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Starts at</label>
        <input name="starts_at" type="datetime-local"
            value="{{ old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i')) }}"
            class="form-control @error('starts_at') is-invalid @enderror">
        @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Ends at</label>
        <input name="ends_at" type="datetime-local"
            value="{{ old('ends_at', $voucher->ends_at?->format('Y-m-d\TH:i')) }}"
            class="form-control @error('ends_at') is-invalid @enderror">
        @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">
        <i class="fa-solid fa-save me-1"></i> Lưu
    </button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.vouchers.index') }}">Quay lại</a>
</div>