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
@push('scripts')
    {{-- JS xử lý map của bạn --}}
    <script src="{{ asset('assets/js/room-map.js') }}"></script>

    {{-- Google Maps API --}}
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initRoomMap"
        async defer></script>

@endpush