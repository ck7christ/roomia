{{-- resources/views/partials/guest/reviews/rating-input.blade.php --}}
@php
    $name = $name ?? 'rating';
    $selected = (int) ($selected ?? 5);
    $idPrefix = $idPrefix ?? 'rating';
@endphp

<div class="btn-group flex-wrap" role="group" aria-label="Rating">
    @for($i = 1; $i <= 5; $i++)
        <input
            type="radio"
            class="btn-check"
            name="{{ $name }}"
            id="{{ $idPrefix }}-{{ $i }}"
            value="{{ $i }}"
            {{ $selected === $i ? 'checked' : '' }}
        >
        <label class="btn btn-outline-warning" for="{{ $idPrefix }}-{{ $i }}">
            {{ $i }} <i class="fa-solid fa-star"></i>
        </label>
    @endfor
</div>
