{{-- resources/views/host/rooms/create.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">
        <h1 class="h4 mb-4">Tạo phòng mới</h1>

        <form action="{{ route('host.rooms.store') }}" method="POST" enctype="multipart/form-data">
            @include('host.rooms._form')

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    Lưu phòng
                </button>
            </div>
        </form>
    </div>
@endsection
