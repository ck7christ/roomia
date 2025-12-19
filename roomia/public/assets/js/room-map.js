// public/assets/js/room-map.js
(function () {
    function qs(sel, root = document) {
        return sel ? root.querySelector(sel) : null;
    }

    function num(v) {
        const n = parseFloat(v);
        return Number.isFinite(n) ? n : null;
    }

    function setVal(el, v) {
        if (!el) return;
        el.value = (v ?? "") + "";
    }

    function parseCenter(wrapper, latInput, lngInput) {
        const dLat = num(wrapper.dataset.lat);
        const dLng = num(wrapper.dataset.lng);

        const iLat = num(latInput?.value);
        const iLng = num(lngInput?.value);

        if (dLat !== null && dLng !== null) return { lat: dLat, lng: dLng };
        if (iLat !== null && iLng !== null) return { lat: iLat, lng: iLng };

        // fallback VN
        return { lat: 10.7769, lng: 106.7009 };
    }

    function applyAddressComponents(place, els) {
        if (!place?.address_components) return;

        let streetNumber = "";
        let route = "";

        let countryName = "";
        let countryCode = "";
        let cityName = "";
        let districtName = "";

        for (const c of place.address_components) {
            const types = c.types || [];

            if (types.includes("street_number")) streetNumber = c.long_name;
            if (types.includes("route")) route = c.long_name;

            if (types.includes("country")) {
                countryName = c.long_name;
                countryCode = c.short_name;
            }

            // city hay gặp:
            if (types.includes("administrative_area_level_1"))
                cityName = c.long_name; // tỉnh/thành
            if (!cityName && types.includes("locality")) cityName = c.long_name;

            // district hay gặp:
            if (types.includes("administrative_area_level_2"))
                districtName = c.long_name;
            if (!districtName && types.includes("sublocality_level_1"))
                districtName = c.long_name;
        }

        const street = [streetNumber, route].filter(Boolean).join(" ").trim();

        setVal(els.streetInput, street || els.streetInput?.value);
        setVal(
            els.formattedInput,
            place.formatted_address || els.formattedInput?.value
        );

        // optional hidden fields (nếu bạn đang dùng)
        setVal(els.countryNameInput, countryName);
        setVal(els.countryCodeInput, countryCode);
        setVal(els.cityNameInput, cityName);
        setVal(els.districtNameInput, districtName);
    }

    function initOne(wrapper) {
        const canvas = qs(wrapper.dataset.canvas || ".rm-map-canvas", wrapper);
        if (!canvas) return;

        const mode = (wrapper.dataset.mode || "edit").toLowerCase(); // edit | show

        const latInput = qs(wrapper.dataset.latInput);
        const lngInput = qs(wrapper.dataset.lngInput);

        const center = parseCenter(wrapper, latInput, lngInput);
        const zoom =
            num(wrapper.dataset.zoom) ??
            (num(center.lat) && num(center.lng) ? 14 : 6);

        const map = new google.maps.Map(canvas, {
            center,
            zoom,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        const marker = new google.maps.Marker({
            position: center,
            map,
            draggable: mode === "edit",
        });

        // sync input lần đầu (nếu có)
        if (mode === "edit") {
            setVal(latInput, center.lat);
            setVal(lngInput, center.lng);
        }

        function setPos(lat, lng) {
            marker.setPosition({ lat, lng });
            map.panTo({ lat, lng });
            if (mode === "edit") {
                setVal(latInput, lat);
                setVal(lngInput, lng);
            }
        }

        if (mode === "edit") {
            map.addListener("click", (e) =>
                setPos(e.latLng.lat(), e.latLng.lng())
            );
            marker.addListener("dragend", () => {
                const p = marker.getPosition();
                if (!p) return;
                setPos(p.lat(), p.lng());
            });
        }

        // Optional: Autocomplete
        const acInput = qs(wrapper.dataset.autocompleteInput);
        if (acInput && google.maps.places?.Autocomplete) {
            const ac = new google.maps.places.Autocomplete(acInput, {
                fields: ["geometry", "formatted_address", "address_components"],
            });
            ac.bindTo("bounds", map);

            const els = {
                streetInput: qs(wrapper.dataset.streetInput),
                formattedInput: qs(wrapper.dataset.formattedInput),

                // hidden optional
                countryNameInput: qs(wrapper.dataset.countryNameInput),
                countryCodeInput: qs(wrapper.dataset.countryCodeInput),
                cityNameInput: qs(wrapper.dataset.cityNameInput),
                districtNameInput: qs(wrapper.dataset.districtNameInput),
            };

            ac.addListener("place_changed", () => {
                const place = ac.getPlace();
                if (!place?.geometry?.location) return;

                const lat = place.geometry.location.lat();
                const lng = place.geometry.location.lng();

                setPos(lat, lng);
                applyAddressComponents(place, els);
            });
            if (els.streetInput && place.formatted_address) {
                els.streetInput.value = place.formatted_address;
            }
        }
    }
    function initRoomsListMap() {
        document.querySelectorAll("[data-rooms-list-map]").forEach((wrap) => {
            const canvas = wrap.querySelector(".rm-map-canvas");
            if (!canvas || !window.google?.maps) return;

            let rooms = [];
            try {
                rooms = JSON.parse(wrap.getAttribute("data-rooms") || "[]");
            } catch (e) {
                rooms = [];
            }
            if (!rooms.length) return;

            const map = new google.maps.Map(canvas, {
                center: { lat: 10.7769, lng: 106.7009 },
                zoom: 6,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            const bounds = new google.maps.LatLngBounds();
            const info = new google.maps.InfoWindow();

            rooms.forEach((r) => {
                const lat = parseFloat(r.lat);
                const lng = parseFloat(r.lng);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

                const pos = { lat, lng };
                const marker = new google.maps.Marker({ map, position: pos });
                bounds.extend(pos);

                marker.addListener("click", () => {
                    const title = (r.title || "").replace(/</g, "&lt;");
                    info.setContent(
                        `<div style="font-weight:600;margin-bottom:4px;">${title}</div>
           <a href="${r.url}">Xem chi tiết</a>`
                    );
                    info.open({ map, anchor: marker });
                });
            });

            if (!bounds.isEmpty()) map.fitBounds(bounds);
        });
    }

    // callback cho Google Maps script
    window.initRoomMap = function () {
        document.querySelectorAll("[data-room-map]").forEach(initOne);
        initRoomsListMap();
    };
})();
