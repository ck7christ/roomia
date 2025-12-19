{{-- resources/views/admin/amenities/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Sửa tiện nghi')

@section('content')
    <div class="p-3 p-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Sửa tiện nghi #{{ $amenity->id }}</h1>
                <div class="text-muted small">Cập nhật thông tin tiện nghi.</div>
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
                <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST" class="vstack gap-3">
                    @csrf
                    @method('PUT')

                    @include('admin.amenities._form', ['amenity' => $amenity])

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.amenities.index') }}" class="btn btn-outline-primary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-regular fa-pen-to-square me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
