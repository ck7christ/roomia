@extends('layouts.admin')

@section('title', 'Contact #' . $m->id)

@section('content')
    <div class="py-4 px-3">

        @includeIf('partials.general.alerts')

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <div class="h5 mb-0">Liên hệ #{{ $m->id }}</div>
                <div class="text-muted small">
                    {{ optional($m->created_at)->format('d/m/Y H:i') }} •
                    @include('partials.admin.contact-messages.status-badge', ['status' => $m->status])
                </div>
            </div>

            <form method="POST" action="{{ route('admin.contact-messages.destroy', $m) }}"
                onsubmit="return confirm('Xóa liên hệ này?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" type="submit">
                    <i class="fa-solid fa-trash me-1"></i> Xóa
                </button>
            </form>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Nội dung</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="fw-semibold">{{ $m->name }}</div>
                            <div class="text-muted small">{{ $m->email }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Chủ đề</div>
                            <div>{{ $m->subject ?? '—' }}</div>
                        </div>

                        <div class="text-muted small mb-1">Tin nhắn</div>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($m->message)) !!}
                        </div>

                        <div class="text-muted small mt-3">
                            IP: {{ $m->ip_address ?? '—' }} • UA:
                            {{ $m->user_agent ? \Illuminate\Support\Str::limit($m->user_agent, 80) : '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Xử lý</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.contact-messages.update', $m) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" @selected($m->status === $st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú nội bộ</label>
                                <textarea name="admin_note" rows="5"
                                    class="form-control @error('admin_note') is-invalid @enderror">{{ old('admin_note', $m->admin_note) }}</textarea>
                                @error('admin_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-save me-2"></i> Lưu
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('admin.contact-messages.index') }}">
                                Quay lại
                            </a>
                        </form>

                        @if($m->handler)
                            <div class="text-muted small mt-3">
                                Handled by: {{ $m->handler->name }} •
                                {{ $m->replied_at ? 'Replied at: ' . $m->replied_at->format('d/m/Y H:i') : '—' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection