<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherApplyController extends Controller
{
    //
    public function apply(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $code = strtoupper(trim((string) $data['code']));
        $subtotal = (float) $data['subtotal'];

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return back()->withErrors(['code' => 'Voucher không tồn tại.'])->withInput();
        }

        // Check điều kiện cơ bản (đúng kiểu field: type/value/min_subtotal/max_discount/usage_limit/per_user_limit)
        $now = now();

        if (property_exists($voucher, 'is_active') && !$voucher->is_active) {
            return back()->withErrors(['code' => 'Voucher đang bị tắt.'])->withInput();
        }

        if (!is_null($voucher->starts_at) && $now->lt($voucher->starts_at)) {
            return back()->withErrors(['code' => 'Voucher chưa đến thời gian áp dụng.'])->withInput();
        }

        if (!is_null($voucher->ends_at) && $now->gt($voucher->ends_at)) {
            return back()->withErrors(['code' => 'Voucher đã hết hạn.'])->withInput();
        }

        if (!is_null($voucher->min_subtotal) && $subtotal < (float) $voucher->min_subtotal) {
            return back()->withErrors(['code' => 'Chưa đạt giá trị tối thiểu để áp dụng voucher.'])->withInput();
        }

        if (!is_null($voucher->usage_limit) && (int) $voucher->used_count >= (int) $voucher->usage_limit) {
            return back()->withErrors(['code' => 'Voucher đã hết lượt sử dụng.'])->withInput();
        }

        if (auth()->check() && !is_null($voucher->per_user_limit)) {
            $usedByUser = VoucherRedemption::where('voucher_id', $voucher->id)
                ->where('user_id', auth()->id())
                ->count();

            if ($usedByUser >= (int) $voucher->per_user_limit) {
                return back()->withErrors(['code' => 'Bạn đã dùng voucher này đủ số lần cho phép.'])->withInput();
            }
        }

        // Preview discount (để hiển thị UI). Khi tạo booking sẽ tính lại trong transaction.
        $previewDiscount = $this->calculatePreviewDiscount($voucher, $subtotal);

        session()->put('voucher', [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'preview_subtotal' => $subtotal,
            'preview_discount' => $previewDiscount,
            'applied_at' => $now->toIsoString(),
        ]);

        // Backward compatible (nếu chỗ khác còn đọc key cũ)
        session()->put('voucher_code', $voucher->code);

        return back()->with('success', "Áp dụng voucher {$voucher->code} thành công.");
    }

    public function remove()
    {
        session()->forget('voucher');
        session()->forget('voucher_code');

        return back()->with('success', 'Đã gỡ voucher.');
    }
    // Tính trước voucher được giảm
    private function calculatePreviewDiscount(Voucher $voucher, float $subtotal): float
    {
        if ($subtotal <= 0)
            return 0.0;

        $type = (string) $voucher->type;
        $value = (float) $voucher->value;

        $discount = 0.0;

        if ($type === 'percent') {
            $discount = $subtotal * ($value / 100.0);
            if (!is_null($voucher->max_discount)) {
                $discount = min($discount, (float) $voucher->max_discount);
            }
        } else {
            $discount = $value;
        }

        $discount = min($discount, $subtotal);

        return (float) round(max(0, $discount), 2);
    }
}
