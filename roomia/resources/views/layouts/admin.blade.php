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
                <div class="d-lg-none sticky-top bg-white border-bottom px-2 py-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                        data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                        <i class="fa-solid fa-bars " title="Menu"></i> 
                    </button>
                </div>
                {{-- Nơi các view con (admin/dashboard, admin/users/index, ...) render nội dung --}}
                @yield('content')
            </div>
        </div>
    </div>
@endsection