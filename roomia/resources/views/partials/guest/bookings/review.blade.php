{{-- resources/views/partials/guest/bookings/review.blade.php --}}
@php
    use Carbon\Carbon;

    $review = $booking->review ?? null;

    $canReview =
        $canReview ?? (filled($booking->check_out) ? Carbon::parse($booking->check_out)->endOfDay()->isPast() : false);
@endphp

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white rounded-top-4 d-flex align-items-center justify-content-between">
        <div class="fw-semibold">
            <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá của bạn
        </div>

        @if ($review)
            <span class="badge text-bg-success">Đã đánh giá</span>
        @endif
    </div>

    <div class="card-body">
        @if (session('review_success'))
            <div class="alert alert-success">{{ session('review_success') }}</div>
        @endif
        @if (session('review_error'))
            <div class="alert alert-warning">{{ session('review_error') }}</div>
        @endif

        @if (!$canReview)
            <div class="alert alert-info mb-0">
                Bạn chỉ có thể đánh giá sau khi đã trả phòng.
            </div>
        @else
            @if (!$review)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="text-muted">
                        Bạn chưa đánh giá booking này.
                    </div>

                    <a href="{{ route('guest.bookings.review.create', $booking) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Viết đánh giá
                    </a>
                </div>
            @else
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-warning">
                        @for ($s = 1; $s <= 5; $s++)
                            <i class="fa-solid fa-star {{ $s <= (int) $review->rating ? '' : 'opacity-25' }}"></i>
                        @endfor
                    </div>
                    <span class="text-muted small">({{ (int) $review->rating }}/5)</span>
                </div>

                @if (!empty($review->comment))
                    <div class="text-muted">{{ $review->comment }}</div>
                @else
                    <div class="text-muted fst-italic">Không có nhận xét.</div>
                @endif

                <div class="text-muted small mt-2">
                    Cập nhật: {{ optional($review->updated_at)->format('d/m/Y H:i') }}
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('guest.bookings.review.edit', $booking) }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-pen me-1"></i> Chỉnh sửa
                    </a>

                    {{-- Xóa bằng Bootstrap modal (không inline JS) --}}
                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteReviewModal{{ $booking->id }}">
                        <i class="fa-solid fa-trash me-1"></i> Xóa
                    </button>
                </div>

                {{-- Modal confirm delete --}}
                <div class="modal fade" id="deleteReviewModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Xóa đánh giá?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Hành động này không thể hoàn tác.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Hủy
                                </button>
                                <form method="POST" action="{{ route('guest.bookings.review.destroy', $booking) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
