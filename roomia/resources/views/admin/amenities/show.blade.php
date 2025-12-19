{{-- resources/views/admin/amenities/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết tiện nghi')

@section('content')
    <div class="p-3 p-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Chi tiết tiện nghi</h1>
                <div class="text-muted small">#{{ $amenity->id }} • {{ $amenity->name }}</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.amenities.edit', $amenity) }}" class="btn btn-primary">
                    <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-lg-7">
                        <div class="vstack gap-2">
                            <div class="d-flex justify-content-between gap-3">
                                <span class="text-muted">Tên</span>
                                <span class="fw-semibold">{{ $amenity->name }}</span>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <span class="text-muted">Mã (code)</span>
                                <span><code>{{ $amenity->code }}</code></span>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <span class="text-muted">Nhóm</span>
                                <span>{{ $amenity->group ?: '—' }}</span>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <span class="text-muted">Thứ tự</span>
                                <span>{{ $amenity->sort_order }}</span>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <span class="text-muted">Trạng thái</span>
                                <span>
                                    @if ($amenity->is_active)
                                        <span class="badge text-bg-success">Đang dùng</span>
                                    @else
                                        <span class="badge text-bg-warning">Tạm ẩn</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="fw-semibold mb-2">Icon</div>

                        @if ($amenity->icon_class)
                            <div class="border rounded-3 p-3 d-flex align-items-center gap-3">
                                <i class="{{ $amenity->icon_class }} fa-2x"></i>
                                <div>
                                    <div class="text-muted small">Class</div>
                                    <code>{{ $amenity->icon_class }}</code>
                                </div>
                            </div>
                        @else
                            <div class="text-muted">Chưa có icon.</div>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-regular fa-trash-can me-1"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
