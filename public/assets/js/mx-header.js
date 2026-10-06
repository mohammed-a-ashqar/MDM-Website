/*
 * MDM header: scroll states (solid / hidden), full-screen menu and search.
 * Plain JS on purpose — navigation must work even without the Vite bundle.
 */
(function () {
    var header = document.querySelector('[data-mx-header]');
    if (!header) return;
    var root = document.documentElement;
    var menu = document.getElementById('mx-menu');
    var burger = header.querySelector('[data-mx-menu-toggle]');
    var search = document.querySelector('[data-mx-search]');
    var searchInput = search && search.querySelector('input');

    // ---- Scroll: solid after 30px, hide on the way down, show on the way up
    var lastY = window.scrollY;
    var ticking = false;
    function onScroll() {
        var y = window.scrollY;
        header.classList.toggle('is-solid', y > 30);
        if (!root.classList.contains('mx-menu-open')) {
            var goingDown = y > lastY && y > 220;
            header.classList.toggle('is-hidden', goingDown && Math.abs(y - lastY) > 2);
        }
        lastY = y;
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(onScroll);
        }
    }, { passive: true });
    onScroll();

    function lockScroll(lock) {
        root.classList.toggle('mx-lock', lock);
        if (window.__lenis) lock ? window.__lenis.stop() : window.__lenis.start();
    }

    // ---- Full-screen menu
    function setMenu(open) {
        root.classList.toggle('mx-menu-open', open);
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        menu.setAttribute('aria-hidden', open ? 'false' : 'true');
        header.classList.remove('is-hidden');
        lockScroll(open);
    }
    if (burger && menu) {
        burger.addEventListener('click', function () {
            setMenu(!root.classList.contains('mx-menu-open'));
        });
        menu.addEventListener('click', function (e) {
            if (e.target.closest('a')) setMenu(false);
        });
    }

    // ---- Search overlay
    function setSearch(open) {
        if (!search) return;
        root.classList.toggle('mx-search-open', open);
        search.setAttribute('aria-hidden', open ? 'false' : 'true');
        lockScroll(open);
        if (open) setTimeout(function () { searchInput && searchInput.focus(); }, 350);
    }
    document.querySelectorAll('[data-mx-search-open]').forEach(function (b) {
        b.addEventListener('click', function () { setSearch(true); });
    });
    document.querySelectorAll('[data-mx-search-close]').forEach(function (b) {
        b.addEventListener('click', function () { setSearch(false); });
    });
    if (search) {
        search.addEventListener('click', function (e) {
            if (e.target === search) setSearch(false);
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (root.classList.contains('mx-search-open')) setSearch(false);
            if (root.classList.contains('mx-menu-open')) setMenu(false);
        }
        if (e.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) {
            e.preventDefault();
            setSearch(true);
        }
    });

    // ---- Mega menu: keyboard + touch friendly open state
    header.querySelectorAll('.has-mega').forEach(function (li) {
        var t;
        li.addEventListener('mouseenter', function () { clearTimeout(t); li.classList.add('is-open'); });
        li.addEventListener('mouseleave', function () { t = setTimeout(function () { li.classList.remove('is-open'); }, 120); });
        li.addEventListener('focusin', function () { li.classList.add('is-open'); });
        li.addEventListener('focusout', function (e) { if (!li.contains(e.relatedTarget)) li.classList.remove('is-open'); });
    });
})();
