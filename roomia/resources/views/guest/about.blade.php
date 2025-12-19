{{-- resources/views/guest/about.blade.php --}}
@extends('layouts.guest')

@section('title', 'Về Roomia')

@section('content')
    <div class="container rm-about-bg py-4">

        {{-- HERO --}}
        <div class="rm-page-hero p-4 p-md-5 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-7">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="rm-soft-badge">
                            <i class="fa-solid fa-location-dot text-accent"></i>
                            Đặt chỗ ở dễ hơn
                        </span>
                        <span class="rm-soft-badge">
                            <i class="fa-solid fa-calendar-check text-accent"></i>
                            Lịch linh hoạt
                        </span>
                        <span class="rm-soft-badge">
                            <i class="fa-solid fa-star text-accent"></i>
                            Review minh bạch
                        </span>
                    </div>

                    <h1 class="rm-title display-6 fw-bold mb-2">Roomia — nền tảng đặt chỗ ở nhanh, gọn, dễ.</h1>
                    <p class="rm-muted mb-3">
                        Roomia kết nối <strong>Guest</strong> và <strong>Host</strong> để việc tìm kiếm, đặt phòng,
                        quản lý lịch và trải nghiệm chuyến đi trở nên đơn giản, rõ ràng và tiện lợi.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Khám phá chỗ ở
                        </a>
                        <a href="{{ url('/register') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-user-plus me-2"></i> Tạo tài khoản
                        </a>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card rm-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="rm-icon-pill"><i class="fa-solid fa-filter"></i></span>
                                <div>
                                    <div class="fw-semibold">Tìm kiếm thông minh</div>
                                    <div class="rm-muted small">Lọc theo điểm đến, ngày, giá, tiện nghi.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="rm-icon-pill"><i class="fa-solid fa-clock"></i></span>
                                <div>
                                    <div class="fw-semibold">Cập nhật nhanh</div>
                                    <div class="rm-muted small">Theo dõi tình trạng đặt phòng rõ ràng.</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <span class="rm-icon-pill"><i class="fa-solid fa-handshake"></i></span>
                                <div>
                                    <div class="fw-semibold">Đôi bên cùng có lợi</div>
                                    <div class="rm-muted small">Guest dễ chọn, Host dễ quản lý & tối ưu.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- VALUES --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card rm-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="rm-icon-pill"><i class="fa-solid fa-shield-halved"></i></span>
                            <div class="fw-semibold">Tin cậy</div>
                        </div>
                        <div class="rm-muted small">
                            Thông tin rõ ràng, quy trình đặt phòng minh bạch để giảm rủi ro.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card rm-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="rm-icon-pill"><i class="fa-solid fa-bolt"></i></span>
                            <div class="fw-semibold">Đơn giản</div>
                        </div>
                        <div class="rm-muted small">
                            Trải nghiệm tối giản: tìm — chọn — đặt — theo dõi trong vài bước.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card rm-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="rm-icon-pill"><i class="fa-solid fa-star"></i></span>
                            <div class="fw-semibold">Minh bạch</div>
                        </div>
                        <div class="rm-muted small">
                            Review & rating giúp Guest chọn đúng, Host cải thiện chất lượng.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HOW IT WORKS --}}
        <div class="card rm-card mb-4">
            <div class="card-header fw-semibold bg-white">
                <i class="fa-solid fa-diagram-project me-2 text-accent"></i> Roomia hoạt động như thế nào?
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="fw-semibold mb-2">
                                <i class="fa-solid fa-suitcase-rolling me-2 text-accent"></i> Dành cho Guest
                            </div>
                            <ol class="rm-muted mb-0">
                                <li>Tìm kiếm theo địa điểm và ngày.</li>
                                <li>Chọn loại phòng phù hợp số khách.</li>
                                <li>Đặt phòng và theo dõi booking.</li>
                                <li>Trải nghiệm và để lại đánh giá.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="fw-semibold mb-2">
                                <i class="fa-solid fa-house-user me-2 text-accent"></i> Dành cho Host
                            </div>
                            <ol class="rm-muted mb-0">
                                <li>Đăng chỗ ở, tạo RoomType.</li>
                                <li>Quản lý lịch & giá theo ngày.</li>
                                <li>Nhận booking và xử lý trạng thái.</li>
                                <li>Quản lý review (tuỳ module).</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="rm-page-hero p-4 rounded-3">
            <div class="row align-items-center g-3">
                <div class="col-12 col-lg-8">
                    <div class="fw-semibold mb-1">Sẵn sàng trải nghiệm Roomia?</div>
                    <div class="rm-muted">
                        Bắt đầu khám phá điểm đến hoặc đăng chỗ ở của bạn để tiếp cận nhiều khách hơn.
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2 justify-content-lg-end">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Tìm chỗ ở
                    </a>
                    <a href="{{ url('/register') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-house-circle-check me-2"></i> Trở thành Host
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
