@extends('layouts.admin')
@section('title', 'Quản lý Users')

@section('content')
<div class="py-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Users</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Tạo user
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-lg-5">
                    <label class="form-label">Từ khóa</label>
                    <input type="text" name="q" class="form-control" placeholder="Tên hoặc email..."
                        value="{{ request('q') }}">
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach(($roles ?? []) as $r)
                            <option value="{{ $r }}" @selected(request('role') === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th style="width: 90px;">#</th>
                            <th>Thông tin</th>
                            <th style="width: 220px;">Roles</th>
                            <th class="text-end" style="width: 180px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td class="fw-semibold">#{{ $u->id }}</td>

                            <td>
                                <div class="fw-semibold">{{ $u->name }}</div>
                                <div class="text-muted small">{{ $u->email }}</div>
                                <div class="text-muted small">
                                    Tạo: {{ optional($u->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <td>
                                @php($roleNames = $u->roles?->pluck('name') ?? collect())
                                @if($roleNames->count())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($roleNames as $rn)
                                            <span class="badge text-bg-light border">{{ $rn }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary"
                                    title="Sửa">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Xóa"
                                        @disabled(auth()->id() === $u->id)>
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>

                                @if(auth()->id() === $u->id)
                                    <div class="text-muted small mt-1">Không thể xóa chính mình</div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Chưa có user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection