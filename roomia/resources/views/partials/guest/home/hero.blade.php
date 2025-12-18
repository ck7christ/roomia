@php
    $slides = [
        [
            'image' => 'assets/images/hero/hero-1.jpg',
            'title' => 'KHÁM PHÁ KỲ NGHỈ HOÀN HẢO',
            'subtitle' => 'Hơn 500+ phòng nghỉ cao cấp tại Việt Nam',
            'cta' => 'ĐẶT NGAY',
        ],
        [
            'image' => 'assets/images/hero/hero-2.jpg',
            'title' => 'ƯU ĐÃI ĐẶC BIỆT MỖI NGÀY',
            'subtitle' => 'Giảm giá theo mùa • Deal độc quyền cho bạn',
            'cta' => 'XEM ƯU ĐÃI',
        ],
        [
            'image' => 'assets/images/hero/hero-3.jpg',
            'title' => 'TRẢI NGHIỆM NHƯ NGƯỜI BẢN ĐỊA',
            'subtitle' => 'Homestay • Villa • Resort • Hotel',
            'cta' => 'KHÁM PHÁ',
        ],
    ];
@endphp

<div class="hero-banner mb-3">
    <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel"
        data-bs-interval="5200" data-bs-pause="hover">

        <div class="carousel-indicators">
            @foreach($slides as $idx => $s)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $idx }}"
                    class="{{ $idx === 0 ? 'active' : '' }}" aria-current="{{ $idx === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $idx + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($slides as $idx => $s)
                <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                    <img src="{{ asset($s['image']) }}" class="hero-slide-img" alt="Hero {{ $idx + 1 }}">

                    <div class="hero-overlay"></div>

                    <div class="carousel-caption hero-caption text-start">
                        <h1 class="hero-title">{{ $s['title'] }}</h1>
                        <p class="hero-subtitle">{{ $s['subtitle'] }}</p>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-light btn-sm hero-scroll">
                                {{ $s['cta'] }}
                            </button>
                            <a class="btn btn-outline-light btn-sm"
                                href="{{ Route::has('guest.rooms.index') ? route('guest.rooms.index') : '#' }}">
                                Xem chỗ ở
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Prev</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>