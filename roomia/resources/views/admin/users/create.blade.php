@extends('layouts.admin')
@section('title', 'Tạo User')

@section('content')
    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Tạo User</h4>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @include('partials.admin.users.form', [
                        'user' => $user,
                        'roles' => $roles,
                        'isEdit' => false,
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection
