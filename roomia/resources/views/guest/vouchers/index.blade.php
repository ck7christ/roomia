{{-- resources/views/guest/vouchers/index.blade.php --}}
@extends('layouts.guest')

@section('title', 'Mã Giảm Giá')

@section('content')
<div class="py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Voucher đang áp dụng</h1>
            <div class="text-muted small">Chỉ hiển thị voucher đang active và còn hiệu lực.</div>
        </div>

        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo mã/tên...">
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-magnifying-glass" title="Tìm"></i> 
            </button>
        </form>
    </div>

    <div class="row g-3">
        @forelse($vouchers as $v)
            @php
                $isPercent = ($v->type === \App\Models\Voucher::TYPE_PERCENT);
                $from = $v->starts_at ? $v->starts_at->format('d/m/Y') : null;
                $to = $v->ends_at ? $v->ends_at->format('d/m/Y') : null;

                $remaining = null;
                if (!is_null($v->usage_limit)) {
                    $remaining = max(0, (int)$v->usage_limit - (int)($v->used_count ?? 0));
                }
            @endphp

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card rm-card rm-animate rm-delay-1 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <div class="text-muted small">Mã</div>
                                <div class="h5 mb-1"><code>{{ $v->code }}</code></div>
                                <div class="fw-semibold">{{ $v->name }}</div>
                            </div>
                            <span class="badge text-bg-success">ACTIVE</span>
                        </div>

                        @if($v->description)
                            <div class="text-muted small mt-2">{{ $v->description }}</div>
                        @endif

                        <div class="mt-3 small">
                            <div>
                                Giảm:
                                <span class="fw-semibold">
                                    {{ $isPercent ? rtrim(rtrim(number_format((float)$v->value, 2, '.', ''), '0'), '.') . '%' : number_format((float)$v->value, 0, ',', '.') }}
                                </span>
                                @if($isPercent && !is_null($v->max_discount))
                                    <span class="text-muted">(tối đa {{ number_format((float)$v->max_discount, 0, ',', '.') }})</span>
                                @endif
                            </div>

                            @if(!is_null($v->min_subtotal))
                                <div class="text-muted">Đơn tối thiểu: {{ number_format((float)$v->min_subtotal, 0, ',', '.') }}</div>
                            @endif

                            @if($from || $to)
                                <div class="text-muted">Hiệu lực: {{ $from ?? '—' }} → {{ $to ?? '—' }}</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">Hiện chưa có voucher active.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
