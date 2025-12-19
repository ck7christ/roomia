{{-- resources/views/host/rooms/show.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        {{-- Header + actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">{{ $room->title }}</h1>
                <p class="text-muted mb-0">
                    Thông tin chi tiết phòng / khách sạn của bạn.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('host.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Danh sách phòng
                </a>

                <a href="{{ route('host.rooms.edit', $room) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Thông tin + Hình ảnh --}}
        <div class="row g-3">

            {{-- Thông tin cơ bản --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>Thông tin phòng / khách sạn</strong>
                    </div>

                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">ID</dt>
                            <dd class="col-sm-8">{{ $room->id }}</dd>

                            <dt class="col-sm-4">Tên phòng</dt>
                            <dd class="col-sm-8">{{ $room->title }}</dd>

                            <dt class="col-sm-4">Mô tả</dt>
                            <dd class="col-sm-8">{{ $room->description ?: '—' }}</dd>

                            @php
                                $addr = $room->address ?? null;
                            @endphp
                            @if ($addr)
                                                <dt class="col-sm-4">Địa chỉ</dt>
                                                <dd class="col-sm-8">
                                                    {{ $addr->formatted_address
                                ?? trim(
                                    implode(', ', array_filter([
                                        $addr->street ?? null,
                                        $addr->district ?? null,
                                        $addr->city ?? null,
                                        $addr->country ?? null,
                                    ])),
                                    ', '
                                )
                                ?: 'Chưa có địa chỉ chi tiết.' }}
                                                </dd>
                            @endif

                            @if ($room->city || $room->district || $room->country)
                                <dt class="col-sm-4">Khu vực</dt>
                                <dd class="col-sm-8">
                                    {{ $room->district ? $room->district . ', ' : '' }}
                                    {{ $room->city ? $room->city . ', ' : '' }}
                                    {{ $room->country }}
                                </dd>
                            @endif

                            <dt class="col-sm-4">Trạng thái</dt>
                            <dd class="col-sm-8">
                                <span class="badge {{ $room->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </dd>

                            <dt class="col-sm-4">Ngày tạo</dt>
                            <dd class="col-sm-8">{{ $room->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-4">Cập nhật</dt>
                            <dd class="col-sm-8">{{ $room->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Hình ảnh phòng --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>Hình ảnh phòng</strong>
                    </div>
                    <div class="card-body">
                        @include('partials.general.image-gallery', ['room' => $room])
                    </div>
                </div>
            </div>
        </div>

        {{-- Loại phòng --}}
        <div class="card shadow-sm h-100 mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Các loại phòng (Room Types)</strong>
                <a href="{{ route('host.rooms.room-types.create', $room) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Thêm loại phòng
                </a>
            </div>

            @if ($room->roomTypes->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên loại phòng</th>
                                <th class="text-center">Số khách tối đa</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Giá / đêm</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Lịch phòng</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($room->roomTypes as $type)
                                <tr>
                                    <td>{{ $type->id }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td class="text-center">{{ $type->max_guests }}</td>
                                    <td class="text-center">{{ $type->total_units }}</td>
                                    <td class="text-end">{{ number_format($type->price_per_night) }} đ</td>

                                    <td class="text-center">
                                        <span
                                            class="badge @if ($type->status == 'active') bg-success @else bg-warning text-dark @endif">
                                            {{ $type->status }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('host.room-types.calendars.index', $type) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('host.rooms.room-types.edit', [$room, $type]) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('host.rooms.room-types.destroy', [$room, $type]) }}"
                                                method="POST" onsubmit="return confirm('Xóa loại phòng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">
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
                <div class="card-body">
                    <p class="text-muted mb-0">Chưa có loại phòng nào.</p>
                </div>
            @endif
        </div>

    </div>
@endsection