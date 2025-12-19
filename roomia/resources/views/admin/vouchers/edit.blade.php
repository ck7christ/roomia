@extends('layouts.admin')
@section('title', 'Sửa Voucher')

@section('content')
    <div class="container py-4 px-3">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Sửa Voucher</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
                    @method('PUT')
                    @include('partials.admin.vouchers.form', ['voucher' => $voucher])
                </form>
            </div>
        </div>
    </div>
@endsection