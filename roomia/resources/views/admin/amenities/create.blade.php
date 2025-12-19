{{-- resources/views/admin/amenities/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm tiện nghi')

@section('content')
    <div class="p-3 p-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Thêm tiện nghi</h1>
                <div class="text-muted small">Tạo mới tiện nghi để gán cho phòng.</div>
            </div>

            <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.amenities.store') }}" method="POST" class="vstack gap-3">
                    @csrf

                    @include('admin.amenities._form', ['amenity' => $amenity ?? null])

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-primary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection