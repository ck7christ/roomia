{{-- resources/views/layouts/admin.blade.php --}}
{{-- Kế thừa layout nền (base) để dùng chung head, assets (CSS/JS), favicon, meta,... --}}
@extends('layouts.base')
{{-- Section "layout" là vùng khung chính mà base.blade.php sẽ render qua @yield('layout') --}}
@section('layout')
    {{-- Khung layout dành cho Admin
    - container-fluid: phù hợp trang quản trị (dashboard), tận dụng chiều ngang màn hình --}}
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar Admin
            - col-md-3 / col-lg-2: sidebar hẹp trên màn hình lớn, full-width trên mobile--}}
            <div class="col-12 col-md-3 col-lg-2">
                @include('partials.admin.sidebar')
            </div>
            {{-- Nội dung chính Admin
            - col-md-9 / col-lg-10: phần nội dung chiếm diện tích còn lại --}}
            <div class="col-12 col-md-9 col-lg-10">
                {{-- Nơi các view con (admin/dashboard, admin/users/index, ...) render nội dung --}}
                @yield('content')
            </div>
        </div>
    </div>
@endsection