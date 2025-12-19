{{-- resources/views/partials/general/alerts.blade.php --}}

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Có lỗi xảy ra:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $key => $class)
    @if (session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach
