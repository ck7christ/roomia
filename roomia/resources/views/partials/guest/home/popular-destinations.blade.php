<h6 class="fw-bold mb-1">ĐIỂM ĐẾN ĐANG THỊNH HÀNH</h6>
<div class="text-muted small mb-2">Du khách tìm kiếm về Việt Nam cũng quan tâm đặt chỗ ở những nơi này</div>

<div class="row g-3 mb-4">
    @foreach(($popularDestinations ?? []) as $i => $d)
        <div class="col-12 {{ $i < 2 ? 'col-md-6' : 'col-md-4' }}">
            <div class="card dest-card">
                <img src="{{ asset($d['image']) }}" class="card-img" alt="dest">
                <div class="card-img-overlay">
                    <h3 class="text-uppercase">{{ $d['name'] }}</h3>
                </div>
            </div>
        </div>
    @endforeach
</div>