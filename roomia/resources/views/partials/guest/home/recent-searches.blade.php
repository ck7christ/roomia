@php
    $items = ($recentSearches ?? collect());
@endphp

<div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="mb-0 fw-bold">TÌM KIẾM GẦN ĐÂY</h6>
</div>

@if($items->isEmpty())
    <div class="text-muted small mb-4">
        Bạn chưa có tìm kiếm gần đây.
    </div>
@else
    @php
        $routeName = null;
        foreach (['search.index', 'guest.rooms.index', 'guest.rooms.search', 'rooms.index'] as $name) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                $routeName = $name;
                break;
            }
        }
    @endphp

    <div class="row g-2 mb-4">
        @foreach($items->take(3) as $x)
            @php
                $params = [
                    'location' => $x['location'] ?? null,
                    'check_in' => $x['check_in'] ?? null,
                    'check_out' => $x['check_out'] ?? null,
                    'adults' => $x['adults'] ?? 2,
                    'children' => $x['children'] ?? 0,
                    'rooms' => $x['rooms'] ?? 1,
                ];

                $href = $routeName ? route($routeName, array_filter($params, fn($v) => $v !== null && $v !== '')) : '#';

                $dateLabel = $x['date_label'] ?? null;
                if (!$dateLabel) {
                    $dateLabel = trim(($x['check_in'] ?? '') . ' → ' . ($x['check_out'] ?? ''), ' →');
                }
            @endphp

            <div class="col-12 col-md-4">
                <a href="{{ $href }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <div
                                class="rounded bg-light border p-2 d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot text-muted"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold text-uppercase text-dark">
                                    {{ $x['location'] }}
                                </div>

                                <div class="text-muted small">
                                    @if($dateLabel)
                                        {{ $dateLabel }}
                                        <span class="mx-1">•</span>
                                    @endif
                                    {{ (int) ($x['adults'] ?? 2) }} NL
                                    <span class="mx-1">•</span>
                                    {{ (int) ($x['children'] ?? 0) }} TE
                                    <span class="mx-1">•</span>
                                    {{ (int) ($x['rooms'] ?? 1) }} phòng
                                </div>
                            </div>

                            <i class="fa-solid fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif