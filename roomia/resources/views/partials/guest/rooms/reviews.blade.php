{{-- resources/views/partials/guest/room/reviews.blade.php --}}
@props(['reviews' => []])

@if (count($reviews))
    <ul class="list-unstyled">
        @foreach ($reviews as $review)
            <li>
                <div>
                    <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                    @if (isset($review->rating))
                        <span> • {{ $review->rating }}/5</span>
                    @endif
                </div>
                <div>{{ $review->comment ?? '' }}</div>
            </li>
        @endforeach
    </ul>
@else
    <div>Chưa có đánh giá.</div>
@endif