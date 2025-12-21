@php
    // Ưu tiên lat/lng từ $lat/$lng truyền vào, fallback từ $address nếu có
    $lat = $lat ?? ($address->lat ?? null);
    $lng = $lng ?? ($address->lng ?? null);

    $mode = $mode ?? 'show';
    $height = $height ?? 320;

    $latInput = $latInput ?? null;
    $lngInput = $lngInput ?? null;
    $autocompleteInput = $autocompleteInput ?? null;
    $streetInput = $streetInput ?? null;
    $formattedInput = $formattedInput ?? null;

    // optional: dùng để resolve id từ tên (controller của bạn có xử lý)
    $countryNameInput = $countryNameInput ?? null;
    $countryCodeInput = $countryCodeInput ?? null;
    $cityNameInput = $cityNameInput ?? null;
    $districtNameInput = $districtNameInput ?? null;

    // optional: class chiều cao (nếu bạn đã có CSS), vẫn giữ fallback inline height
    $heightClass = $heightClass ?? null;

    $mapId = $mapId ?? 'rm-map-' . uniqid();
@endphp

<div id="{{ $mapId }}" data-room-map data-mode="{{ $mode }}" data-lat="{{ $lat }}"
    data-lng="{{ $lng }}" @if ($latInput) data-lat-input="{{ $latInput }}" @endif
    @if ($lngInput) data-lng-input="{{ $lngInput }}" @endif
    @if ($autocompleteInput) data-autocomplete-input="{{ $autocompleteInput }}" @endif
    @if ($streetInput) data-street-input="{{ $streetInput }}" @endif
    @if ($formattedInput) data-formatted-input="{{ $formattedInput }}" @endif
    @if ($countryNameInput) data-country-name-input="{{ $countryNameInput }}" @endif
    @if ($countryCodeInput) data-country-code-input="{{ $countryCodeInput }}" @endif
    @if ($cityNameInput) data-city-name-input="{{ $cityNameInput }}" @endif
    @if ($districtNameInput) data-district-name-input="{{ $districtNameInput }}" @endif>
    <div class="rm-map-canvas border rounded {{ $heightClass }}" style="height: {{ (int) $height }}px;"></div>
</div>
