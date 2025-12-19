{{-- resources/views/guest/reviews/edit.blade.php --}}
@extends('layouts.guest')

@section('content')
    @include('guest.reviews.form', [
        'booking' => $booking,
        'review' => $review,
        'canReview' => $canReview ?? null,
        'mode' => 'edit',
    ])

    {{-- (Tuỳ chọn) Nút xoá review để đúng flow edit --}}
    @if (\Illuminate\Support\Facades\Route::has('guest.bookings.review.destroy'))
        <div class="container pb-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="text-muted small">
                        Xóa đánh giá sẽ không thể hoàn tác.
                    </div>

                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteReviewModal{{ $booking->id }}">
                        <i class="fa-solid fa-trash me-1"></i> Xóa đánh giá
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteReviewModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xóa đánh giá?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Hành động này không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form method="POST" action="{{ route('guest.bookings.review.destroy', $booking) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
