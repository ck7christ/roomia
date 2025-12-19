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

    $mapId = $mapId ?? 'rm-map-' . uniqid();
@endphp

<div id="{{ $mapId }}" data-room-map data-mode="{{ $mode }}" data-lat="{{ $lat }}"
    data-lng="{{ $lng }}" @if ($latInput) data-lat-input="{{ $latInput }}" @endif
    @if ($lngInput) data-lng-input="{{ $lngInput }}" @endif
    @if ($autocompleteInput) data-autocomplete-input="{{ $autocompleteInput }}" @endif
    @if ($streetInput) data-street-input="{{ $streetInput }}" @endif
    @if ($formattedInput) data-formatted-input="{{ $formattedInput }}" @endif>
    <div class="rm-map-canvas border rounded" style="height: {{ (int) $height }}px;"></div>
</div>
