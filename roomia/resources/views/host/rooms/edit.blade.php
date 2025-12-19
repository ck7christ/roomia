{{-- resources/views/host/rooms/edit.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">
        <h1 class="h4 mb-4">Chỉnh sửa phòng</h1>

        <form action="{{ route('host.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('host.rooms._form')

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
@endsection
