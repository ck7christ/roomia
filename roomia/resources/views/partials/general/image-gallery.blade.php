{{-- resources/views/partials/general/image-gallery.blade.php --}}
@php
    $images = $room->images;
    $total = $images->count();
@endphp

@if($total)
    <div class="row g-2">
        @foreach($images as $index => $image)
            @php
                // chỉ hiển thị 4 ảnh đầu
                if ($index > 3)
                    break;

                $imgPath = $image->file_path ?? $image->path ?? null;
                $isLastTileWithMore = ($index === 3 && $total > 4);
                $moreCount = $total - 3; 
            @endphp

            @if($imgPath)
                <div class="col-12 col-md-6">
                    <a href="#" class="room-image-thumb d-block" data-index="{{ $index }}">
                        <div class="room-thumb-wrapper border position-relative">
                            <img src="{{ asset('storage/' . $imgPath) }}" alt="Room image">

                            @if($isLastTileWithMore)
                                <div class="room-thumb-overlay">
                                    +{{ $moreCount }} ảnh
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
@else
    <p class="text-muted mb-0">Chưa có hình ảnh nào.</p>
@endif

{{-- Modal + slider --}}
@if($total)
    <div class="modal fade" id="roomImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">
                        Ảnh phòng: {{ $room->title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
                </div>

                <div class="modal-body p-0">
                    <div id="roomImageCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach($images as $loopIndex => $image)
                                @php
                                    $imgPath = $image->file_path ?? $image->path ?? null;
                                @endphp

                                @if($imgPath)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $imgPath) }}" class="d-block mx-auto room-modal-img"
                                            alt="Room image">
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if($total > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#roomImageCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#roomImageCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif