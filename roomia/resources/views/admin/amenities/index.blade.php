{{-- resources/views/admin/amenities/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tiện nghi')

@section('content')
    <div class="p-3 p-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Tiện nghi</h1>
                <div class="text-muted small">Quản lý danh sách tiện nghi.</div>
            </div>

            <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Thêm tiện nghi
            </a>
        </div>

        {{-- Flash message (nếu bạn có partial riêng thì include vào đây) --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-regular fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Tên</th>
                                <th style="width: 160px;">Mã</th>
                                <th style="width: 160px;">Nhóm</th>
                                <th style="width: 260px;">Icon</th>
                                <th style="width: 120px;">Trạng thái</th>
                                <th style="width: 110px;" class="text-end">Thứ tự</th>
                                <th style="width: 220px;" class="text-end">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($amenities as $amenity)
                                            <tr>
                                                <td class="text-muted">#{{ $amenity->id }}</td>

                                                <td>
                                                    <div class="fw-semibold">{{ $amenity->name }}</div>
                                                    <div class="text-muted small">{{ $amenity->code }}</div>
                                                </td>

                                                <td>
                                                    <span class="badge text-bg-primary">{{ $amenity->code }}</span>
                                                </td>

                                                <td>{{ $amenity->group ?: '—' }}</td>

                                                <td>
                                                    @if ($amenity->icon_class)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="{{ $amenity->icon_class }}"></i>
                                                            <code class="small">{{ $amenity->icon_class }}</code>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($amenity->is_active)
                                                        <span class="badge text-bg-success">Đang dùng</span>
                                                    @else
                                                        <span class="badge text-bg-warning">Tạm ẩn</span>
                                                    @endif
                                                </td>

                                                <td class="text-end">{{ $amenity->sort_order }}</td>

                                                <td class="text-end">
                                                    <a href="{{ route('admin.amenities.show', $amenity) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Xem">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.amenities.edit', $amenity) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Sửa">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>

                                                    <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                </div>
                                </td>
                                </tr>
                            @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Chưa có tiện nghi nào.
                        </td>
                    </tr>
                @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection