<?php

namespace App\Http\Controllers\Guest;

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
        $q = Voucher::query()
            ->where('is_active', 1)
            ->where(function ($x) {
                $x->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($x) {
                $x->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($x) {
                // còn lượt dùng: usage_limit null OR used_count < usage_limit
                $x->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            });

        if ($request->filled('q')) {
            $kw = trim($request->q);
            $q->where(function ($x) use ($kw) {
                $x->where('code', 'like', "%{$kw}%")
                  ->orWhere('name', 'like', "%{$kw}%")
                  ->orWhere('description', 'like', "%{$kw}%");
            });
        }

        $vouchers = $q->latest('id')->paginate(12)->withQueryString();

        return view('guest.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
