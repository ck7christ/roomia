<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $q = $request->query('q');

        $vouchers = Voucher::query()
            ->when($q, fn($qr) => $qr->where('code', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.vouchers.create', [
            'voucher' => new Voucher(),
            'types' => Voucher::TYPES,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $this->validateVoucher($request);

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
        return view('admin.vouchers.show', [
            'voucher' => $voucher->loadCount('redemptions'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
        return view('admin.vouchers.edit', [
            'voucher' => $voucher,
            'types' => Voucher::TYPES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
        $data = $this->validateVoucher($request, $voucher->id);
        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
        $voucher->delete();
        return back()->with('success', 'Đã xóa voucher.');
    }
    private function validateVoucher(Request $request, ?int $ignoreId = null): array
    {
        $unique = 'unique:vouchers,code' . ($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            'code' => ['required', 'string', 'max:50', $unique],
            'name' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],

            'type' => ['required', 'in:' . implode(',', Voucher::TYPES)],
            'value' => ['required', 'numeric', 'min:0'],

            'min_subtotal' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],

            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],

            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ], [], [
            'min_subtotal' => 'giá trị tối thiểu',
            'max_discount' => 'giảm tối đa',
            'usage_limit' => 'giới hạn lượt dùng',
            'per_user_limit' => 'giới hạn mỗi user',
        ]);
    }
}
