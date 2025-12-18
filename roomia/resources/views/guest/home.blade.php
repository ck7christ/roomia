@extends('layouts.guest')

@section('content')
    <div class="container py-3">

        @include('partials.guest.home.hero')
        @include('partials.guest.home.search')

        @include('partials.guest.home.recent-searches')
        @include('partials.guest.home.featured-stays')

        @include('partials.guest.home.why-roomia')
        @include('partials.guest.home.deals')

        @include('partials.guest.home.popular-destinations')
        @include('partials.guest.home.collections')
        @include('partials.guest.home.explore-vietnam')

    </div>
@endsection