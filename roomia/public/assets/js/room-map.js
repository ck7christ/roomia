// public/assets/js/room-map.js
window.initRoomMap = function () {
    const mapEl = document.getElementById("room-map");
    if (!mapEl) return;

    const latAttr = parseFloat(mapEl.dataset.lat);
    const lngAttr = parseFloat(mapEl.dataset.lng);

    const center = {
        lat: isNaN(latAttr) ? 10.762622 : latAttr,
        lng: isNaN(lngAttr) ? 106.660172 : lngAttr,
    };

    const latInput = document.getElementById("lat");
    const lngInput = document.getElementById("lng");

    const map = new google.maps.Map(mapEl, { center, zoom: 15 });

    let marker = new google.maps.Marker({
        position: center,
        map,
        draggable: true,
    });

    function updateInputs(pos) {
        if (!latInput || !lngInput) return;
        latInput.value = pos.lat();
        lngInput.value = pos.lng();
    }

    updateInputs(marker.getPosition());

    map.addListener("click", (e) => {
        marker.setPosition(e.latLng);
        updateInputs(e.latLng);
    });

    marker.addListener("dragend", () => {
        updateInputs(marker.getPosition());
    });

    // ========================
    // AUTOCOMPLETE ĐỊA CHỈ
    // ========================
    const streetInput = document.getElementById("street");
    const formattedInput = document.getElementById("formatted_address");

    // 3 select ẩn (vẫn submit bình thường)
    const countrySelect = document.getElementById("country_id");
    const citySelect = document.getElementById("city_id");
    const districtSelect = document.getElementById("district_id");

    // ✅ NEW: hidden name/code để backend map
    const countryNameInput = document.getElementById("country_name");
    const countryCodeInput = document.getElementById("country_code");
    const cityNameInput = document.getElementById("city_name");
    const districtNameInput = document.getElementById("district_name");

    // Chuẩn hoá text để so khớp (bỏ dấu, bỏ tiền tố hành chính, bỏ ký tự thừa)
    function normalizeText(text) {
        return (text || "")
            .toString()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "") // bỏ dấu
            .toLowerCase()
            .replace(
                /\b(thanh pho|tp\.?|tinh|quan|huyen|thi xa|thi tran|phuong|xa)\b/g,
                ""
            )
            .replace(/[^\w\s]/g, " ")
            .replace(/\s+/g, " ")
            .trim();
    }

    // Chọn option theo text (so khớp “mềm”)
    function selectByText(selectEl, text) {
        if (!selectEl || !text) return false;

        const target = normalizeText(text);
        if (!target) return false;

        const options = Array.from(selectEl.options || []);

        // 1) match tuyệt đối sau normalize
        let found = options.find((opt) => normalizeText(opt.text) === target);

        // 2) fallback match contains (đề phòng DB có “Thành phố Đà Nẵng”)
        if (!found) {
            found = options.find((opt) =>
                normalizeText(opt.text).includes(target)
            );
        }

        if (found) {
            // set value chắc chắn
            selectEl.value = found.value;
            // bắn change để các đoạn JS khác (nếu có) bắt sự kiện
            selectEl.dispatchEvent(new Event("change", { bubbles: true }));
            return true;
        }
        return false;
    }

    if (streetInput) {
        const autocomplete = new google.maps.places.Autocomplete(streetInput, {
            fields: ["geometry", "formatted_address", "address_components"],
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            const location = place.geometry.location;

            map.setCenter(location);
            marker.setPosition(location);
            updateInputs(location);

            if (place.formatted_address) {
                streetInput.value = place.formatted_address;
                if (formattedInput)
                    formattedInput.value = place.formatted_address;
            }

            // Parse address_components
            let countryName = "";
            let countryCode = "";
            let cityName = "";
            let districtName = "";

            (place.address_components || []).forEach((component) => {
                const types = component.types || [];

                if (types.includes("country")) {
                    countryName = component.long_name; // vd: "Vietnam"
                    countryCode = component.short_name; // vd: "VN"
                }

                // Tỉnh/TP trực thuộc TW
                if (types.includes("administrative_area_level_1")) {
                    cityName = component.long_name;
                }

                // Quận/Huyện (VN thường là level_2, nhưng đôi khi rơi vào sublocality/locality)
                if (types.includes("administrative_area_level_2")) {
                    districtName = component.long_name;
                }

                if (!districtName && types.includes("sublocality_level_1")) {
                    districtName = component.long_name;
                }
            });

            // ✅ đổ hidden name/code để backend map nếu dropdown fail
            if (countryNameInput) countryNameInput.value = countryName;
            if (countryCodeInput) countryCodeInput.value = countryCode;
            if (cityNameInput) cityNameInput.value = cityName;
            if (districtNameInput) districtNameInput.value = districtName;

            // Special-case nhỏ: VN hay lệch “Vietnam” vs “Việt Nam”
            // (giữ nguyên vẫn OK vì normalizeText đã bỏ dấu; nhưng để chắc hơn)
            const countryNameForSelect =
                normalizeText(countryName) === "vietnam"
                    ? "Viet Nam"
                    : countryName;

            selectByText(countrySelect, countryNameForSelect);
            selectByText(citySelect, cityName);
            selectByText(districtSelect, districtName);
        });
    }
};
