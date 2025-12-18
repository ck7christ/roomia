<p>
    <label>Tên tiện nghi (name):</label><br>
    <input type="text" name="name" value="{{ old('name', $amenity->name ?? '') }}">
    @error('name')
        <div>{{ $message }}</div>
    @enderror
</p>

<p>
    <label>Mã (code):</label><br>
    <input type="text" name="code" value="{{ old('code', $amenity->code ?? '') }}">
    @error('code')
        <div>{{ $message }}</div>
    @enderror
</p>

<p>
    <label>Nhóm (group):</label><br>
    <input type="text" name="group" value="{{ old('group', $amenity->group ?? '') }}">
    @error('group')
        <div>{{ $message }}</div>
    @enderror
</p>

<p>
    <label>Icon class (Font Awesome):</label><br>
    <input type="text" name="icon_class" value="{{ old('icon_class', $amenity->icon_class ?? '') }}">
    <br>
    <small>Ví dụ: fa-solid fa-wifi</small>
    @error('icon_class')
        <div>{{ $message }}</div>
    @enderror
</p>

<p>
    <label>Thứ tự hiển thị (sort_order):</label><br>
    <input type="number" name="sort_order" min="0"
           value="{{ old('sort_order', $amenity->sort_order ?? 0) }}">
    @error('sort_order')
        <div>{{ $message }}</div>
    @enderror
</p>

<p>
    @php
        $active = old('is_active', $amenity->is_active ?? true);
    @endphp
    <label>Hoạt động (is_active):</label><br>
    <select name="is_active">
        <option value="1" @selected($active == 1)>Đang sử dụng</option>
        <option value="0" @selected($active == 0)>Tạm ẩn</option>
    </select>
    @error('is_active')
        <div>{{ $message }}</div>
    @enderror
</p>
