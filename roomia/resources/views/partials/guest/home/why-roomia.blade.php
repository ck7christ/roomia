{{-- resources/views/partials/guest/why-roomia.blade.php --}}

<div class="why-roomia mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="why-roomia__heading fw-bold mb-0">VÌ SAO LÀ ROOMIA?</h6>
        <span class="why-roomia__dot"></span>
    </div>

    <div class="row g-3 g-lg-4">
        @foreach(($whyRoomia ?? []) as $x)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="why-roomia__card card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="why-roomia__icon flex-shrink-0">
                                <i class="{{ $x['icon'] }} fs-4"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="why-roomia__title text-break">
                                    {{ $x['title'] }}
                                </div>
                                <div class="why-roomia__desc text-break">
                                    {{ $x['desc'] }}
                                </div>
                            </div>
                        </div>

                        {{-- accent line (không thêm text, giữ nguyên nội dung) --}}
                        <div class="why-roomia__line mt-3"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>