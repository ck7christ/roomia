{{-- resources/views/partials/guest/home/recent-searches.blade.php --}}
@php use Carbon\Carbon; @endphp

@if (!empty($recentSearches) && count($recentSearches))
    <div class="rs-wrap mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0 fw-bold text-uppercase small">Tìm kiếm gần đây</h6>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rs-btn" data-rs-target="#recentSearchTrack"
                    data-rs-dir="-1" aria-label="Trước">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rs-btn"
                    data-rs-target="#recentSearchTrack" data-rs-dir="1" aria-label="Sau">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div id="recentSearchTrack" class="row flex-nowrap g-3 rs-track">
            @foreach ($recentSearches as $s)
                @php
                    $params = [
                        'destination' => $s['location'] ?? null,
                        'check_in' => $s['check_in'] ?? null,
                        'check_out' => $s['check_out'] ?? null,
                        'adults' => (int) ($s['adults'] ?? 2),
                        'children' => (int) ($s['children'] ?? 0),
                        'rooms' => (int) ($s['rooms'] ?? 1),
                    ];

                    $checkIn = !empty($s['check_in']) ? Carbon::parse($s['check_in'])->format('d/m/Y') : null;
                    $checkOut = !empty($s['check_out']) ? Carbon::parse($s['check_out'])->format('d/m/Y') : null;
                    $adults = (int) ($s['adults'] ?? 2);
                    $children = (int) ($s['children'] ?? 0);
                    $rooms = (int) ($s['rooms'] ?? 1);
                @endphp

                <div class="col-10 col-md-6 col-lg-6 rs-slide">
                    <a href="{{ route('rooms.index', $params) }}"
                        class="card rs-card shadow-sm border-0 h-100 text-decoration-none">
                        <div class="card-body d-flex gap-3">
                            <div class="rs-icon flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-dark text-truncate">
                                            {{ $s['location'] ?? '---' }}
                                        </div>

                                        <div class="text-muted small">
                                            @if ($checkIn && $checkOut)
                                                {{ $checkIn }} <span class="mx-1">→</span> {{ $checkOut }}
                                            @else
                                                Chưa chọn ngày
                                            @endif
                                        </div>
                                    </div>

                                    <span class="text-muted mt-1">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                </div>

                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill text-bg-light border">{{ $adults }} NL</span>
                                    @if ($children > 0)
                                        <span class="badge rounded-pill text-bg-light border">{{ $children }}
                                            TE</span>
                                    @endif
                                    <span class="badge rounded-pill text-bg-light border">{{ $rooms }}
                                        phòng</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
