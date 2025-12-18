<div class="card rm-card">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-paper-plane me-2 text-accent"></i> Gửi tin nhắn
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('contact.send') }}">
            @csrf

            {{-- Honeypot --}}
            <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" value="{{ $prefill['name'] ?? old('name') }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $prefill['email'] ?? old('email') }}"
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Chủ đề</label>
                    <input type="text" name="subject" value="{{ $prefill['subject'] ?? old('subject') }}"
                        class="form-control @error('subject') is-invalid @enderror">
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Nội dung</label>
                    <textarea name="message" rows="6"
                        class="form-control @error('message') is-invalid @enderror">{{ $prefill['message'] ?? old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-paper-plane me-2"></i> Gửi
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-house me-2"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>