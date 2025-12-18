@extends('layouts.admin')
@section('title', 'Tạo Voucher')

@section('content')
    <div class="py-4 px-3">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Tạo Voucher</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vouchers.store') }}">
                    @include('partials.admin.vouchers.form')
                </form>
            </div>
        </div>
    </div>
@endsection