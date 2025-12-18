@extends('layouts.admin')

@section('title', 'Contact Inbox')

@section('content')
    <div class="py-4 px-3">

        @includeIf('partials.general.alerts')

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h1 class="h5 mb-0">Liên hệ</h1>

            <form class="d-flex gap-2" method="GET" action="{{ route('admin.contact-messages.index') }}">
                <select name="status" class="form-select form-select-sm" style="max-width: 160px;">
                    <option value="">Tất cả</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" @selected(($status ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm" placeholder="Tìm kiếm...">
                <button class="btn btn-sm btn-primary" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Người gửi</th>
                            <th>Chủ đề</th>
                            <th>Trạng thái</th>
                            <th>Ngày</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $m)
                            <tr>
                                <td class="text-muted">#{{ $m->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $m->name }}</div>
                                    <div class="text-muted small">{{ $m->email }}</div>
                                </td>
                                <td class="text-muted">{{ $m->subject ?? '—' }}</td>
                                <td>@include('partials.admin.contact-messages.status-badge', ['status' => $m->status])</td>
                                <td class="text-muted">{{ optional($m->created_at)->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.contact-messages.show', $m) }}">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Chưa có liên hệ.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $messages->links() }}
        </div>

    </div>
@endsection