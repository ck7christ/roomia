// public/assets/js/host-revenue-chart.js
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

    function formatVND(value) {
        const n = Number(value || 0);
        return n.toLocaleString("vi-VN");
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (typeof Chart === "undefined") return;

        const canvases = document.querySelectorAll(
            'canvas[data-rm-chart="revenue-monthly"]'
        );
        if (!canvases.length) return;

        const primary = getCssVar("--rm-primary", "#003b95");
        const accent = getCssVar("--rm-accent", "#f6a100");
        const muted = getCssVar("--rm-gray-600", "#6c757d");

        canvases.forEach((el) => {
            const labels = JSON.parse(el.dataset.labels || "[]");
            const revenues = JSON.parse(el.dataset.revenues || "[]");
            const bookings = JSON.parse(el.dataset.bookings || "[]");

            // Bar (revenue) + Line (bookings) cho dễ nhìn
            new Chart(el, {
                data: {
                    labels,
                    datasets: [
                        {
                            type: "bar",
                            label: "Doanh thu",
                            data: revenues,
                            backgroundColor: hexToRgba(accent, 0.25),
                            borderColor: accent,
                            borderWidth: 1,
                            borderRadius: 10,
                            yAxisID: "y",
                        },
                        {
                            type: "line",
                            label: "Số đặt phòng",
                            data: bookings,
                            borderColor: primary,
                            backgroundColor: hexToRgba(primary, 0.12),
                            pointBackgroundColor: primary,
                            pointBorderColor: primary,
                            pointRadius: 3,
                            tension: 0.35,
                            yAxisID: "y1",
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: muted,
                                boxWidth: 12,
                            },
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false,
                            callbacks: {
                                label: (ctx) => {
                                    const label = ctx.dataset.label || "";
                                    const v = ctx.parsed.y;

                                    if (ctx.dataset.yAxisID === "y") {
                                        return `${label}: ${formatVND(v)} đ`;
                                    }
                                    return `${label}: ${formatVND(v)}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: muted },
                        },
                        y: {
                            position: "left",
                            beginAtZero: true,
                            ticks: {
                                color: muted,
                                callback: (v) => formatVND(v),
                            },
                        },
                        y1: {
                            position: "right",
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                            ticks: { color: muted },
                        },
                    },
                },
            });
        });
    });
})();
