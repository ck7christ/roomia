{{-- resources/views/host/reviews/index.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h1 class="h3 mb-1">Đánh giá</h1>
                <p class="text-muted mb-0">Tổng hợp review từ khách cho các phòng của bạn.</p>
            </div>
        </div>

        @include('partials.general.flash-message')

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Reviews</strong>
                <span class="text-muted small">
                    Tổng:
                    {{ method_exists($reviews, 'total') ? $reviews->total() : $reviews->count() }}
                </span>
            </div>

            @if ($reviews->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">#</th>
                                <th style="width: 130px;" class="text-center">Rating</th>
                                <th>Nội dung</th>
                                <th>Khách</th>
                                <th>Phòng</th>
                                <th style="width: 120px;">Booking</th>
                                <th style="width: 160px;">Ngày tạo</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($reviews as $review)
                                @php
                                    $rating = (int) ($review->rating ?? 0);
                                    $rating = max(0, min(5, $rating));

                                    // cố gắng lấy thông tin liên quan nhưng không giả định bạn có relation nào cụ thể
                                    $booking = $review->booking ?? null;
                                    $guest = $review->guest ?? ($booking?->guest ?? null);
                                    $roomType = $review->roomType ?? ($booking?->roomType ?? null);
                                    $room = $review->room ?? ($roomType?->room ?? null);

                                    $content = $review->comment ?? $review->content ?? $review->review ?? null;
                                @endphp

                                <tr>
                                    <td class="fw-semibold">#{{ $review->id }}</td>

                                    <td class="text-center">
                                        <span class="me-1 fw-semibold">{{ $rating }}/5</span>
                                        <span class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </td>

                                    <td>
                                        @if ($content)
                                            <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($content, 120) }}</div>
                                            @if (strlen($content) > 120)
                                                <div class="text-muted small">
                                                    {{ \Illuminate\Support\Str::limit($content, 200) }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $guest?->name ?? '—' }}
                                        <div class="text-muted small">{{ $guest?->email }}</div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $room?->title ?? '—' }}</div>
                                        <div class="text-muted small">{{ $roomType?->name }}</div>
                                    </td>

                                    <td>
                                        @if ($booking)
                                            <span class="badge bg-light text-dark border">#{{ $booking->id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $review->created_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                @if (method_exists($reviews, 'links'))
                    <div class="card-footer">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="card-body">
                    <p class="text-muted mb-0">Chưa có đánh giá nào.</p>
                </div>
            @endif
        </div>

    </div>
@endsection