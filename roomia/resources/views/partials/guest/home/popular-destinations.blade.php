<h6 class="fw-bold mb-1">ĐIỂM ĐẾN ĐANG THỊNH HÀNH</h6>
<div class="text-muted small mb-2">Du khách tìm kiếm về Việt Nam cũng quan tâm đặt chỗ ở những nơi này</div>

<div class="row g-3 mb-4">
    {{-- Ô Video YouTube (UI only) --}}
    <div class="explore__video ">
        <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
            <iframe src="https://www.youtube-nocookie.com/embed/OMEZsNBP3QE?autoplay=1&mute=1&playsinline=1&rel=0"
                title="Roomia video" allow="autoplay; encrypted-media; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    </div>
    @foreach ($popularDestinations ?? [] as $i => $d)
        @php
            $title = $d['name'] ?? 'Điểm đến';
            $img = asset($d['image'] ?? 'assets/images/placeholders/room-1.jpg');
            $url = $d['url'] ?? '#';
        @endphp
        <div class="col-12 col-md-3">
            <a href="{{ $url }}" class="dest-link d-block h-100">
                <div class="card dest-card h-100">
                    <div class="ratio ratio-16x9">
                        <img src="{{ $img }}" class="w-100 h-100 dest-img" alt="{{ $title }}">
                    </div>

                    <div class="card-img-overlay dest-overlay">
                        <div class="dest-overlay__content">
                            <h3 class="dest-title text-uppercase">{{ $title }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>