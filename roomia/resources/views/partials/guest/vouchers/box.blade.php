{{-- resources/views/partials/guest/voucher/box.blade.php --}}
@php
    /**
     * Partial voucher apply box cho trang checkout.
     * Yêu cầu:
     * - Truyền $subtotal (tổng tạm tính)
     * - Truyền $payButtonId (id của nút "Thanh toán"/"Tiếp tục")
     */
    $v = session('voucher');
    $code = data_get($v, 'code', session('voucher_code'));
    $previewDiscount = (float) data_get($v, 'preview_discount', 0);

    $subtotalValue = (float) ($subtotal ?? 0);
    $payablePreview = max(0, $subtotalValue - $previewDiscount);

    $applied = !empty($code);
    $payButtonId = $payButtonId ?? 'btnCheckoutPay';
@endphp

<div class="card rm-card" id="voucherBox" data-voucher-applied="{{ $applied ? 1 : 0 }}"
    data-pay-button-id="{{ $payButtonId }}">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-ticket me-2 text-accent"></i>MÃ GIẢM GIÁ
    </div>

    <div class="card-body">
        @if($applied)
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="fw-semibold">{{ $code }}</div>

                    @if($previewDiscount > 0)
                        <div class="text-muted small">
                            Giảm tạm tính: {{ number_format($previewDiscount, 0, ',', '.') }}
                            • Còn: {{ number_format($payablePreview, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-muted small">
                            Voucher đang được áp dụng. (Sẽ được kiểm tra lại khi tạo booking)
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('guest.vouchers.remove') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit">Gỡ</button>
                </form>
            </div>
        @else
            <form method="POST" action="{{ route('guest.vouchers.apply') }}" class="row g-2" id="voucherApplyForm">
                @csrf

                <div class="col-12 mb-2">
                    <input id="voucherCodeInput" name="code" value="{{ old('code') }}"
                        class="form-control @error('code') is-invalid @enderror" placeholder="Nhập mã voucher">
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <input type="hidden" name="subtotal" value="{{ $subtotalValue }}">
                    <button class="btn btn-primary w-100" type="submit">
                        Áp dụng
                    </button>
                </div>
            </form>

            <div class="text-muted small mt-2 d-none" id="voucherGateHint">
                Bạn đã nhập mã voucher. Hãy bấm <b>Áp dụng</b> để tiếp tục thanh toán.
            </div>
        @endif
    </div>
</div>