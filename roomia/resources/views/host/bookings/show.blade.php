{{-- resources/views/host/bookings/show.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        @php
            $roomType = $booking->roomType;
            $room = $roomType?->room;
            $guest = $booking->guest;

            $status = strtolower($booking->status ?? 'pending');
            $badge = match ($status) {
                'confirmed' => 'bg-success',
                'cancelled' => 'bg-secondary',
                'rejected' => 'bg-danger',
                default => 'bg-warning text-dark',
            };
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Chi tiết booking #{{ $booking->id }}</h1>
                <p class="text-muted mb-0">
                    Trạng thái:
                    <span class="badge {{ $badge }}">{{ ucfirst($status) }}</span>
                </p>
            </div>

            <a href="{{ route('host.bookings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Có lỗi xảy ra:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">

            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header"><strong>Thông tin phòng</strong></div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Khách sạn</dt>
                            <dd class="col-sm-8">{{ $room?->title ?? 'Không tìm thấy thông tin phòng.' }}</dd>

                            <dt class="col-sm-4">Loại phòng</dt>
                            <dd class="col-sm-8">{{ $roomType?->name ?? '—' }}</dd>

                            @if ($roomType)
                                <dt class="col-sm-4">Giá / đêm (hiện tại)</dt>
                                <dd class="col-sm-8">{{ number_format($roomType->price_per_night) }} đ</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header"><strong>Thời gian & số khách</strong></div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Ngày nhận phòng</dt>
                            <dd class="col-sm-8">{{ $booking->check_in?->format('d/m/Y') ?? '—' }}</dd>

                            <dt class="col-sm-4">Ngày trả phòng</dt>
                            <dd class="col-sm-8">{{ $booking->check_out?->format('d/m/Y') ?? '—' }}</dd>

                            <dt class="col-sm-4">Số khách</dt>
                            <dd class="col-sm-8">{{ $booking->guest_count ?? 0 }}</dd>

                            <dt class="col-sm-4">Tổng tiền</dt>
                            <dd class="col-sm-8">{{ number_format($booking->total_price ?? 0) }} đ</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm mb-3">
                    <div class="card-header"><strong>Khách đặt phòng</strong></div>
                    <div class="card-body">
                        @if ($guest)
                            <div class="fw-semibold">{{ $guest->name }}</div>
                            <div class="text-muted">{{ $guest->email }}</div>
                        @else
                            <p class="text-muted mb-0">Không có thông tin khách.</p>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header"><strong>Cập nhật trạng thái</strong></div>
                    <div class="card-body">
                        <form action="{{ route('host.bookings.update', $booking) }}" method="POST" class="js-confirm"
                            data-confirm="Bạn chắc chắn muốn cập nhật trạng thái booking #{{ $booking->id }}?">
                            @csrf
                            @method('PATCH')

                            @php $selected = old('status', $booking->status); @endphp

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="pending" @selected($selected == 'pending')>Pending</option>
                                    <option value="confirmed" @selected($selected == 'confirmed')>Confirmed</option>
                                    <option value="cancelled" @selected($selected == 'cancelled')>Cancelled</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-save me-1"></i> Lưu
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection