{{-- resources/views/host/roomTypes/edit.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        {{-- Header + actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Sửa loại phòng</h1>
                <p class="text-muted mb-0">
                    Loại phòng: <strong>{{ $roomType->name }}</strong><br>
                    Thuộc phòng / khách sạn: <strong>{{ $room->title }}</strong>
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('host.rooms.room-types.index', $room) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Danh sách loại phòng
                </a>
                <a href="{{ route('host.rooms.show', $room) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-hotel me-1"></i> Xem phòng
                </a>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông báo lỗi chung --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <p class="mb-2"><strong>Đã có lỗi xảy ra:</strong></p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card form --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('host.rooms.room-types.update', [$room, $roomType]) }}" method="POST">
                    @method('PUT')
                    @include('host.roomTypes._form')
                </form>
            </div>
        </div>
    </div>
@endsection