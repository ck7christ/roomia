{{-- resources/views/layouts/host.blade.php --}}
{{-- Kế thừa layout nền (base) để dùng chung cấu trúc HTML, CSS/JS assets, favicon, meta,... --}}
@extends('layouts.base')
{{-- Section "layout" là vùng khung chính mà base.blade.php sẽ render qua @yield('layout') --}}
@section('layout')
    {{-- Navbar dành riêng cho Host
    - Tách thành partial để tái sử dụng và đồng bộ giao diện trên tất cả trang host --}}
    @include('partials.host.navbar')
    {{-- Khung nội dung chính của Host
    - Dùng container-fluid để tận dụng toàn bộ chiều ngang (phù hợp dashboard/quản trị)
    - Bootstrap grid (row/col) giúp responsive và dễ chia layout sau này (sidebar/content) --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Nơi các view con (host/dashboard, host/rooms/index, ...) đổ nội dung vào --}}
                @yield('content')
            </div>
        </div>
    </div>
@endsection