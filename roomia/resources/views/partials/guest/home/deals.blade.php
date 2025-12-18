<h6 class="fw-bold mb-2">ƯU ĐÃI</h6>
<div class="text-muted small mb-2">Khuyến mãi, giảm giá và ưu đãi đặc biệt dành riêng cho bạn</div>

<div class="row g-3 mb-4">
    @foreach(($deals ?? []) as $d)
        <div class="col-12 col-lg-6">
            <div class="card text-bg-dark">
                <img src="{{ asset($d['image']) }}" class="card-img" alt="deal">
                <div class="card-img-overlay d-flex flex-column justify-content-end">
                    <div class="badge text-bg-light text-dark mb-2 align-self-start">{{ $d['badge'] }}</div>
                    <h5 class="card-title fw-bold mb-1">{{ $d['title'] }}</h5>
                    <p class="card-text small mb-2">{{ $d['desc'] }}</p>
                    <a class="btn btn-light btn-sm align-self-start" href="{{ $d['href'] }}">{{ $d['cta'] }}</a>
                </div>
            </div>
        </div>
    @endforeach
</div>