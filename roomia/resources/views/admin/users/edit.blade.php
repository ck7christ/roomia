@extends('layouts.admin')
@section('title', 'Sửa User')

@section('content')
    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Sửa User #{{ $user->id }}</h4>
                <div class="text-muted small">{{ $user->email }}</div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @method('PUT')

                    @include('partials.admin.users.form', [
                        'user' => $user,
                        'roles' => $roles,
                        'isEdit' => true,
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection
