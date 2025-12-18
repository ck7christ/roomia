{{-- resources/views/partials/general/footer.blade.php --}}
<footer class="rm-footer mt-5">
    {{--
    container: căn giữa nội dung theo chuẩn Bootstrap
    py-5: padding trên/dưới tạo khoảng thở cho footer
    --}}
    <div class="container py-5">
        {{--
        row g-4: chia hàng + khoảng cách (gutter) giữa các cột
        Mỗi cột dùng col-12 để full width ở mobile,
        và tăng dần col-md / col-lg để hiển thị nhiều cột hơn trên màn lớn.
        --}}
        <div class="row g-4">
            {{-- CỘT 1: HỖ TRỢ --}}
            <div class="col-12 col-md-6 col-lg-3">
                {{-- fw-bold: chữ đậm (Bootstrap utility) --}}
                <div class="fw-bold mb-2">HỖ TRỢ</div>
                {{--
                list-unstyled: bỏ dấu chấm danh sách mặc định
                rm-footer-links: class custom để đồng bộ style link trong footer
                --}}
                <ul class="list-unstyled rm-footer-links">
                    {{--
                    url('/...'): tạo URL nội bộ dự án (không phụ thuộc domain).
                    Nếu sau này có named route, có thể đổi sang route('tên_route') để rõ nghĩa hơn.
                    --}}
                    <li><a href="{{ url('/help') }}">Quản lí các chuyến đi của bạn</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ Dịch vụ Khách hàng</a></li>
                    <li><a href="{{ url('/notifications') }}">Trung tâm thông tin thông báo</a></li>
                </ul>
            </div>
            {{-- CỘT 2: KHÁM PHÁ THÊM --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="fw-bold mb-2">KHÁM PHÁ THÊM</div>
                <ul class="list-unstyled rm-footer-links">
                    <li><a href="{{ url('/news') }}">Chương trình khách hàng thân thiết Roomia</a></li>
                    <li><a href="{{ url('/support') }}">Liên hệ Dịch vụ Khách hàng</a></li>
                    <li><a href="{{ url('/privacy-center') }}">Trung tâm thông tin bảo mật</a></li>
                </ul>
            </div>
            {{-- CỘT 3: ĐIỀU KHOẢN --}}
            <div class="col-12 col-md-6 col-lg-2">
                <div class="fw-bold mb-2">ĐIỀU KHOẢN</div>
                <ul class="list-unstyled rm-footer-links">
                    <li><a href="{{ url('/terms') }}">Chính sách bảo mật &amp; Cookie</a></li>
                    <li><a href="{{ url('/legal') }}">Điều khoản dịch vụ</a></li>
                </ul>
            </div>
            {{-- CỘT 4: DÀNH CHO ĐỐI TÁC --}}
            <div class="col-12 col-md-6 col-lg-2">
                <div class="fw-bold mb-2">DÀNH CHO ĐỐI TÁC</div>
                <ul class="list-unstyled rm-footer-links">
                    <li><a href="{{ url('/partners') }}">Trợ giúp đối tác</a></li>
                    <li><a href="{{ url('/host') }}">Đăng chỗ ở của Quý vị</a></li>
                </ul>
            </div>
            {{-- CỘT 5: VỀ CHÚNG TÔI --}}
            <div class="col-12 col-md-6 col-lg-2">
                <div class="fw-bold mb-2">VỀ CHÚNG TÔI</div>
                <ul class="list-unstyled rm-footer-links">
                    <li><a href="{{ url('/about') }}">Về Roomia</a></li>
                    <li><a href="{{ url('/contact') }}">Liên hệ công ty</a></li>
                </ul>
            </div>
        </div>
        <hr class="rm-footer-divider my-4">
        {{--
        flex-column (mobile) -> flex-md-row (>= md): tự đổi layout theo màn hình.
        justify-content-between: đẩy 2 cụm sang 2 phía (trái/phải) trên màn lớn.
        small text-muted: chữ nhỏ + màu nhạt theo Bootstrap.
        --}}
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 small text-muted">
            <div>
                {{--
                Icon Font Awesome (fa-regular fa-copyright).
                Điều kiện: cần đã nhúng Font Awesome trong layout/base.
                --}}
                <i class="fa-regular fa-copyright me-1"></i>
                {{--
                now()->year: lấy năm hiện tại theo Laravel (Carbon) -> tự cập nhật mỗi năm.
                fw-semibold: nhấn nhẹ tên thương hiệu.
                --}}
                {{ now()->year }} - <span class="fw-semibold">Roomia</span>. Bảo lưu mọi quyền.
            </div>
            {{-- Nhóm link nhỏ phía phải (hoặc dưới ở mobile) --}}
            <div class="d-flex align-items-center gap-3">
                <a class="rm-footer-mini" href="{{ url('/privacy') }}">Chính Sách Quyền Riêng Tư</a>
                <a class="rm-footer-mini" href="{{ url('/terms') }}">Chính Sách Bảo Mật</a>
                <a class="rm-footer-mini" href="{{ url('/contact') }}">Liên Hệ</a>
            </div>
        </div>
    </div>
</footer>
