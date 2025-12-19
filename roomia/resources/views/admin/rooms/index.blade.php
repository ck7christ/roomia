@extends('layouts.admin')
@section('title', 'Quản lý Rooms')

@section('content')
    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Rooms</h4>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Filter --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.rooms.index') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-lg-4">
                        <label class="form-label">Từ khóa (title)</label>
                        <input type="text" name="q" class="form-control" placeholder="Nhập tên phòng..."
                            value="{{ request('q') }}">
                    </div>

                    <div class="col-12 col-lg-3">
                        <label class="form-label">Địa điểm (destination)</label>
                        <input type="text" name="destination" class="form-control" placeholder="City/District/Country..."
                            value="{{ request('destination') }}">
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach(($statuses ?? []) as $st)
                                <option value="{{ $st }}" @selected(request('status') === $st)>{{ strtoupper($st) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <label class="form-label">Host</label>
                        <select name="host_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach(($hosts ?? []) as $h)
                                <option value="{{ $h->id }}" @selected((string) request('host_id') === (string) $h->id)>
                                    {{ $h->name }} ({{ $h->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label">City</label>
                        <select name="city_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach(($cities ?? []) as $c)
                                <option value="{{ $c->id }}" @selected((string) request('city_id') === (string) $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-lg-3 d-flex gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fa-solid fa-filter me-1"></i> Lọc
                        </button>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.rooms.index') }}">Reset</a>
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
                                <th>Phòng</th>
                                <th>Host</th>
                                <th>Địa chỉ</th>
                                <th class="text-center" style="width: 120px;">Trạng thái</th>
                                <th class="text-end" style="width: 160px;"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($rooms as $room)
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
                                    $city = $addr?->city?->name;
                                    $district = $addr?->district?->name;
                                    $country = $addr?->country?->name;
                                    $loc = collect([$district, $city, $country])->filter()->implode(', ');

                                    $cover = $room->coverImage?->file_path;
                                @endphp

                                <tr>
                                    <td class="fw-semibold">#{{ $room->id }}</td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <div class="flex-shrink-0">
                                                @if($cover)
                                                    <img src="{{ asset('storage/' . $cover) }}" alt="cover" class="rounded border"
                                                        width="54" height="40">
                                                @else
                                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center"
                                                        style="width:54px;height:40px;">
                                                        <i class="fa-regular fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <div class="fw-semibold text-truncate" style="max-width: 320px;">
                                                    {{ $room->title }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $room->roomTypes?->count() ?? 0 }} loại phòng
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $room->host?->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $room->host?->email ?? '' }}</div>
                                    </td>

                                    <td>
                                        <div class="text-muted small">
                                            {{ $loc ?: '-' }}
                                        </div>
                                        @if(!empty($addr?->formatted_address))
                                            <div class="small">{{ $addr->formatted_address }}</div>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <span class="badge text-bg-{{ $badge }}">{{ strtoupper($room->status ?? '-') }}</span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-sm btn-outline-primary">
                                            Xem
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            Sửa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Chưa có room.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection