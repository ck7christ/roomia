@php $col = $col ?? 'col-12 col-md-6 col-xl-3'; @endphp

<div class="{{ $col }}">
    <div class="card h-100">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted small">{{ $title }}</div>
                <div class="fs-4 fw-semibold">{{ $value }}</div>
                @if(!empty($subtitle))
                    <div class="text-muted small mt-1">{{ $subtitle }}</div>
                @endif
            </div>
            <div class="fs-4 text-muted"><i class="{{ $icon }}"></i></div>
        </div>

        @if(!empty($href))
            <div class="card-footer bg-transparent">
                <a class="text-decoration-none" href="{{ $href }}">
                    Xem chi tiết <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>
</div>