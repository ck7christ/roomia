@extends('layouts.admin')
@section('title', 'Quản lý Booking')

@section('content')
    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Bookings</h4>
        </div>

        {{-- Filter --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-lg-4">
                        <label class="form-label">Từ khóa</label>
                        <input type="text" name="q" class="form-control" placeholder="Tên/email khách hoặc tên phòng..."
                            value="{{ request('q') }}">
                    </div>

                    <div class="col-12 col-md-4 col-lg-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach(($statuses ?? []) as $st)
                                <option value="{{ $st }}" @selected(request('status') === $st)>
                                    {{ strtoupper($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>

                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Lọc
                        </button>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.bookings.index') }}">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- List --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="text-muted small">
                            <tr>
                                <th style="width: 90px;">#</th>
                                <th>Khách</th>
                                <th>Phòng</th>
                                <th>Ngày</th>
                                <th class="text-end">Tổng</th>
                                <th class="text-center">Trạng thái</th>
                                <th style="width: 120px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                @php
                                    $badge = match ($b->status) {
                                        \App\Models\Booking::STATUS_PENDING => 'warning',
                                        \App\Models\Booking::STATUS_CONFIRMED => 'primary',
                                        \App\Models\Booking::STATUS_COMPLETED => 'success',
                                        \App\Models\Booking::STATUS_CANCELLED => 'secondary',
                                        default => 'light',
                                    };

                                    $roomTitle = $b->roomType?->room?->title ?? '-';
                                    $roomTypeName = $b->roomType?->name ?? '-';
                                @endphp
                                <tr>
                                    <td class="fw-semibold">#{{ $b->id }}</td>

                                    <td>
                                        <div class="fw-semibold">{{ $b->guest?->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $b->guest?->email ?? '' }}</div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $roomTitle }}</div>
                                        <div class="text-muted small">{{ $roomTypeName }}</div>
                                    </td>

                                    <td>
                                        <div class="small">
                                            {{ optional($b->check_in)->format('d/m/Y') ?? '-' }}
                                            <span class="text-muted mx-1">→</span>
                                            {{ optional($b->check_out)->format('d/m/Y') ?? '-' }}
                                        </div>
                                        <div class="text-muted small">{{ (int) ($b->guest_count ?? 0) }} khách</div>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format((float) ($b->total_price ?? 0), 0, ',', '.') }} đ
                                    </td>

                                    <td class="text-center">
                                        <span class="badge text-bg-{{ $badge }}">
                                            {{ strtoupper($b->status ?? '-') }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Chưa có booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection