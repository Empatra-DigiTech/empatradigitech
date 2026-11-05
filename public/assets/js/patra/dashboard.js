/* PATRA DASHBOARD
 * Bergantung pada: Chart.js 2.9.x (sudah dimuat di layouts/script.blade.php)
 * dan window.PATRA_DASH yang dirender oleh pages/patra/index.blade.php.
 */
(function () {
    'use strict';

    var DATA = window.PATRA_DASH || {};
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var NAVY = '#0f3c5d';
    var RED = '#b02e36';
    var GREEN = '#2e9e5b';
    var GRID = 'rgba(100, 116, 139, 0.12)';
    var TICK = '#64748b';

    /* ---------------------------------------------------------- Jam */
    var TZ = 'Asia/Jakarta';
    var clockEl = document.getElementById('pd-clock');
    var dateEl = document.getElementById('pd-date');

    function tick() {
        var now = new Date();
        try {
            if (clockEl) {
                clockEl.textContent = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: TZ
                }).replace(/\./g, ':');
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: TZ
                });
            }
        } catch (e) {
            // Browser lama tanpa dukungan timeZone: biarkan nilai dari server.
        }
    }
    if (clockEl || dateEl) {
        tick();
        setInterval(tick, 1000);
    }

    /* ------------------------------------------------- Count-up angka */
    function countUp(el) {
        var target = parseInt(el.getAttribute('data-count'), 10);
        if (isNaN(target) || target <= 0 || reduceMotion) { return; }

        var duration = 800;
        var start = null;
        var fmt = new Intl.NumberFormat('id-ID');

        function step(ts) {
            if (start === null) { start = ts; }
            var p = Math.min((ts - start) / duration, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = fmt.format(Math.round(target * eased));
            if (p < 1) { requestAnimationFrame(step); }
        }
        requestAnimationFrame(step);
    }
    Array.prototype.forEach.call(document.querySelectorAll('[data-count]'), countUp);

    /* ------------------------------------------------------ Chart.js */
    if (typeof window.Chart === 'undefined') { return; }

    var Chart = window.Chart;
    Chart.defaults.global.defaultFontFamily = "'Source Sans Pro', -apple-system, 'Segoe UI', sans-serif";
    Chart.defaults.global.defaultFontColor = TICK;
    Chart.defaults.global.animation.duration = reduceMotion ? 0 : 700;

    function gradient(ctx, color, height) {
        var g = ctx.createLinearGradient(0, 0, 0, height || 300);
        g.addColorStop(0, color + '55');
        g.addColorStop(1, color + '00');
        return g;
    }

    function rupiahShort(v) {
        var abs = Math.abs(v);
        if (abs >= 1e9) { return 'Rp ' + (v / 1e9).toFixed(1).replace('.0', '') + ' M'; }
        if (abs >= 1e6) { return 'Rp ' + (v / 1e6).toFixed(1).replace('.0', '') + ' jt'; }
        if (abs >= 1e3) { return 'Rp ' + (v / 1e3).toFixed(0) + ' rb'; }
        return 'Rp ' + v;
    }

    function rupiahFull(v) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v));
    }

    var tooltipBase = {
        backgroundColor: '#0f172a',
        titleFontStyle: 'bold',
        xPadding: 12,
        yPadding: 10,
        cornerRadius: 8,
        displayColors: true,
        mode: 'index',
        intersect: false
    };

    /* ---- Tren aktivitas ---- */
    var trendEl = document.getElementById('pd-trend-chart');
    if (trendEl && DATA.traffic) {
        var tctx = trendEl.getContext('2d');
        var datasets = [{
            label: 'Tampilan konten',
            data: DATA.traffic.series,
            borderColor: NAVY,
            backgroundColor: gradient(tctx, NAVY, 300),
            borderWidth: 2.5,
            pointRadius: 0,
            pointHoverRadius: 5,
            pointBackgroundColor: NAVY,
            lineTension: 0.35,
            fill: true
        }];

        if (DATA.messages) {
            datasets.push({
                label: 'Pesan masuk',
                data: DATA.messages.series,
                borderColor: RED,
                backgroundColor: gradient(tctx, RED, 300),
                borderWidth: 2.5,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointBackgroundColor: RED,
                lineTension: 0.35,
                fill: true
            });
        }

        new Chart(tctx, {
            type: 'line',
            data: { labels: DATA.traffic.labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 18 } },
                tooltips: tooltipBase,
                hover: { mode: 'index', intersect: false },
                scales: {
                    xAxes: [{ gridLines: { display: false }, ticks: { maxTicksLimit: 8, maxRotation: 0 } }],
                    yAxes: [{ gridLines: { color: GRID, drawBorder: false }, ticks: { beginAtZero: true, precision: 0, maxTicksLimit: 6 } }]
                }
            }
        });
    }

    /* ---- Komposisi konten ---- */
    var compEl = document.getElementById('pd-comp-chart');
    if (compEl && DATA.composition && DATA.composition.length) {
        new Chart(compEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: DATA.composition.map(function (c) { return c.label; }),
                datasets: [{
                    data: DATA.composition.map(function (c) { return c.value; }),
                    backgroundColor: DATA.composition.map(function (c) { return c.color; }),
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 66,
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 10, fontSize: 11 } },
                tooltips: Object.assign({}, tooltipBase, {
                    mode: 'nearest',
                    intersect: true,
                    callbacks: {
                        label: function (item, data) {
                            return ' ' + data.labels[item.index] + ': ' + data.datasets[0].data[item.index];
                        }
                    }
                })
            }
        });
    }

    /* ---- Arus kas ---- */
    var finEl = document.getElementById('pd-finance-chart');
    if (finEl && DATA.finance && DATA.finance.show) {
        new Chart(finEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: DATA.finance.labels,
                datasets: [
                    { label: 'Pemasukan', data: DATA.finance.masuk, backgroundColor: GREEN },
                    { label: 'Pengeluaran', data: DATA.finance.keluar, backgroundColor: RED }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 18 } },
                tooltips: Object.assign({}, tooltipBase, {
                    callbacks: {
                        label: function (item, data) {
                            return ' ' + data.datasets[item.datasetIndex].label + ': ' + rupiahFull(item.yLabel);
                        }
                    }
                }),
                scales: {
                    xAxes: [{ gridLines: { display: false }, barPercentage: 0.7, categoryPercentage: 0.7 }],
                    yAxes: [{
                        gridLines: { color: GRID, drawBorder: false },
                        ticks: { beginAtZero: true, maxTicksLimit: 6, callback: rupiahShort }
                    }]
                }
            }
        });
    }

    /* ---- Sparkline di kartu KPI ---- */
    function spark(canvas, series, color) {
        if (!series || !series.length) { return; }
        var ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: series.map(function (_, i) { return i; }),
                datasets: [{
                    data: series,
                    borderColor: color,
                    backgroundColor: gradient(ctx, color, 40),
                    borderWidth: 2,
                    pointRadius: 0,
                    lineTension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                tooltips: { enabled: false },
                hover: { mode: null },
                events: [],
                layout: { padding: { top: 4, bottom: 0 } },
                scales: {
                    xAxes: [{ display: false }],
                    yAxes: [{ display: false, ticks: { beginAtZero: true } }]
                }
            }
        });
    }

    Array.prototype.forEach.call(document.querySelectorAll('canvas[data-spark]'), function (canvas) {
        var kind = canvas.getAttribute('data-spark');
        if (kind === 'traffic' && DATA.traffic) { spark(canvas, DATA.traffic.series, NAVY); }
        if (kind === 'messages' && DATA.messages) { spark(canvas, DATA.messages.series, RED); }
    });
})();
