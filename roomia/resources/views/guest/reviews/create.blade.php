{{-- resources/views/guest/reviews/create.blade.php --}}
@extends('layouts.guest')

@section('content')
    @include('guest.reviews.form', [
        'booking' => $booking,
        'review' => null,
        'canReview' => $canReview ?? null,
        'mode' => 'create',
    ])
@endsection
