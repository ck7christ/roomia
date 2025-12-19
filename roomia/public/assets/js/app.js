/* =========================================================
   Roomia - app.js (optimized)
   - Single DOMContentLoaded
   - Event delegation (fewer listeners)
   - Guards for Bootstrap presence
   - Prevent duplicated snowflakes
   ========================================================= */
(() => {
    "use strict";

    // ---------- Helpers ----------
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const clamp = (n, min, max) => Math.max(min, Math.min(max, n));
    const prefersReducedMotion = () =>
        !!window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const hasBootstrap = () => typeof window.bootstrap !== "undefined";

    // ---------- 1) Auth slider mode ----------
    function initAuthSlider() {
        const card = $("#authSliderCard");
        if (!card) return;

        document.addEventListener("click", (e) => {
            const btn = e.target.closest("[data-auth-mode]");
            if (!btn) return;

            e.preventDefault();
            const mode = btn.getAttribute("data-auth-mode");
            card.classList.toggle("auth-mode-signup", mode === "signup");
        });
    }

    // ---------- 2) Simple horizontal scroll buttons (.rs-btn) ----------
    function initRoomScrollerButtons() {
        document.addEventListener("click", (e) => {
            const btn = e.target.closest(".rs-btn");
            if (!btn) return;

            const target = btn.getAttribute("data-rs-target");
            const dir = parseInt(btn.getAttribute("data-rs-dir") || "1", 10);
            const track = target ? document.querySelector(target) : null;
            if (!track) return;

            track.scrollBy({
                left: track.clientWidth * dir,
                behavior: "smooth",
            });
        });
    }

    // ---------- 3) Snow effect ----------
    // Tip: tắt snow trên page nào đó bằng cách set: <body data-rm-snow="0">
    function initSnowEffect() {
        const body = document.body;
        if (!body) return;

        if (body.getAttribute("data-rm-snow") === "0") return;

        // Prevent duplicated snowflakes (nếu script load lại)
        if ($(".snowflake")) return;

        const SNOWFLAKE_COUNT = 60;
        const frag = document.createDocumentFragment();

        for (let i = 0; i < SNOWFLAKE_COUNT; i++) {
            const snowflake = document.createElement("span");
            snowflake.className = "snowflake";
            snowflake.textContent = "❄";

            const size = (Math.random() * 0.8 + 0.6).toFixed(2);
            snowflake.style.fontSize = size + "rem";
            snowflake.style.left = Math.random() * 100 + "vw";

            const duration = (Math.random() * 15 + 10).toFixed(2);
            snowflake.style.animationDuration = duration + "s";

            const delay = (Math.random() * -20).toFixed(2);
            snowflake.style.animationDelay = delay + "s";

            frag.appendChild(snowflake);
        }

        body.appendChild(frag);
    }

    // ---------- 4) Host Rooms: dynamic image inputs ----------
    function initDynamicImageInputs() {
        const wrapper = $("#image-input-wrapper");
        if (!wrapper) return;

        const refreshButtons = () => {
            const rows = $$(".image-input-row", wrapper);
            rows.forEach((row, index) => {
                const btn = $("button", row);
                if (!btn) return;

                const isLast = index === rows.length - 1;

                if (isLast) {
                    btn.classList.remove("btn-danger", "btn-remove-image");
                    btn.classList.add("btn-outline-secondary", "btn-add-image");
                    btn.innerHTML = '<i class="fas fa-plus"></i>';
                } else {
                    btn.classList.remove(
                        "btn-outline-secondary",
                        "btn-add-image"
                    );
                    btn.classList.add("btn-danger", "btn-remove-image");
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
                return;
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

    // ---------- 5) Host Rooms: Modal ảnh + Carousel (#roomImageModal) ----------
    function initHostRoomImageModal() {
        const modalEl = $("#roomImageModal");
        const carouselEl = $("#roomImageCarousel");
        if (!modalEl || !carouselEl || !hasBootstrap()) return;

        // Create instances once
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        const carousel =
            window.bootstrap.Carousel.getInstance(carouselEl) ||
            new window.bootstrap.Carousel(carouselEl, {
                interval: false,
                ride: false,
            });

        // Event delegation
        document.addEventListener("click", (e) => {
            const thumb = e.target.closest(".room-image-thumb");
            if (!thumb) return;

            e.preventDefault();
            const index = parseInt(thumb.getAttribute("data-index") || "0", 10);
            carousel.to(index);
            modal.show();
        });
    }

    // ---------- 6) Guest picker (Adults/Children/Rooms) ----------
    function initGuestPickers() {
        $$(".home-search-form").forEach((form) => {
            const dropdown = $(".guest-dropdown", form);
            if (!dropdown) return;

            const summaryText = $(".guest-summary-text", form);

            const hiddenAdults = dropdown.querySelector('input[name="adults"]');
            const hiddenChildren = dropdown.querySelector(
                'input[name="children"]'
            );
            const hiddenRooms = dropdown.querySelector('input[name="rooms"]');

            if (!hiddenAdults || !hiddenChildren || !hiddenRooms) return;

            const getStepper = (field) =>
                dropdown.querySelector(`.guest-stepper[data-field="${field}"]`);

            const getVal = (field) => {
                const stepper = getStepper(field);
                if (!stepper) return 0;
                const v = $(".guest-value", stepper);
                return parseInt((v && v.value) || "0", 10);
            };

            const updateSummary = () => {
                if (!summaryText) return;
                const a = getVal("adults");
                const c = getVal("children");
                const r = getVal("rooms");
                summaryText.textContent = `${a} Người lớn - ${c} Trẻ em - ${r} Phòng`;
            };

            const setVal = (field, next) => {
                const stepper = getStepper(field);
                if (!stepper) return;

                const min = parseInt(
                    stepper.getAttribute("data-min") || "0",
                    10
                );
                const max = parseInt(
                    stepper.getAttribute("data-max") || "99",
                    10
                );
                const value = Math.max(min, Math.min(max, next));

                const valueInput = $(".guest-value", stepper);
                if (valueInput) valueInput.value = String(value);

                if (field === "adults") hiddenAdults.value = String(value);
                if (field === "children") hiddenChildren.value = String(value);
                if (field === "rooms") hiddenRooms.value = String(value);

                updateSummary();
            };

            // Delegation for +/- and apply
            dropdown.addEventListener("click", (e) => {
                const minus = e.target.closest(".guest-minus");
                const plus = e.target.closest(".guest-plus");
                const apply = e.target.closest(".guest-apply");

                if (minus || plus) {
                    const stepper = e.target.closest(".guest-stepper");
                    if (!stepper) return;

                    const field = stepper.getAttribute("data-field");
                    if (!field) return;

                    const cur = getVal(field);
                    setVal(field, cur + (plus ? 1 : -1));
                    return;
                }

                if (apply) {
                    const toggle = $(".guest-summary", form);
                    if (!toggle) return;

                    if (hasBootstrap()) {
                        const dd =
                            window.bootstrap.Dropdown.getOrCreateInstance(
                                toggle
                            );
                        dd.hide();
                    }
                }
            });

            // Initial sync
            setVal("adults", parseInt(hiddenAdults.value || "2", 10));
            setVal("children", parseInt(hiddenChildren.value || "0", 10));
            setVal("rooms", parseInt(hiddenRooms.value || "1", 10));
        });
    }

    // ---------- 7) Roomia coverflow slider ----------
    function snapToNearest(track, items) {
        const center = track.scrollLeft + track.clientWidth / 2;

        let best = null;
        let bestDist = Infinity;

        items.forEach((item) => {
            const w = item.getBoundingClientRect().width || 1;
            const itemCenter = item.offsetLeft + w / 2;
            const dist = Math.abs(itemCenter - center);
            if (dist < bestDist) {
                bestDist = dist;
                best = item;
            }
        });

        if (!best) return;

        const bw = best.getBoundingClientRect().width || 1;
        const target = best.offsetLeft - (track.clientWidth / 2 - bw / 2);

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
            const norm = clamp(distPx / (track.clientWidth / 2), -1, 1);

            const scale = 1.02 - Math.abs(norm) * 0.1;
            const rotateY = -norm * 8;
            const y = -(1 - Math.abs(norm)) * 6;
            const sat = 1 - Math.abs(norm) * 0.15;
            const bri = 1 - Math.abs(norm) * 0.08;

            const card = item.querySelector(".card");
            if (card) {
                card.style.setProperty("--roomia-s", scale.toFixed(3));
                card.style.setProperty(
                    "--roomia-ry",
                    `${rotateY.toFixed(2)}deg`
                );
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

        if (progressEl) {
            const max = track.scrollWidth - track.clientWidth;
            const p = max <= 0 ? 0 : (track.scrollLeft / max) * 100;
            progressEl.style.setProperty(
                "--roomia-progress",
                `${p.toFixed(1)}%`
            );
        }
    }

    function initRoomiaSlider(root) {
        const track = $(".roomia-slider__track", root);
        const prevBtn = $(".roomia-slider__btn--prev", root);
        const nextBtn = $(".roomia-slider__btn--next", root);
        const progress = $(".roomia-slider__progress", root);

        if (!track) return;

        const items = $$(".roomia-slider__item", root);
        if (!items.length) return;

        // Reveal items
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting)
                        entry.target.classList.add("is-visible");
                });
            },
            { root: track, threshold: 0.25 }
        );
        items.forEach((it) => io.observe(it));

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

        // Drag to scroll (không ăn click link)
        let isDown = false;
        let dragMoved = false;
        let startX = 0;
        let startLeft = 0;
        const DRAG_THRESHOLD = 6;

        track.addEventListener("pointerdown", (e) => {
            if (e.target.closest("a, button, input, textarea, select, label"))
                return;

            isDown = true;
            dragMoved = false;
            startX = e.clientX;
            startLeft = track.scrollLeft;
        });

        track.addEventListener("pointermove", (e) => {
            if (!isDown) return;

            const dx = e.clientX - startX;
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

        updateCoverflow(track, items, progress);
    }

    function initRoomiaSliders() {
        $$("[data-roomia-slider]").forEach(initRoomiaSlider);
    }

    // ---------- 8) Explore rotator ----------
    function initExploreRotator() {
        const poster = $("[data-explore-rotator]");
        if (!poster) return;

        let slides = [];
        try {
            slides = JSON.parse(poster.getAttribute("data-slides") || "[]");
        } catch (_) {
            slides = [];
        }
        if (!slides.length) return;

        const img = $(".explore__img", poster);
        const nameEl = poster.querySelector("[data-explore-name]");
        const countEl = poster.querySelector("[data-explore-count]");
        const bg = $("[data-explore-bg]");
        const dots = $$("[data-explore-dot]");

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
    }

    // ---------- 9) Gallery modal sync (.rm-gallery-open + .rm-gallery-modal) ----------
    function initRoomGalleryModalSync() {
        // store startIndex on click
        document.addEventListener("click", (e) => {
            const trigger = e.target.closest(".rm-gallery-open");
            if (!trigger) return;

            e.preventDefault();

            const idx = parseInt(trigger.dataset.index || "0", 10);
            const target = trigger.getAttribute("data-bs-target");
            if (!target) return;

            const modalEl = document.querySelector(target);
            if (!modalEl) return;

            modalEl.dataset.startIndex = String(idx);
        });

        // when modal shown -> move carousel to startIndex
        document.addEventListener("shown.bs.modal", (e) => {
            const modalEl = e.target;
            if (!modalEl.classList.contains("rm-gallery-modal")) return;
            if (!hasBootstrap()) return;

            const startIndex = parseInt(modalEl.dataset.startIndex || "0", 10);
            const carouselEl = $(".carousel", modalEl);
            if (!carouselEl) return;

            const instance =
                window.bootstrap.Carousel.getInstance(carouselEl) ||
                new window.bootstrap.Carousel(carouselEl, {
                    interval: false,
                    ride: false,
                });

            instance.to(startIndex);
        });
    }

    // ---------- Boot ----------
    document.addEventListener("DOMContentLoaded", () => {
        initAuthSlider();
        initRoomScrollerButtons();
        initSnowEffect();
        initDynamicImageInputs();
        initHostRoomImageModal();
        initGuestPickers();
        initRoomiaSliders();
        initExploreRotator();
        initRoomGalleryModalSync();
    });
})();
