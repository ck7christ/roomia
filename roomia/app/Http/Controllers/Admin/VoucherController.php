<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $q = Voucher::query();

        if ($request->filled('q')) {
            $kw = trim($request->q);
            $q->where(function ($x) use ($kw) {
                $x->where('code', 'like', "%{$kw}%")
                    ->orWhere('name', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $q->where('is_active', (bool) $request->is_active);
        }

        $vouchers = $q->latest()->paginate(20)->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $this->validateData($request);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        // rule logic theo type
        if ($data['type'] === 'fixed') {
            $data['max_discount'] = null;
        }

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
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
        $data = $this->validateData($request, $voucher);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        if ($data['type'] === 'fixed') {
            $data['max_discount'] = null;
        }

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
        return redirect()->route('admin.vouchers.index')->with('success', 'Xoá voucher thành công.');
    }
    private function validateData(Request $request, ?Voucher $voucher = null): array
    {
        $id = $voucher?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/',
                Rule::unique('vouchers', 'code')->ignore($id),
            ],

            'type' => ['required', Rule::in(Voucher::TYPES)],
            'value' => [
                'required',
                'numeric',
                'gt:0',
                function ($attr, $val, $fail) use ($request) {
                    if ($request->input('type') === 'percent' && $val > 100) {
                        $fail('Giá trị % không được vượt quá 100.');
                    }
                },
            ],

            'min_subtotal' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],

            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],

            // checkbox: vẫn validate được nếu bạn dùng boolean() ở store/update
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
