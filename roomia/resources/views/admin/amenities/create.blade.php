<x-app-layout>
    <h1>Thêm tiện nghi mới</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.amenities.store') }}" method="POST">
        @csrf

        @include('admin.amenities._form', ['amenity' => $amenity])

        <p>
            <button type="submit">Lưu</button>
        </p>
    </form>
</x-app-layout>
