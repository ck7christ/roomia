@php
    $user = $user ?? new \App\Models\User();
    $roles = $roles ?? collect();
    $isEdit = $isEdit ?? false;

    $currentRole = old('role');
    if ($currentRole === null && $isEdit) {
        $currentRole = $user->roles?->pluck('name')->first();
    }
@endphp

@csrf

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <label class="form-label">Họ tên</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}" placeholder="Nhập tên...">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" placeholder="name@example.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror">
            <option value="">-- Chọn role --</option>
            @foreach($roles as $r)
                <option value="{{ $r }}" @selected($currentRole === $r)>{{ $r }}</option>
            @endforeach
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label class="form-label">
            Mật khẩu
            @if($isEdit)
                <span class="text-muted small">(bỏ trống nếu không đổi)</span>
            @endif
        </label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="Tối thiểu 8 ký tự">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-lg-6">
        <label class="form-label">Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu">
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            Hủy
        </a>
    </div>
</div>