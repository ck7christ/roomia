{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    @php
        // Fallback để view không lỗi nếu controller chưa truyền data
        $stats = $stats ?? [];
        $recentBookings = $recentBookings ?? [];
        $recentUsers = $recentUsers ?? [];

        // URL fallback nếu route name chưa có
        $usersUrl = \Illuminate\Support\Facades\Route::has('admin.users.index')
            ? route('admin.users.index')
            : url('/admin/users');

        $roomsUrl = \Illuminate\Support\Facades\Route::has('admin.rooms.index')
            ? route('admin.rooms.index')
            : url('/admin/rooms');

        $bookingsUrl = \Illuminate\Support\Facades\Route::has('admin.bookings.index')
            ? route('admin.bookings.index')
            : url('/admin/bookings');

        $reportsUrl = \Illuminate\Support\Facades\Route::has('admin.reports.index')
            ? route('admin.reports.index')
            : url('/admin/reports');
    @endphp

    {{-- Layout admin đã có container-fluid rồi, ở đây chỉ cần padding --}}
    <div class="py-4 px-3">

        @include('partials.admin.header', compact('usersUrl', 'roomsUrl', 'bookingsUrl', 'reportsUrl'))

        {{-- Alerts chung (nếu có) --}}
        @includeIf('partials.general.alerts')

        @include('partials.admin.dashboard.stats', compact('stats'))

        <div class="row g-3">
            <div class="col-12 col-lg-8">
                @include('partials.admin.dashboard.recent-bookings', compact('recentBookings', 'bookingsUrl'))
            </div>

            <div class="col-12 col-lg-4">
                @include('partials.admin.dashboard.quick-actions', compact('usersUrl', 'roomsUrl', 'bookingsUrl', 'reportsUrl'))
                @include('partials.admin.dashboard.recent-users', compact('recentUsers'))
            </div>
        </div>

    </div>
@endsection