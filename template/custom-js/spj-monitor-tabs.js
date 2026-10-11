// Simpan & pulihkan tab aktif monitor SPJ (localStorage)
(function () {
    // Count-up rupiah di tile-stats (ponytail: rAF 1.2s ease-out; upgrade ke IntersectionObserver bila tile di bawah fold)
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        var fmt = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
        $('[data-countup]').each(function () {
            var el = this, target = parseFloat(el.getAttribute('data-countup')) || 0, t0 = null, dur = 1200;
            function tick(t) {
                if (!t0) t0 = t;
                var p = Math.min((t - t0) / dur, 1), e = 1 - Math.pow(1 - p, 3);
                el.textContent = fmt.format(Math.round(target * e));
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    }

    var KEY = 'spj_monitor_tab';
    var $tabs = $('a[data-toggle="tab"][href^="#tab-"]');
    if (!$tabs.length) return;

    // Pulihkan tab terakhir sebelum bootstrap menampilkan default
    var saved = localStorage.getItem(KEY);
    if (saved && $(saved).length) {
        $tabs.filter('[href="' + saved + '"]').tab('show');
    }

    // Simpan setiap kali tab berpindah
    $tabs.on('shown.bs.tab', function () {
        localStorage.setItem(KEY, this.getAttribute('href'));
    });
})();
