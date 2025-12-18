{{-- resources/views/guest/rooms/index.blade.php --}}
@extends('layouts.guest')

@section('title', 'Lưu trú')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Lưu trú</h1>
                <div class="text-muted small">
                    {{ number_format($rooms->total()) }} kết quả
                </div>
            </div>
        </div>

        @includeIf('partials.general.alerts')

        @include('partials.guest.rooms.filter', [
            'cities' => $cities ?? collect(),
        ])

        @if ($rooms->count())
            <div class="row g-3">
                @foreach ($rooms as $room)
                    <div class="col-12 col-md-6 col-lg-4">
                        @include('partials.guest.rooms.card', ['room' => $room])
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $rooms->links() }}
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="display-6 mb-2">😕</div>
                    <h2 class="h5 mb-1">Không tìm thấy chỗ ở phù hợp</h2>
                    <div class="text-muted mb-3">Thử đổi từ khóa hoặc chọn thành phố khác.</div>
                    <a href="{{ route('guest.rooms.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Xem tất cả
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
