{{-- resources/views/layouts/guest.blade.php --}}
{{-- Kế thừa layout nền (base) để dùng chung head, assets (CSS/JS), favicon,... --}}
@extends('layouts.base')
{{-- Section "layout" là vùng khung chính mà base.blade.php sẽ render qua @yield('layout') --}}
@section('layout')
    {{-- Nhúng thanh điều hướng (navbar) dành riêng cho Guest
    - Tách thành partial để tái sử dụng và đồng bộ giao diện trên mọi trang guest --}}
    @include('partials.guest.navbar')
    {{-- Khung nội dung chính của guest
    - Sử dụng Bootstrap grid (container/row/col) để responsive
    - @yield('content') là nơi các view con (guest/home, guest/rooms/...) đổ nội dung vào --}}
    <div class="container">
        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
