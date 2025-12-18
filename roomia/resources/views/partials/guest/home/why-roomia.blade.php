<h6 class="fw-bold mb-2">VÌ SAO LÀ ROOMIA?</h6>

<div class="row g-3 mb-4">
    @foreach(($whyRoomia ?? []) as $x)
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="{{ $x['icon'] }} fs-4 text-primary"></i>
                        <div class="fw-semibold">{{ $x['title'] }}</div>
                    </div>
                    <div class="text-muted small">{{ $x['desc'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>