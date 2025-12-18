<h6 class="fw-bold mb-2">{{ $title ?? 'DANH SÁCH PHÒNG' }}</h6>

<div class="roomia-slider" data-roomia-slider>
    <button type="button" class="roomia-slider__btn roomia-slider__btn--prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <div class="roomia-slider__track">
        <div class="row g-3 flex-nowrap m-0">
            @forelse(($rooms ?? collect()) as $room)
                @php
                    $url = route('rooms.show', $room);

                    $coverPath = data_get($room, 'coverImage.file_path');
                    $firstPath = $room->images?->first()?->file_path;
                    $imgPath = $coverPath ?: $firstPath;

                    $img = $imgPath
                        ? asset('storage/' . ltrim($imgPath, '/'))
                        : asset('assets/images/placeholders/room-1.jpg');

                    $rating = (float) data_get($roomRatings, $room->id . '.avg', 0);
                    $count = (int) data_get($roomRatings, $room->id . '.count', 0);

                    $city =
                        data_get($room, 'address.city.name') ?? (data_get($room, 'address.city.city_name') ?? 'CITY');

                    $country =
                        data_get($room, 'address.country.name') ??
                        (data_get($room, 'address.country.country_name') ?? 'COUNTRY');

                    $isWished = in_array($room->id, $wishlistRoomIds ?? []);
                @endphp

                <div class="col-10 col-sm-6 col-md-4 col-lg-3 flex-shrink-0 roomia-slider__item roomia-reveal">
                    <div class="card h-100">
                        <a href="{{ $url }}" class="text-decoration-none roomia-no-drag">
                            <div class="ratio ratio-1x1">
                                <img src="{{ $img }}" class="w-100 h-100 roomia-img-cover" alt="room">
                            </div>
                        </a>

                        <div class="card-body">
                            <div class="fw-semibold">
                                <a href="{{ $url }}" class="text-decoration-none text-dark roomia-no-drag">
                                    {{ $room->title ?? ($room->name ?? 'NAME') }}
                                </a>
                            </div>

                            <div class="text-muted small">{{ $city }}, {{ $country }}</div>

                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div>
                                    <span class="badge text-bg-primary">{{ number_format($rating, 1) }}</span>
                                    <span class="text-muted small ms-1">RATING</span>
                                    <div class="text-muted small">{{ $count }} đánh giá</div>
                                </div>

                                {{-- Wishlist (nếu bạn đã làm guest.wishlist.*) --}}
                                @if (auth()->check())
                                    @if (!$isWished)
                                        <form action="{{ route('guest.wishlist.store') }}" method="POST"
                                            class="m-0 roomia-no-drag">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                                aria-label="Thêm vào wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('guest.wishlist.destroy', $room) }}" method="POST"
                                            class="m-0 roomia-no-drag">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                aria-label="Bỏ khỏi wishlist">
                                                <i class="fa-solid fa-heart"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="btn btn-outline-primary btn-sm roomia-no-drag"
                                        aria-label="Đăng nhập để lưu">
                                        <i class="fa-regular fa-heart"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- fallback demo --}}
                @for ($i = 0; $i < 4; $i++)
                    <div class="col-10 col-sm-6 col-md-4 col-lg-3 flex-shrink-0 roomia-slider__item roomia-reveal">
                        <div class="card h-100">
                            <div class="ratio ratio-1x1">
                                <img src="{{ asset('assets/images/placeholders/room-1.jpg') }}"
                                    class="w-100 h-100 roomia-img-cover" alt="room">
                            </div>
                            <div class="card-body">
                                <div class="fw-semibold">NAME</div>
                                <div class="text-muted small">CITY, COUNTRY</div>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>

    <button type="button" class="roomia-slider__btn roomia-slider__btn--next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="roomia-slider__progress" aria-hidden="true">
        <span></span>
    </div>
</div>
