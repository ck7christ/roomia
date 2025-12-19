{{-- resources/views/host/dashboard.blade.php --}}
@extends('layouts.host')

@section('title', 'Host Dashboard - ' . config('app.name'))

@section('content')
    <div class="container py-3">

        @include('partials.host.dashboard.header')
        @include('partials.host.dashboard.quick-actions')
        @include('partials.host.dashboard.stats')
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-7">
                @include('partials.host.dashboard.revenue-monthly')
            </div>
            <div class="col-12 col-xl-5">
                @include('partials.host.dashboard.status-summary')
            </div>
        </div>
        
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-7">
                @include('partials.host.dashboard.recent-bookings')
            </div>
            <div class="col-12 col-xl-5">
                @include('partials.host.dashboard.upcoming')
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-7">
                @include('partials.host.dashboard.top-roomtypes')
            </div>
            <div class="col-12 col-xl-5">
                @include('partials.host.dashboard.latest-reviews')
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12">
                @include('partials.host.dashboard.calendar-health')
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js') }}" defer></script>
    <script src="{{ asset('assets/js/host-revenue-chart.js') }}" defer></script>
    <script src="{{ asset('assets/js/host-booking-status-chart.js') }}" defer></script>
@endpush