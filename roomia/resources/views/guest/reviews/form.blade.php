{{-- resources/views/guest/reviews/form.blade.php --}}

@php
    use Carbon\Carbon;

    // Cho phép wrapper truyền $mode ('create'|'edit'), nếu không thì tự suy ra bằng $review
    $isEdit = ($mode ?? null) === 'edit' || !empty($review);

    $title = $isEdit ? 'Chỉnh sửa đánh giá' : 'Viết đánh giá';

    $action = $isEdit
        ? route('guest.bookings.review.update', $booking)
        : route('guest.bookings.review.store', $booking);

    $selectedRating = (int) old('rating', $review->rating ?? 5);
    $commentValue = old('comment', $review->comment ?? '');

    $nights = null;
    if (!empty($booking->check_in) && !empty($booking->check_out)) {
        $nights = Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out));
    }

    $roomTitle = optional(optional($booking->roomType)->room)->title;
    $roomTypeName = optional($booking->roomType)->name;

    // Nếu controller không truyền $canReview thì tự tính basic rule
    $canReview =
        $canReview ?? (filled($booking->check_out) ? Carbon::parse($booking->check_out)->endOfDay()->isPast() : false);
@endphp

<div class="py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1">{{ $title }}</h4>
            <div class="text-muted small">Booking #{{ $booking->id }}</div>
        </div>

        <a href="{{ route('guest.bookings.show', $booking) }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại booking
        </a>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Không thể tiếp tục:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('review_error'))
        <div class="alert alert-warning">{{ session('review_error') }}</div>
    @endif

    @if (session('review_success'))
        <div class="alert alert-success">{{ session('review_success') }}</div>
    @endif

    <div class="row g-3">
        {{-- Booking summary --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white rounded-top-4">
                    <div class="fw-semibold">
                        <i class="fa-solid fa-receipt me-1"></i> Thông tin booking
                    </div>
                </div>
                <div class="card-body">
                    <div class="fw-semibold">{{ $roomTypeName ?? 'Loại phòng' }}</div>
                    @if ($roomTitle)
                        <div class="text-muted small">{{ $roomTitle }}</div>
                    @endif

                    <hr>

                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="text-muted">Nhận phòng</div>
                            <div class="fw-semibold">
                                {{ $booking->check_in ? Carbon::parse($booking->check_in)->format('d/m/Y') : '—' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Trả phòng</div>
                            <div class="fw-semibold">
                                {{ $booking->check_out ? Carbon::parse($booking->check_out)->format('d/m/Y') : '—' }}
                            </div>
                        </div>

                        <div class="col-6 mt-2">
                            <div class="text-muted">Số đêm</div>
                            <div class="fw-semibold">{{ is_null($nights) ? '—' : $nights }}</div>
                        </div>
                        <div class="col-6 mt-2">
                            <div class="text-muted">Số khách</div>
                            <div class="fw-semibold">{{ $booking->guest_count ?? '—' }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="text-muted">Tổng tiền</div>
                            <div class="fw-bold">
                                {{ number_format((int) ($booking->total_price ?? 0), 0, ',', '.') }} ₫
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Review form --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white rounded-top-4 d-flex align-items-center justify-content-between">
                    <div class="fw-semibold">
                        <i class="fa-solid fa-star text-warning me-1"></i> Nội dung đánh giá
                    </div>
                    @if ($isEdit)
                        <span class="badge text-bg-success">Đang chỉnh sửa</span>
                    @endif
                </div>

                <div class="card-body">
                    @if (!$canReview)
                        <div class="alert alert-info mb-0">
                            Bạn chỉ có thể đánh giá sau khi đã trả phòng.
                        </div>
                    @else
                        <form method="POST" action="{{ $action }}">
                            @csrf
                            @if ($isEdit)
                                @method('PATCH')
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Số sao</label>
                                @include('partials.guest.reviews.rating-input', [
                                    'name' => 'rating',
                                    'selected' => $selectedRating,
                                    'idPrefix' => ($isEdit ? 'edit' : 'create') . '-bk' . $booking->id,
                                ])
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nhận xét (tuỳ chọn)</label>
                                <textarea name="comment" rows="5" class="form-control @error('comment') is-invalid @enderror"
                                    placeholder="Chia sẻ trải nghiệm của bạn...">{{ $commentValue }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-floppy-disk me-1"></i>
                                    {{ $isEdit ? 'Lưu thay đổi' : 'Gửi đánh giá' }}
                                </button>

                                <a class="btn btn-outline-secondary"
                                    href="{{ route('guest.bookings.show', $booking) }}">
                                    Hủy
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            @if ($isEdit)
                <div class="alert alert-light border mt-3 mb-0">
                    <div class="small text-muted">
                        * Bạn có thể cập nhật lại rating/nhận xét bất kỳ lúc nào.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
