<x-app-layout>
    <h1>Sửa tiện nghi #{{ $amenity->id }}</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.amenities._form', ['amenity' => $amenity])

        <p>
            <button type="submit">Cập nhật</button>
        </p>
    </form>
</x-app-layout>
