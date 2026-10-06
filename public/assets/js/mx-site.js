/* Footer "back to top" + home category hover preview (plain JS, no build step) */
(function () {
    document.querySelectorAll('[data-mx-to-top]').forEach(function (b) {
        b.addEventListener('click', function () {
            if (window.__lenis) window.__lenis.scrollTo(0, { duration: 1.6 });
            else window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    var cats = document.querySelector('.mx-cats');
    if (!cats || !window.matchMedia('(hover: hover)').matches) return;
    var preview = cats.querySelector('.mx-cats__preview');
    var img = preview.querySelector('img');
    var x = 0, y = 0, px = 0, py = 0, raf = null;

    function loop() {
        px += (x - px) * 0.14;
        py += (y - py) * 0.14;
        preview.style.left = px + 'px';
        preview.style.top = py + 'px';
        raf = Math.abs(x - px) + Math.abs(y - py) > 0.5 ? requestAnimationFrame(loop) : null;
    }

    cats.querySelectorAll('.mx-cats__row').forEach(function (row) {
        row.addEventListener('mouseenter', function (e) {
            var src = row.getAttribute('data-preview');
            if (!src) return;
            if (img.getAttribute('src') !== src) img.setAttribute('src', src);
            if (!preview.classList.contains('is-visible')) {
                px = x = e.clientX + 140;
                py = y = e.clientY;
            }
            preview.classList.add('is-visible');
        });
        row.addEventListener('mouseleave', function () {
            preview.classList.remove('is-visible');
        });
    });

    cats.addEventListener('mousemove', function (e) {
        x = e.clientX + 140;
        y = e.clientY;
        if (!raf) raf = requestAnimationFrame(loop);
    });
})();
