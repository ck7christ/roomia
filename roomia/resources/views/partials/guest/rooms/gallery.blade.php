@php
    $id = 'roomGallery' . ($room->id ?? 'x');
    $imgs = ($images ?? collect())->values();
@endphp

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if ($imgs->count())
            <div id="{{ $id }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($imgs as $i => $img)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <div class="room-thumb-wrapper">
                                <img src="{{ asset('storage/' . $img->file_path) }}" alt="Photo {{ $i + 1 }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($imgs->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $id }}"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Prev</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $id }}"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        @else
            <div class="p-4 text-center text-muted">
                Không có ảnh.
            </div>
        @endif
    </div>
</div>
