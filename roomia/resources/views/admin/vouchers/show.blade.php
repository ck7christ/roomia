@extends('layouts.admin')
@section('title', 'Voucher ' . $voucher->code)

@section('content')
    <div class="py-4 px-3">
        @includeIf('partials.general.alerts')

        <div class="card">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div class="fw-semibold">{{ $voucher->code }}</div>
                @include('partials.admin.vouchers.status', ['voucher' => $voucher])
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Tên</div>
                        <div>{{ $voucher->name ?? '—' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Loại / Giá trị</div>
                        <div>{{ $voucher->type }} / {{ $voucher->value }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Min subtotal</div>
                        <div>{{ $voucher->min_subtotal ?? '—' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Max discount</div>
                        <div>{{ $voucher->max_discount ?? '—' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Usage</div>
                        <div>{{ $voucher->used_count }} @if($voucher->usage_limit)/ {{ $voucher->usage_limit }}@endif</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">Redemptions</div>
                        <div>{{ $voucher->redemptions_count }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Mô tả</div>
                        <div class="text-muted">{{ $voucher->description ?? '—' }}</div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.vouchers.edit', $voucher) }}">Sửa</a>
                    <a class="btn btn-outline-primary" href="{{ route('admin.vouchers.index') }}">Danh sách</a>
                </div>
            </div>
        </div>
    </div>
@endsection