{{-- resources/views/host/roomTypes/calendars/index.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        {{-- Tiêu đề + điều hướng --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Lịch phòng cho loại phòng: {{ $roomType->name }}</h1>
                <p class="text-muted mb-0">
                    Quản lý giá và tồn kho theo từng ngày cho loại phòng này.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('host.rooms.room-types.index', $room) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Danh sách loại phòng
                </a>
                <a href="{{ route('host.rooms.show', $room) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-bed me-1"></i> Xem phòng
                </a>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Lỗi validate --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <p class="fw-semibold mb-1">Có lỗi xảy ra:</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">

            {{-- Form thêm / cập nhật 1 ngày --}}
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>Thêm / cập nhật 1 ngày cụ thể</strong>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('host.room-types.calendars.store', $roomType) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="date" class="form-label">Ngày</label>
                                <input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}">
                            </div>

                            <div class="mb-3">
                                <label for="price_per_night" class="form-label">
                                    Giá / đêm
                                    <small class="text-muted">(bỏ trống = dùng giá mặc định)</small>
                                </label>
                                <input type="number" id="price_per_night" name="price_per_night" min="0"
                                    class="form-control" value="{{ old('price_per_night') }}">
                            </div>

                            <div class="mb-3">
                                <label for="available_units" class="form-label">
                                    Số lượng phòng khả dụng
                                    <small class="text-muted">(bỏ trống = dùng total_units)</small>
                                </label>
                                <input type="number" id="available_units" name="available_units" min="0"
                                    class="form-control" value="{{ old('available_units') }}">
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_closed" name="is_closed" value="1"
                                    @checked(old('is_closed'))>
                                <label class="form-check-label" for="is_closed">
                                    Đóng ngày này (không cho đặt)
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Lưu lịch
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Bảng lịch hiện tại --}}
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>Lịch hiện tại</strong>
                    </div>

                    @if ($calendars->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Giá / đêm (override)</th>
                                        <th>Số lượng khả dụng (override)</th>
                                        <th class="text-center">Đóng?</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($calendars as $calendar)
                                        <tr>
                                            <td>{{ $calendar->date->format('d/m/Y') }}</td>

                                            <td>
                                                @if (!is_null($calendar->price_per_night))
                                                    {{ number_format($calendar->price_per_night) }} đ
                                                @else
                                                    <span class="text-muted">
                                                        Dùng giá mặc định:
                                                        {{ number_format($roomType->price_per_night) }} đ
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (!is_null($calendar->available_units))
                                                    {{ $calendar->available_units }}
                                                @else
                                                    <span class="text-muted">
                                                        Dùng total_units: {{ $roomType->total_units }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ $calendar->is_closed ? 'Có' : 'Không' }}
                                            </td>

                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">

                                                    <form
                                                        action="{{ route('host.room-types.calendars.destroy', [$roomType, $calendar]) }}"
                                                        method="POST" data-confirm="Bạn chắc chắn muốn xóa cấu hình ngày này?">
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
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                Chưa có cấu hình lịch nào. Mặc định sẽ dùng giá & tồn kho của loại phòng.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection