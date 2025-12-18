document.addEventListener("DOMContentLoaded", function () {
    // ===== Auth slider mode =====
    const card = document.getElementById("authSliderCard");
    if (card) {
        document.querySelectorAll("[data-auth-mode]").forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                const mode = btn.getAttribute("data-auth-mode");
                if (mode === "signup") {
                    card.classList.add("auth-mode-signup");
                } else {
                    card.classList.remove("auth-mode-signup");
                }
            });
        });
    }

    // ===== Snow effect =====
    const SNOWFLAKE_COUNT = 60;
    const body = document.body;

    for (let i = 0; i < SNOWFLAKE_COUNT; i++) {
        const snowflake = document.createElement("span");
        snowflake.classList.add("snowflake");
        snowflake.textContent = "❄";

        const size = (Math.random() * 0.8 + 0.6).toFixed(2);
        snowflake.style.fontSize = size + "rem";

        snowflake.style.left = Math.random() * 100 + "vw";

        const duration = (Math.random() * 15 + 10).toFixed(2);
        snowflake.style.animationDuration = duration + "s";

        const delay = (Math.random() * -20).toFixed(2);
        snowflake.style.animationDelay = delay + "s";

        body.appendChild(snowflake);
    }

    // ===== Host Rooms: dynamic image inputs =====
    const wrapper = document.getElementById("image-input-wrapper");
    if (wrapper) {
        const refreshButtons = () => {
            const rows = wrapper.querySelectorAll(".image-input-row");
            rows.forEach((row, index) => {
                const btn = row.querySelector("button");
                if (!btn) return;

                if (index === rows.length - 1) {
                    btn.classList.remove("btn-danger", "btn-remove-image");
                    btn.classList.add("btn-outline-secondary", "btn-add-image");
                    // FA5
                    btn.innerHTML = '<i class="fas fa-plus"></i>';
                } else {
                    btn.classList.remove(
                        "btn-outline-secondary",
                        "btn-add-image"
                    );
                    btn.classList.add("btn-danger", "btn-remove-image");
                    // FA5
                    btn.innerHTML = '<i class="fas fa-trash"></i>';
                }
            });
        };

        wrapper.addEventListener("click", (e) => {
            const addBtn = e.target.closest(".btn-add-image");
            const removeBtn = e.target.closest(".btn-remove-image");

            if (addBtn) {
                const lastRow = wrapper.querySelector(
                    ".image-input-row:last-child"
                );
                if (!lastRow) return;

                const newRow = lastRow.cloneNode(true);
                const input = newRow.querySelector('input[type="file"]');
                if (input) {
                    input.value = "";
                    if (input.id === "images") input.id = "";
                }

                wrapper.appendChild(newRow);
                refreshButtons();
            }

            if (removeBtn) {
                const row = removeBtn.closest(".image-input-row");
                if (!row) return;

                const rows = wrapper.querySelectorAll(".image-input-row");
                if (rows.length > 1) {
                    row.remove();
                    refreshButtons();
                }
            }
        });

        refreshButtons();
    }

    // ===== Host Rooms: Modal ảnh + Carousel =====
    const modalEl = document.getElementById("roomImageModal");
    const carouselEl = document.getElementById("roomImageCarousel");

    if (modalEl && carouselEl && typeof bootstrap !== "undefined") {
        const modal = new bootstrap.Modal(modalEl);
        const carousel = new bootstrap.Carousel(carouselEl, {
            interval: false,
            ride: false,
        });

        document.querySelectorAll(".room-image-thumb").forEach((thumb) => {
            thumb.addEventListener("click", (e) => {
                e.preventDefault();

                const index = parseInt(
                    thumb.getAttribute("data-index") || "0",
                    10
                );
                carousel.to(index);
                modal.show();
            });
        });
    }
});
// ===== Guest picker: Adults / Children / Rooms (Home search) =====
document.querySelectorAll(".home-search-form").forEach((form) => {
    const dropdown = form.querySelector(".guest-dropdown");
    if (!dropdown) return;

    const summaryText = form.querySelector(".guest-summary-text");
    const hiddenAdults = dropdown.querySelector('input[name="adults"]');
    const hiddenChildren = dropdown.querySelector('input[name="children"]');
    const hiddenRooms = dropdown.querySelector('input[name="rooms"]');

    const getVal = (field) => {
        const stepper = dropdown.querySelector(
            `.guest-stepper[data-field="${field}"]`
        );
        if (!stepper) return 0;
        const v = stepper.querySelector(".guest-value");
        return parseInt(v.value || "0", 10);
    };

    const setVal = (field, next) => {
        const stepper = dropdown.querySelector(
            `.guest-stepper[data-field="${field}"]`
        );
        if (!stepper) return;

        const min = parseInt(stepper.getAttribute("data-min") || "0", 10);
        const max = parseInt(stepper.getAttribute("data-max") || "99", 10);

        const value = Math.max(min, Math.min(max, next));
        stepper.querySelector(".guest-value").value = String(value);

        if (field === "adults") hiddenAdults.value = String(value);
        if (field === "children") hiddenChildren.value = String(value);
        if (field === "rooms") hiddenRooms.value = String(value);

        // Update summary
        if (summaryText) {
            const a = getVal("adults");
            const c = getVal("children");
            const r = getVal("rooms");
            summaryText.textContent = `${a} Người lớn - ${c} Trẻ em - ${r} Phòng`;
        }
    };

    // Bind +/-
    dropdown.querySelectorAll(".guest-stepper").forEach((stepper) => {
        const field = stepper.getAttribute("data-field");

        stepper.querySelector(".guest-minus")?.addEventListener("click", () => {
            setVal(field, getVal(field) - 1);
        });

        stepper.querySelector(".guest-plus")?.addEventListener("click", () => {
            setVal(field, getVal(field) + 1);
        });
    });

    // Apply closes dropdown
    dropdown.querySelector(".guest-apply")?.addEventListener("click", () => {
        const toggle = form.querySelector(".guest-summary");
        if (!toggle) return;

        // Bootstrap 5 dropdown instance
        const dd = bootstrap.Dropdown.getOrCreateInstance(toggle);
        dd.hide();
    });

    // Initial sync (đảm bảo hidden + summary đúng)
    setVal("adults", parseInt(hiddenAdults.value || "2", 10));
    setVal("children", parseInt(hiddenChildren.value || "0", 10));
    setVal("rooms", parseInt(hiddenRooms.value || "1", 10));
});
