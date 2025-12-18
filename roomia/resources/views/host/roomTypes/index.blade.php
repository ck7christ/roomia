{{-- resources/views/host/roomTypes/index.blade.php --}}
@extends('layouts.host')

@section('title', 'RoomsType - ' . config('app.name'))

@section('content')
    <div class="container py-4">
        {{-- Header + actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1"> {{ $room->title }}</h1>
                <p class="text-muted mb-0">
                    Quản lý các loại phòng (room types) cho khách sạn / phòng này.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('host.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Danh sách phòng
                </a>
                <a href="{{ route('host.rooms.show', $room) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-hotel me-1"></i> Xem phòng
                </a>
                <a href="{{ route('host.rooms.room-types.create', $room) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Tạo loại phòng
                </a>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Danh sách loại phòng --}}


        @if($roomTypes->count())
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Danh sách loại phòng</strong></span>
                    <span class="text-muted small">
                        Quản lý giá, tồn kho và lịch bán cho từng loại phòng.
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên loại phòng</th>
                                <th class="text-center">Số khách tối đa</th>
                                <th class="text-center">Số lượng phòng</th>
                                <th class="text-end">Giá / đêm</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Lịch phòng</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roomTypes as $roomType)
                                <tr>
                                    <td>{{ $roomType->id }}</td>
                                    <td class="fw-semibold">{{ $roomType->name }}</td>
                                    <td class="text-center">{{ $roomType->max_guests }}</td>
                                    <td class="text-center">{{ $roomType->total_units }}</td>
                                    <td class="text-end">
                                        {{ number_format($roomType->price_per_night) }} đ
                                    </td>
                                    <td class="text-center text-capitalize">
                                        @php $status = $roomType->status; @endphp
                                        <span
                                            class="badge
                                                                                                                                            @if($status === 'active') bg-success
                                                                                                                                            @elseif($status === 'inactive') bg-secondary
                                                                                                                                            @else bg-warning text-dark
                                                                                                                                            @endif">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('host.room-types.calendars.index', $roomType) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fa fa-calendar-alt me-1"></i> Lịch
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('host.rooms.room-types.edit', [$room, $roomType]) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('host.rooms.room-types.destroy', [$room, $roomType]) }}"
                                                method="POST" onsubmit="return confirm('Xóa loại phòng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        @else
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

                                    <a href="{{ route('host.rooms.room-types.create', $room) }}" class="btn btn-primary">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Tạo phòng đầu tiên
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </div>
@endsection