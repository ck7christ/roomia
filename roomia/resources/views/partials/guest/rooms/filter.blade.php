@php
    $q = request('q');
    $cityId = request('city_id');
    $sort = request('sort');
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('guest.rooms.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="q" class="form-label mb-1">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input id="q" type="text" name="q" value="{{ $q }}" class="form-control"
                        placeholder="Tên chỗ ở, khu vực, tiện nghi...">
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <label for="city_id" class="form-label mb-1">Thành phố</label>
                <select id="city_id" name="city_id" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($cities ?? collect() as $city)
                        <option value="{{ $city->id }}"
                            {{ (string) $cityId === (string) $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4 col-lg-2">
                <label for="sort" class="form-label mb-1">Sắp xếp</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="">Mặc định</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá ↑</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá ↓</option>
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                </select>
            </div>

            <div class="col-12 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter me-1"></i> Lọc
                </button>

                <a href="{{ route('guest.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>
