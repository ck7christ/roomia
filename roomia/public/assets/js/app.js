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

// slider
function clamp(n, min, max) {
    return Math.max(min, Math.min(max, n));
}

function prefersReducedMotion() {
    return (
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches
    );
}

function snapToNearest(track, items) {
    const center = track.scrollLeft + track.clientWidth / 2;

    let best = null;
    let bestDist = Infinity;

    items.forEach((item) => {
        const rect = item.getBoundingClientRect();
        const itemCenter = item.offsetLeft + rect.width / 2;
        const dist = Math.abs(itemCenter - center);
        if (dist < bestDist) {
            bestDist = dist;
            best = item;
        }
    });

    if (!best) return;

    const bestRect = best.getBoundingClientRect();
    const target =
        best.offsetLeft - (track.clientWidth / 2 - bestRect.width / 2);
    track.scrollTo({
        left: target,
        behavior: prefersReducedMotion() ? "auto" : "smooth",
    });
}

function updateCoverflow(track, items, progressEl) {
    const trackCenter = track.scrollLeft + track.clientWidth / 2;

    let active = null;
    let activeDist = Infinity;

    items.forEach((item) => {
        const w = item.getBoundingClientRect().width || 1;
        const itemCenter = item.offsetLeft + w / 2;

        const distPx = itemCenter - trackCenter;
        const norm = clamp(distPx / (track.clientWidth / 2), -1, 1); // -1..1

        // coverflow nhẹ: item gần giữa scale lớn hơn, rotate nhẹ
        const scale = 1.02 - Math.abs(norm) * 0.1; // ~1.02 -> ~0.92
        const rotateY = -norm * 8; // -8deg..8deg
        const y = -(1 - Math.abs(norm)) * 6; // nhấc lên nhẹ
        const sat = 1 - Math.abs(norm) * 0.15;
        const bri = 1 - Math.abs(norm) * 0.08;

        const card = item.querySelector(".card");
        if (card) {
            card.style.setProperty("--roomia-s", scale.toFixed(3));
            card.style.setProperty("--roomia-ry", `${rotateY.toFixed(2)}deg`);
            card.style.setProperty("--roomia-y", `${y.toFixed(2)}px`);
            card.style.setProperty("--roomia-sat", sat.toFixed(3));
            card.style.setProperty("--roomia-bri", bri.toFixed(3));
        }

        const distAbs = Math.abs(distPx);
        if (distAbs < activeDist) {
            activeDist = distAbs;
            active = item;
        }
    });

    items.forEach((i) => i.classList.toggle("is-active", i === active));

    // progress
    if (progressEl) {
        const max = track.scrollWidth - track.clientWidth;
        const p = max <= 0 ? 0 : (track.scrollLeft / max) * 100;
        progressEl.style.setProperty("--roomia-progress", `${p.toFixed(1)}%`);
    }
}

function initRoomiaSlider(root) {
    const track = root.querySelector(".roomia-slider__track");
    const prevBtn = root.querySelector(".roomia-slider__btn--prev");
    const nextBtn = root.querySelector(".roomia-slider__btn--next");
    const progress = root.querySelector(".roomia-slider__progress");

    if (!track) return;

    const items = Array.from(root.querySelectorAll(".roomia-slider__item"));
    if (!items.length) return;

    // reveal
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) e.target.classList.add("is-visible");
            });
        },
        { root: track, threshold: 0.25 }
    );

    items.forEach((it) => io.observe(it));

    // arrows
    const step = () => Math.floor(track.clientWidth * 0.85);

    prevBtn?.addEventListener("click", () => {
        track.scrollBy({
            left: -step(),
            behavior: prefersReducedMotion() ? "auto" : "smooth",
        });
    });

    nextBtn?.addEventListener("click", () => {
        track.scrollBy({
            left: step(),
            behavior: prefersReducedMotion() ? "auto" : "smooth",
        });
    });

    // drag to scroll
    // drag to scroll (KHÔNG ĂN CLICK LINK)
    let isDown = false;
    let dragMoved = false;
    let startX = 0;
    let startLeft = 0;

    const DRAG_THRESHOLD = 6; // px

    track.addEventListener("pointerdown", (e) => {
        // Nếu click vào element tương tác thì cho click bình thường
        if (e.target.closest("a, button, input, textarea, select, label"))
            return;

        isDown = true;
        dragMoved = false;
        startX = e.clientX;
        startLeft = track.scrollLeft;

        // chỉ set cursor/drag state, chưa capture vội
    });

    track.addEventListener("pointermove", (e) => {
        if (!isDown) return;

        const dx = e.clientX - startX;

        // Chưa vượt ngưỡng thì không coi là drag (để click không bị mất)
        if (!dragMoved && Math.abs(dx) < DRAG_THRESHOLD) return;

        if (!dragMoved) {
            dragMoved = true;
            track.classList.add("is-dragging");
            try {
                track.setPointerCapture(e.pointerId);
            } catch (_) {}
        }

        track.scrollLeft = startLeft - dx;
    });

    const endDrag = () => {
        if (!isDown) return;
        isDown = false;

        if (dragMoved) {
            track.classList.remove("is-dragging");
            snapToNearest(track, items);
        }
    };

    track.addEventListener("pointerup", endDrag);
    track.addEventListener("pointercancel", endDrag);
    track.addEventListener("mouseleave", endDrag);

    // Nếu vừa kéo thì chặn click “lạc”
    track.addEventListener(
        "click",
        (e) => {
            if (dragMoved) {
                e.preventDefault();
                e.stopPropagation();
                dragMoved = false;
            }
        },
        true
    );

    // coverflow update (rAF throttle)
    let raf = null;
    const onScroll = () => {
        if (raf) return;
        raf = requestAnimationFrame(() => {
            raf = null;
            updateCoverflow(track, items, progress);
        });
    };

    track.addEventListener("scroll", onScroll, { passive: true });

    // init
    updateCoverflow(track, items, progress);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-roomia-slider]").forEach(initRoomiaSlider);
});
document.addEventListener("DOMContentLoaded", () => {
    const poster = document.querySelector("[data-explore-rotator]");
    if (!poster) return;

    let slides = [];
    try {
        slides = JSON.parse(poster.getAttribute("data-slides") || "[]");
    } catch (e) {
        slides = [];
    }
    if (!slides.length) return;

    const img = poster.querySelector(".explore__img");
    const nameEl = poster.querySelector("[data-explore-name]");
    const countEl = poster.querySelector("[data-explore-count]");
    const bg = document.querySelector("[data-explore-bg]");
    const dots = Array.from(document.querySelectorAll("[data-explore-dot]"));

    let idx = 0;
    const intervalMs = 3500;

    const setActiveDot = (i) => {
        dots.forEach((d) => d.classList.remove("is-active"));
        const el = dots.find(
            (d) => String(d.getAttribute("data-explore-dot")) === String(i)
        );
        if (el) el.classList.add("is-active");
    };

    const apply = (s) => {
        if (!s) return;
        if (img) img.src = s.image;
        if (bg) bg.src = s.image;
        if (nameEl) nameEl.textContent = s.name || "City";
        if (countEl) countEl.textContent = String(s.count ?? 0);
        if (s.url) poster.setAttribute("href", s.url);
        setActiveDot(idx);
    };

    apply(slides[0]);

    setInterval(() => {
        idx = (idx + 1) % slides.length;

        poster.classList.add("is-fading");
        setTimeout(() => {
            apply(slides[idx]);
            poster.classList.remove("is-fading");
        }, 220);
    }, intervalMs);
});
