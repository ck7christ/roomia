@php
    $action = Route::has('guest.rooms.index') ? route('guest.rooms.index') : '#';

    // default values (có thể lấy từ query để giữ state)
    $adults = (int) request('adults', 2);
    $children = (int) request('children', 0);
    $rooms = (int) request('rooms', 1);
@endphp

<div id="search" class="card border border-2 border-warning mb-4 home-search-card">
    <div class="card-body">
        <form class="row g-2 align-items-center home-search-form" method="GET" action="{{ $action }}">
            <div class="col-12 col-lg-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                    <input name="destination" type="text" class="form-control" placeholder="Bạn muốn đi đâu?"
                        value="{{ request('destination') }}">
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                    <input name="check_in" type="date" class="form-control" value="{{ request('check_in') }}">
                    <input name="check_out" type="date" class="form-control" value="{{ request('check_out') }}">
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>

                    <div class="dropdown flex-grow-1">
                        <button class="form-control text-start guest-summary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <span class="guest-summary-text">
                                {{ $adults }} Người lớn - {{ $children }} Trẻ em - {{ $rooms }} Phòng
                            </span>
                        </button>

                        <div class="dropdown-menu p-3 w-100 guest-dropdown">
                            <input type="hidden" name="adults" value="{{ $adults }}">
                            <input type="hidden" name="children" value="{{ $children }}">
                            <input type="hidden" name="rooms" value="{{ $rooms }}">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="fw-semibold">Người lớn</div>
                                    <div class="text-muted small">Từ 13 tuổi trở lên</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 guest-stepper" data-field="adults"
                                    data-min="1" data-max="20">
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-minus">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="text" class="form-control form-control-sm text-center guest-value"
                                        value="{{ $adults }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="fw-semibold">Trẻ em</div>
                                    <div class="text-muted small">0–12 tuổi</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 guest-stepper" data-field="children"
                                    data-min="0" data-max="20">
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-minus">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="text" class="form-control form-control-sm text-center guest-value"
                                        value="{{ $children }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-semibold">Phòng</div>
                                    <div class="text-muted small">Số phòng muốn đặt</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 guest-stepper" data-field="rooms"
                                    data-min="1" data-max="10">
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-minus">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="text" class="form-control form-control-sm text-center guest-value"
                                        value="{{ $rooms }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-sm guest-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 d-grid">
                                <button type="button" class="btn btn-primary btn-sm guest-apply">Xong</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-2 d-grid">
                <button class="btn btn-primary">
                    TÌM PHÒNG
                </button>
            </div>
        </form>
    </div>
</div>