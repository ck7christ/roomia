<form method="POST" action="{{ $action }}" class="row g-2">
    @csrf

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger small mb-0">
                <div class="fw-semibold mb-1">Không thể tiếp tục:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="col-12">
            <div class="alert alert-warning small mb-0">{{ session('error') }}</div>
        </div>
    @endif

    @isset($roomTypeId)
        <input type="hidden" name="room_type_id" value="{{ $roomTypeId }}">
    @endisset
    <div class="col-12 col-md-6">
        <div class="col-12 ">
            <label class="form-label mb-1">Nhận Phòng</label>
            <input type="date" name="check_in" value="{{ old('check_in') }}"
                class="form-control @error('check_in') is-invalid @enderror">
            @error('check_in')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 ">
            <label class="form-label mb-1">Trả Phòng</label>
            <input type="date" name="check_out" value="{{ old('check_out') }}"
                class="form-control @error('check_out') is-invalid @enderror">
            @error('check_out')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 ">
            <label class="form-label mb-1">Số Khách</label>
            <input type="number" min="1" name="guest_count" value="{{ old('guest_count', 1) }}"
                class="form-control @error('guest_count') is-invalid @enderror">
            @error('guest_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-12 col-md-6">
        @include('partials.guest.vouchers.box', [
            'subtotal' => $subtotal ?? 0,
            'payButtonId' => 'btnCheckoutPay'
        ])
    </div>

    <div class="row mt-3">
        <button id="btnCheckoutPay" class="btn btn-success" type="submit">
            <i class="fa-solid fa-arrow-right me-1"></i> Tiếp tục thanh toán
        </button>
    </div>
</form>