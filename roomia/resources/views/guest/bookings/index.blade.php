{{-- resources/views/guest/bookings/index.blade.php --}}
@extends('layouts.guest')

@section('title', 'Đặt phòng của tôi')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Đặt phòng của tôi</h1>
                <div class="text-muted small">Theo dõi lịch sử đặt phòng, thanh toán và trạng thái.</div>
            </div>

            <div class="d-flex gap-2">
                @if (\Illuminate\Support\Facades\Route::has('guest.rooms.index'))
                    <a class="btn btn-outline-primary" href="{{ route('guest.rooms.index') }}">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Tìm chỗ ở
                    </a>
                @endif
                <a class="btn btn-outline-secondary" href="{{ url('/') }}">
                    <i class="fa-solid fa-house me-2"></i> Trang chủ
                </a>
            </div>
        </div>

        @includeIf('partials.general.alerts')

        <div class="card">
            <div class="card-body p-0">
                @if ($bookings->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">Mã</th>
                                    <th>Chỗ ở</th>
                                    <th class="text-nowrap">Nhận phòng</th>
                                    <th class="text-nowrap">Trả phòng</th>
                                    <th class="text-nowrap">Khách</th>
                                    <th class="text-nowrap">Tổng tiền</th>
                                    <th class="text-nowrap">Trạng thái</th>
                                    <th class="text-end text-nowrap">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    @include('partials.guest.bookings.row', ['booking' => $booking])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        Bạn chưa có đặt phòng nào.
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
