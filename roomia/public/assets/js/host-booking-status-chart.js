// public/assets/js/host-booking-status-chart.js
(() => {
    function getCssVar(name, fallback) {
        const v = getComputedStyle(document.documentElement)
            .getPropertyValue(name)
            .trim();
        return v || fallback;
    }

    function hexToRgba(hex, alpha) {
        if (!hex) return `rgba(0,0,0,${alpha})`;
        const h = hex.replace("#", "").trim();
        if (h.length !== 6) return `rgba(0,0,0,${alpha})`;
        const r = parseInt(h.slice(0, 2), 16);
        const g = parseInt(h.slice(2, 4), 16);
        const b = parseInt(h.slice(4, 6), 16);
        return `rgba(${r},${g},${b},${alpha})`;
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (typeof Chart === "undefined") return;

        const canvases = document.querySelectorAll(
            'canvas[data-rm-chart="booking-status"]'
        );
        if (!canvases.length) return;

        const primary = getCssVar("--rm-primary", "#003b95");
        const accent = getCssVar("--rm-accent", "#f6a100");
        const muted = getCssVar("--rm-gray-600", "#6c757d");

        // nhiều status thì lặp pattern màu (không gradient)
        const baseBg = [
            hexToRgba(primary, 0.22),
            hexToRgba(accent, 0.25),
            hexToRgba(primary, 0.12),
            hexToRgba(accent, 0.14),
            hexToRgba(muted, 0.18),
            hexToRgba(primary, 0.18),
            hexToRgba(accent, 0.18),
        ];

        const baseBorder = [
            primary,
            accent,
            primary,
            accent,
            muted,
            primary,
            accent,
        ];

        canvases.forEach((el) => {
            const labels = JSON.parse(el.dataset.labels || "[]");
            const values = JSON.parse(el.dataset.values || "[]");

            const bgColors = labels.map((_, i) => baseBg[i % baseBg.length]);
            const borderColors = labels.map(
                (_, i) => baseBorder[i % baseBorder.length]
            );

            new Chart(el, {
                type: "doughnut",
                data: {
                    labels,
                    datasets: [
                        {
                            data: values,
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: 1,
                            hoverOffset: 6,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "68%",
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: { color: muted, boxWidth: 12 },
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const label = ctx.label || "";
                                    const v = Number(ctx.parsed || 0);
                                    const total =
                                        ctx.dataset.data.reduce(
                                            (a, b) => a + Number(b || 0),
                                            0
                                        ) || 1;
                                    const pct = Math.round((v / total) * 100);
                                    return `${label}: ${v} (${pct}%)`;
                                },
                            },
                        },
                    },
                },
            });
        });
    });
})();
