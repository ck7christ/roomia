@extends('layouts.admin')
@section('title', 'Vouchers')

@section('content')
    <div class="py-4 px-3">
        @includeIf('partials.general.alerts')

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h1 class="h5 mb-0">Vouchers</h1>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('admin.vouchers.index') }}" class="d-flex gap-2">
                    <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm"
                        placeholder="Tìm code/tên...">
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-plus me-1"></i> Tạo voucher
                </a>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Hiệu lực</th>
                            <th>Lượt dùng</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $v)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $v->code }}</div>
                                    <div class="text-muted small">{{ $v->name ?? '—' }}</div>
                                </td>
                                <td class="text-muted">{{ $v->type }}</td>
                                <td class="text-muted">
                                    {{ $v->type === 'percent' ? rtrim(rtrim(number_format((float) $v->value, 2, '.', ''), '0'), '.') . '%' : number_format((float) $v->value, 0, ',', '.') }}
                                </td>
                                <td class="text-muted small">
                                    {{ $v->starts_at ? $v->starts_at->format('d/m/Y') : '—' }}
                                    →
                                    {{ $v->ends_at ? $v->ends_at->format('d/m/Y') : '—' }}
                                </td>
                                <td class="text-muted">
                                    {{ $v->used_count }}
                                    @if ($v->usage_limit)
                                        / {{ $v->usage_limit }}
                                    @endif
                                </td>
                                <td>
                                    @include('partials.admin.vouchers.status', ['voucher' => $v])
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.vouchers.show', $v) }}"><i class="fa-solid fa-eye"
                                            title="Xem"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary"
                                        href="{{ route('admin.vouchers.edit', $v) }}"><i
                                            class="fa-regular fa-pen-to-square" title="Sửa"></i></a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('admin.vouchers.destroy', $v) }}"
                                        onsubmit="return confirm('Xóa voucher này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit"><i
                                                class="fa-solid fa-trash"title="Xóa"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có voucher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $vouchers->links() }}</div>
    </div>
@endsection
