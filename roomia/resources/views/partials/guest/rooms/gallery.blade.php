{{-- resources/views/partials/guest/gallery.blade.php --}}
@php
    $baseId = 'rmGallery' . ($room->id ?? 'x');
    $cardCarouselId = $baseId . 'Card';
    $modalId = $baseId . 'Modal';
    $modalCarouselId = $baseId . 'ModalCarousel';

    $imgs = ($images ?? ($room->images ?? collect()))->values();
@endphp

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if ($imgs->count())
            <div id="{{ $cardCarouselId }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($imgs as $i => $img)
                        @php
                            $path = $img->file_path ?? $img->path ?? null;
                        @endphp

                        @if($path)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <a href="#"
                                   class="rm-gallery-open d-block"
                                   data-bs-toggle="modal"
                                   data-bs-target="#{{ $modalId }}"
                                   data-index="{{ $i }}"
                                   aria-label="Xem ảnh {{ $i + 1 }}">
                                    <div class="room-thumb-wrapper">
                                        <img src="{{ asset('storage/' . $path) }}" alt="Photo {{ $i + 1 }}">
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if ($imgs->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $cardCarouselId }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Prev</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $cardCarouselId }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>

            {{-- Modal full ảnh --}}
            <div class="modal fade rm-gallery-modal" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-0">
                            <h6 class="modal-title text-white mb-0">
                                Ảnh phòng: {{ $room->title ?? 'Room' }}
                            </h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>

                        <div class="modal-body p-0">
                            <div id="{{ $modalCarouselId }}" class="carousel slide" data-bs-ride="false">
                                <div class="carousel-inner">
                                    @foreach ($imgs as $i => $img)
                                        @php
                                            $path = $img->file_path ?? $img->path ?? null;
                                        @endphp

                                        @if($path)
                                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                                <img
                                                    src="{{ asset('storage/' . $path) }}"
                                                    class="d-block mx-auto room-modal-img "
                                                    alt="Room image {{ $i + 1 }}"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                @if ($imgs->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $modalCarouselId }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Prev</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#{{ $modalCarouselId }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 text-center text-muted">
                Không có ảnh.
            </div>
        @endif
    </div>
</div>
