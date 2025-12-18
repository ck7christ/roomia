@php

    $cities = collect($exploreCities ?? [])->values();

    $slides = $cities->take(6)->map(function ($c) {
        return [
            'name' => data_get($c, 'name', 'City'),
            'url' => data_get($c, 'url', '#'),
            'image' => asset(data_get($c, 'image', 'assets/images/placeholders/room-1.jpg')),
            'count' => (int) data_get($c, 'rooms_count', 0),
        ];
    })->values();

    $first = $slides->first() ?? [
        'name' => 'City',
        'url' => '#',
        'image' => asset('assets/images/placeholders/room-1.jpg'),
        'count' => 0,
    ];

    $allRoomsUrl = \Illuminate\Support\Facades\Route::has('rooms.index') ? route('rooms.index') : url('/rooms');
    $chips = $cities->take(5)->values();
@endphp

<section class="explore mb-4" id="explore-vietnam">
    {{-- background will be updated by JS --}}
    <img src="{{ $first['image'] }}" class="explore__bg" data-explore-bg alt="Explore Vietnam background">
    <div class="explore__veil"></div>

    <div class="explore__inner">
        <div class="row g-4 align-items-center">
            {{-- LEFT --}}
            <div class="col-lg-5">
                <div class="explore__panel">
                    <div class="explore__kicker">
                        <span class="explore__badge">
                            <i class="fa-solid fa-compass me-2"></i> EXPLORE VIỆT NAM
                        </span>
                        <span class="explore__spark" aria-hidden="true"></span>
                    </div>

                    <h3 class="explore__title mb-2">Bộ sưu tập thành phố du lịch</h3>
                    <p class="explore__desc mb-3">
                        Chọn điểm đến để xem chỗ ở theo <span class="fw-semibold">thành phố / quận huyện</span>.
                        Gọn – nhanh – đúng nhu cầu.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ $allRoomsUrl }}" class="btn btn-warning explore__btn roomia-no-drag">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Xem tất cả
                        </a>
                        <a href="#search" class="btn btn-outline-light explore__btn roomia-no-drag">
                            <i class="fa-regular fa-calendar me-2"></i> Đặt lịch
                        </a>
                    </div>



                    <div class="explore__tip mt-3">
                        <i class="fa-regular fa-lightbulb me-2"></i>
                        Mẹo: gõ “<span class="fw-semibold">Thành phố Phú Quốc</span>” hoặc “<span
                            class="fw-semibold">Phú Quốc</span>” đều ra kết quả.
                    </div>
                </div>
            </div>

            {{-- RIGHT (single poster rotator) --}}
            <div class="col-lg-7">
                <a href="{{ $first['url'] }}" class="explore__poster roomia-no-drag" data-explore-rotator
                    data-slides='@json($slides)'>
                    <div class="ratio ratio-16x9 explore__media">
                        <img class="explore__img" src="{{ $first['image'] }}" alt="{{ $first['name'] }}">
                    </div>

                    <div class="explore__overlay">
                        <div class="explore__top">
                            <span class="explore__pill">
                                <i class="fa-solid fa-location-dot me-1"></i> KHÁM PHÁ
                            </span>
                        </div>

                        <div class="explore__bottom">
                            <div class="explore__name" data-explore-name>{{ $first['name'] }}</div>
                        </div>
                    </div>
                </a>

                <div class="explore__dots mt-2" aria-hidden="true">
                    @for($i = 0; $i < min(6, $slides->count()); $i++)
                        <span class="explore__dotItem" data-explore-dot="{{ $i }}"></span>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>