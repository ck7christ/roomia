{{-- resources/views/host/rooms/index.blade.php --}}
@extends('layouts.host')

@section('title', 'Rooms - ' . config('app.name'))

@section('content')
    <div class="container py-4">

        @include('partials.general.flash-message')
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Quản Lí Khách Sạn</h1>
                <p class="text-muted mb-0">Danh sách khách sạn của bạn.</p>
            </div>

            <a href="{{ route('host.rooms.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i>
                Tạo phòng mới
            </a>
        </div>
        @if ($rooms->count())

            {{-- Có phòng: hiển thị bảng --}}
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">ID</th>
                                    <th class="text-nowrap">Tên phòng / khách sạn</th>
                                    <th class="text-nowrap">Địa chỉ</th>
                                    <th class="text-nowrap">Trạng thái</th>
                                    <th class="text-nowrap">Số loại phòng</th>
                                    <th class="text-end text-nowrap">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                    @php $addr = $room->address ?? null; @endphp
                                    <tr>
                                        <td>{{ $room->id }}</td>
                                        <td class="fw-semibold">{{ $room->title }}</td>
                                        <td class="small">
                                            @if ($addr)
                                                {{ $addr->street ?? '' }}
                                                @if (!empty($addr->district?->name))
                                                    , {{ $addr->district->name }}
                                                @endif
                                                @if (!empty($addr->city?->name))
                                                    , {{ $addr->city->name }}
                                                @endif
                                                @if (!empty($addr->country?->name))
                                                    , {{ $addr->country->name }}
                                                @endif
                                            @else
                                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $status = $room->status ?? 'draft';
                                                $badgeClass =
                                                    [
                                                        'active' => 'bg-success',
                                                        'inactive' => 'bg-secondary',
                                                        'draft' => 'bg-warning text-dark',
                                                    ][$status] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-capitalize">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td>{{ $room->roomTypes->count() }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- XEM -->
                                                <form action="{{ route('host.rooms.show', $room) }}" method="GET">
                                                    <button type="submit" class="btn btn-outline-secondary me-1"
                                                        data-bs-toggle="tooltip" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form>

                                                <!-- SỬA -->
                                                <form action="{{ route('host.rooms.edit', $room) }}" method="GET">
                                                    <button type="submit" class="btn btn-outline-primary me-1"
                                                        data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i> </button>
                                                </form>

                                                <!-- LOẠI PHÒNG -->
                                                <form action="{{ route('host.rooms.room-types.index', $room) }}" method="GET">
                                                    <button type="submit" class="btn btn-outline-info me-1" data-bs-toggle="tooltip"
                                                        title="Quản lý loại phòng">

                                                        <i class="fas fa-layer-group"></i>

                                                    </button>
                                                </form>

                                                <!-- XÓA -->
                                                <form action="{{ route('host.rooms.destroy', $room) }}" method="POST"
                                                    onsubmit="return confirm('Bạn chắc chắn muốn xóa phòng này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip"
                                                        title="Xóa phòng">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if (method_exists($rooms, 'links'))
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-end">
                            {{ $rooms->links() }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- KHÔNG có phòng: empty state đẹp hơn --}}
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">

                            <div class="mb-3">
                                <i class="fa-solid fa-bed fa-2x text-muted"></i>
                            </div>

                            <h2 class="h5 mb-2">
                                Bạn chưa tạo phòng nào
                            </h2>

                            <p class="text-muted mb-4 small">
                                Hãy tạo phòng đầu tiên để bắt đầu đón khách trên Roomia.
                                Bạn có thể thêm nhiều loại phòng, giá theo ngày và lịch nhận phòng sau này.
                            </p>

                            <a href="{{ route('host.rooms.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus me-1"></i>
                                Tạo phòng đầu tiên
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>
@endsection