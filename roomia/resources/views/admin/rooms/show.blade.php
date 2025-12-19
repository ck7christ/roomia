@extends('layouts.admin')
@section('title', 'Chi tiết Room')

@section('content')
    @php
        $badge = match ($room->status) {
            'active' => 'success',
            'pending' => 'warning',
            'inactive' => 'secondary',
            'blocked' => 'danger',
            'draft' => 'light',
            default => 'light',
        };

        $addr = $room->address;
        $cover = $room->coverImage?->file_path;
    @endphp

    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Room #{{ $room->id }}</h4>
                <div class="text-muted small">{{ $room->title }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen me-1"></i> Sửa
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <div class="text-muted small">Trạng thái</div>
                                <span class="badge text-bg-{{ $badge }}">{{ strtoupper($room->status ?? '-') }}</span>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small">Host</div>
                                <div class="fw-semibold">{{ $room->host?->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $room->host?->email ?? '' }}</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Mô tả</div>
                            <div>{{ $room->description ?: '—' }}</div>
                        </div>

                        <hr>

                        <div class="fw-semibold mb-2">Địa chỉ</div>
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Country</div>
                                <div class="fw-semibold">{{ $addr?->country?->name ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">City</div>
                                <div class="fw-semibold">{{ $addr?->city?->name ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">District</div>
                                <div class="fw-semibold">{{ $addr?->district?->name ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Street</div>
                                <div class="fw-semibold">{{ $addr?->street ?? '—' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small">Formatted</div>
                                <div class="fw-semibold">{{ $addr?->formatted_address ?? '—' }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Lat</div>
                                <div class="fw-semibold">{{ $addr?->lat ?? '—' }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Lng</div>
                                <div class="fw-semibold">{{ $addr?->lng ?? '—' }}</div>
                            </div>
                        </div>

                        <hr>

                        <div class="fw-semibold mb-2">Amenities</div>
                        @if($room->amenities?->count())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($room->amenities as $am)
                                    <span class="badge text-bg-light border">
                                        @if(!empty($am->icon_class)) <i class="{{ $am->icon_class }} me-1"></i> @endif
                                        {{ $am->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">Chưa có tiện ích.</div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Room Types</div>

                        @if($room->roomTypes?->count())
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="text-muted small">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên</th>
                                            <th class="text-end">Giá/đêm</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($room->roomTypes as $rt)
                                            <tr>
                                                <td class="fw-semibold">#{{ $rt->id }}</td>
                                                <td class="fw-semibold">{{ $rt->name }}</td>
                                                <td class="text-end">
                                                    {{ number_format((float) ($rt->price_per_night ?? 0), 0, ',', '.') }} đ</td>
                                                <td class="text-center">
                                                    <span class="badge text-bg-light border">{{ $rt->status ?? '-' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted">Chưa có loại phòng.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Ảnh</div>

                        @if($cover)
                            <div class="mb-2">
                                <div class="text-muted small mb-1">Cover</div>
                                <img src="{{ asset('storage/' . $cover) }}" class="img-fluid rounded border" alt="cover">
                            </div>
                        @endif

                        @if($room->images?->count())
                            <div class="row g-2">
                                @foreach($room->images as $img)
                                    <div class="col-6">
                                        <img src="{{ asset('storage/' . $img->file_path) }}" class="img-fluid rounded border"
                                            alt="img">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">Chưa có ảnh.</div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Thao tác</div>

                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                Xóa phòng
                            </button>
                        </form>

                        <div class="text-muted small mt-2">
                            Nếu không xóa được, có thể phòng đang dính booking / ràng buộc dữ liệu.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection