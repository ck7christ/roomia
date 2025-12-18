{{-- resources/views/layouts/base.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        {{-- (1) Bộ ký tự UTF-8 để hiển thị tiếng Việt và đa ngôn ngữ ổn định --}}
        <meta charset="utf-8">
        {{-- (2) Viewport giúp giao diện responsive trên mobile/tablet --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- (3) CSRF token: bảo vệ form/AJAX khỏi tấn công CSRF trong Laravel --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- (4) Tiêu đề tab trình duyệt --}}
        <title>@yield('title', config('app.name', 'Roomia'))</title>
        {{-- =========================================================
    (5) FAVICON / APP ICONS (logo trên tab browser + icon iOS/Android)
    - Tất cả file icon đặt trong: public/assets/images/logo/
    - asset('...') sẽ map tới thư mục public/
    ========================================================== --}}
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/logo/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/logo/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/logo/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/logo/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/logo/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/logo/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/logo/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/logo/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"
            href="{{ asset('assets/images/logo/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32"
            href="{{ asset('assets/images/logo/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96"
            href="{{ asset('assets/images/logo/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ asset('assets/images/logo/favicon-16x16.png') }}">
        {{-- manifest.json hỗ trợ PWA/Android --}}
        <link rel="manifest" href="{{ asset('assets/images/logo/manifest.json') }}">
        {{-- Các meta dành cho Windows tile + theme color trình duyệt --}}
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('assets/images/logo/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        {{-- =========================================================
    (6) Fonts
    - Dùng Inter từ Bunny Fonts (nhẹ, ổn định, phổ biến)
    ========================================================== --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        {{-- =========================================================
    (7) CSS (external)
    - Bootstrap: public/assets/css/bootstrap.min.css
    - Font Awesome (local): public/assets/fontawesome/css/all.min.css
    - App CSS: public/assets/css/app.css (các custom style của Roomia)
    ========================================================== --}}
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
        {{-- (8) Cho phép từng view “push” thêm CSS nếu cần --}}
        @stack('styles')
    </head>

    <body>
        {{-- =========================================================
    (9) Container hiệu ứng (ví dụ: tuyết rơi)
    ========================================================== --}}
        <div id="snow-container"></div>
        {{-- (10) Khu vực render layout con (guest/host/admin) --}}
        @yield('layout')
        {{-- Footer dùng chung --}}
        @include('partials.general.footer')
        {{-- =========================================================
    (12) JS (external)
    - Bootstrap bundle: public/assets/js/bootstrap.bundle.min.js
    - App JS: public/assets/js/app.js (logic chung dự án)
    ========================================================== --}}
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
        {{-- (13) Cho phép từng view “push” thêm JS nếu cần --}}
        @stack('scripts')
    </body>

</html>
