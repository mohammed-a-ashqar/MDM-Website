import Lenis from 'lenis';

/*
 * Scroll-driven motion: smooth scroll (Lenis) + GSAP ScrollTrigger effects.
 * GSAP itself is the copy the theme already loads globally (window.gsap).
 */

const reduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/** Wrap every word of an element's text in masked spans, keeping inner tags like <br>/<em>. */
function splitWords(el) {
    if (el.dataset.mxSplit) return el.querySelectorAll('.mx-w__i');
    el.dataset.mxSplit = '1';
    const walk = (node) => {
        [...node.childNodes].forEach((child) => {
            if (child.nodeType === Node.TEXT_NODE) {
                const parts = child.textContent.split(/(\s+)/);
                const frag = document.createDocumentFragment();
                parts.forEach((part) => {
                    if (!part) return;
                    if (/^\s+$/.test(part)) {
                        frag.appendChild(document.createTextNode(' '));
                        return;
                    }
                    const outer = document.createElement('span');
                    outer.className = 'mx-w';
                    const inner = document.createElement('span');
                    inner.className = 'mx-w__i';
                    inner.textContent = part;
                    outer.appendChild(inner);
                    frag.appendChild(outer);
                });
                child.replaceWith(frag);
            } else if (child.nodeType === Node.ELEMENT_NODE && child.tagName !== 'BR') {
                walk(child);
            }
        });
    };
    walk(el);
    return el.querySelectorAll('.mx-w__i');
}

function initSmoothScroll(gsap, ScrollTrigger) {
    if (window.matchMedia('(pointer: coarse)').matches) return null;

    const lenis = new Lenis({
        duration: 1.15,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        anchors: { offset: -90 },
        prevent: (node) => !!node.closest?.('.offcanvas, .modal, .mx-index, .dropdown-menu, [data-lenis-prevent]'),
    });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
    window.__lenis = lenis;
    if (document.documentElement.classList.contains('mx-lock')) lenis.stop();
    return lenis;
}

function heroOnScroll(gsap) {
    const hero = document.querySelector('.site-page-home .hero.vh-100');
    if (!hero) return;
    const cover = hero.querySelector('.video-cover');
    const content = hero.querySelector('.mx-hero, .hero-content');

    gsap.timeline({
        scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true },
    })
        .fromTo(cover, { clipPath: 'inset(0% 0% 0% 0% round 0px)' }, { clipPath: 'inset(7% 4% 0% 4% round 40px)', ease: 'none' }, 0)
        .fromTo(hero.querySelector('#hero-video'), { scale: 1 }, { scale: 1.18, yPercent: 8, ease: 'none' }, 0)
        .to(content, { yPercent: -35, opacity: 0, ease: 'none' }, 0);
}

function marquees(gsap, ScrollTrigger) {
    document.querySelectorAll('.mx-marquee__row').forEach((row) => {
        const track = row.querySelector('.mx-marquee__track');
        if (!track) return;
        const dir = row.dataset.dir === 'right' ? 1 : -1;
        let x = 0;
        let skew = 0;
        const base = 0.045; // % of track per frame
        gsap.ticker.add(() => {
            const v = gsap.utils.clamp(-3000, 3000, scrollVelocity());
            const boost = 1 + Math.abs(v) / 250;
            x += base * boost * dir * (v < 0 ? -1 : 1);
            if (x <= -50) x += 50;
            if (x >= 0) x -= 50;
            skew += (gsap.utils.clamp(-12, 12, v / 120) - skew) * 0.1;
            track.style.transform = `translate3d(${x}%,0,0) skewX(${-skew * dir}deg)`;
        });
    });
}

let lastY = window.scrollY;
let velocity = 0;
function scrollVelocity() {
    return velocity;
}
function trackVelocity(gsap) {
    gsap.ticker.add((time, dt) => {
        const y = window.scrollY;
        const v = ((y - lastY) / Math.max(dt, 1)) * 1000;
        velocity += (v - velocity) * 0.2;
        lastY = y;
    });
}

function statementFill(gsap) {
    document.querySelectorAll('[data-mx-words]').forEach((el) => {
        const words = splitWords(el);
        el.classList.add('is-split');
        gsap.fromTo(
            words,
            { opacity: 0.12, yPercent: 0 },
            {
                opacity: 1,
                stagger: 0.08,
                ease: 'none',
                scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: true },
            },
        );
    });
}

function headingReveals(gsap) {
    const selector = [
        '.science-heading',
        '.clinical-heading',
        '.our-products-title',
        '#why-choose-us h2',
        '#from_our_blog_2 h2',
        '#get-in-touch h2',
        '.mx-reveal-words',
        'main#content.layout-page > section:first-child h1:not(.mx-hero__title)',
    ].join(',');

    document.querySelectorAll(selector).forEach((el) => {
        const words = splitWords(el);
        if (!words.length) return;
        gsap.fromTo(
            words,
            { yPercent: 110, rotate: 4 },
            {
                yPercent: 0,
                rotate: 0,
                duration: 1.1,
                ease: 'expo.out',
                stagger: 0.05,
                scrollTrigger: { trigger: el, start: 'top 88%', once: true },
            },
        );
    });
}

function imageReveals(gsap) {
    const media = document.querySelectorAll('.clinical-card__media, .science-visual__main, .science-visual__overlay, .mx-reveal-img');
    media.forEach((wrap, i) => {
        const img = wrap.querySelector('img');
        wrap.style.overflow = 'hidden';
        gsap.fromTo(
            wrap,
            { clipPath: 'inset(100% 0% 0% 0%)' },
            {
                clipPath: 'inset(0% 0% 0% 0%)',
                duration: 1.5,
                ease: 'expo.inOut',
                delay: (i % 2) * 0.15,
                scrollTrigger: { trigger: wrap, start: 'top 85%', once: true },
            },
        );
        if (img) {
            gsap.fromTo(img, { scale: 1.35 }, { scale: 1.1, duration: 1.8, ease: 'expo.out', scrollTrigger: { trigger: wrap, start: 'top 85%', once: true } });
            gsap.fromTo(img, { yPercent: -5 }, { yPercent: 5, ease: 'none', scrollTrigger: { trigger: wrap, start: 'top bottom', end: 'bottom top', scrub: true } });
        }
    });
}

function staggerGroups(gsap) {
    const groups = [
        ['#why-choose-us .choose-us-grid', '.choose-us-item'],
        ['.science-aesthetics-section .row', '.science-step-badge'],
        ['#our-products .our-products-grid', '.our-products-tile'],
    ];
    groups.forEach(([parentSel, childSel]) => {
        document.querySelectorAll(parentSel).forEach((parent) => {
            const items = parent.querySelectorAll(childSel);
            if (!items.length) return;
            gsap.fromTo(
                items,
                { y: 80, opacity: 0 },
                { y: 0, opacity: 1, duration: 1.2, ease: 'expo.out', stagger: 0.12, scrollTrigger: { trigger: parent, start: 'top 85%', once: true } },
            );
        });
    });

    // Brand tiles: slow parallax on the background image
    document.querySelectorAll('.our-products-tile').forEach((tile) => {
        gsap.fromTo(tile, { backgroundPositionY: '35%' }, { backgroundPositionY: '65%', ease: 'none', scrollTrigger: { trigger: tile, start: 'top bottom', end: 'bottom top', scrub: true } });
    });
}

function productSections(gsap) {
    // Dark product sections open from a rounded card to full width, content tilts upright.
    document.querySelectorAll('.mx-showcase-section').forEach((section) => {
        const inner = section.querySelector('[data-island]');
        gsap.fromTo(
            section,
            { clipPath: 'inset(0% 5% 0% 5% round 48px)' },
            { clipPath: 'inset(0% 0% 0% 0% round 0px)', ease: 'none', scrollTrigger: { trigger: section, start: 'top bottom', end: 'top 15%', scrub: true } },
        );
        if (inner) {
            gsap.fromTo(
                inner,
                { rotateX: 16, scale: 0.9, y: 90, transformPerspective: 1400, transformOrigin: '50% 0%' },
                { rotateX: 0, scale: 1, y: 0, ease: 'none', scrollTrigger: { trigger: section, start: 'top bottom', end: 'top 20%', scrub: true } },
            );
        }
    });

    // Product detail: the stage drifts slower than the copy while leaving the hero.
    const pd = document.querySelector('.mx-pd-section');
    if (pd) {
        window.ScrollTrigger.create({
            trigger: pd,
            start: 'top top',
            end: 'bottom top',
            onUpdate: (st) => {
                const stage = pd.querySelector('.mx-pd__media');
                const info = pd.querySelector('.mx-pd__info');
                if (stage) stage.style.transform = `translate3d(0, ${st.progress * 120}px, 0)`;
                if (info) info.style.transform = `translate3d(0, ${st.progress * -40}px, 0)`;
            },
        });
    }
}

/* ---------- Page transitions ---------- */
function pageTransitions() {
    const overlay = document.createElement('div');
    overlay.className = 'mx-transition';
    overlay.setAttribute('aria-hidden', 'true');
    overlay.innerHTML = '<span class="mx-transition__a"></span><span class="mx-transition__b"><em>MDM</em></span>';
    document.body.appendChild(overlay);

    let arrived = false;
    try {
        arrived = sessionStorage.getItem('mx-nav') === '1';
        sessionStorage.removeItem('mx-nav');
    } catch (e) {}

    if (arrived) {
        overlay.classList.add('is-covered');
        requestAnimationFrame(() => requestAnimationFrame(() => overlay.classList.replace('is-covered', 'is-leaving')));
        setTimeout(() => overlay.classList.remove('is-leaving'), 1300);
    }

    window.addEventListener('pageshow', (e) => {
        if (e.persisted) overlay.className = 'mx-transition';
    });

    document.addEventListener('click', (e) => {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        const a = e.target.closest('a[href]');
        if (!a || a.target === '_blank' || a.hasAttribute('download') || a.dataset.noTransition !== undefined) return;
        const url = new URL(a.href, location.href);
        if (url.origin !== location.origin) return;
        if (url.pathname.startsWith('/admin') || /\.(pdf|zip|jpe?g|png|webp|mp4)$/i.test(url.pathname)) return;
        if (url.pathname === location.pathname && url.search === location.search && url.hash) return;

        e.preventDefault();
        try {
            sessionStorage.setItem('mx-nav', '1');
        } catch (err) {}
        overlay.classList.add('is-entering');
        setTimeout(() => {
            location.href = url.href;
        }, 650);
    });
}

export function initScrollFx() {
    if (!reduced()) pageTransitions();

    const gsap = window.gsap;
    const ScrollTrigger = window.ScrollTrigger;
    if (!gsap || !ScrollTrigger || reduced()) return;
    gsap.registerPlugin(ScrollTrigger);

    initSmoothScroll(gsap, ScrollTrigger);
    trackVelocity(gsap);
    heroOnScroll(gsap);
    marquees(gsap, ScrollTrigger);
    statementFill(gsap);
    headingReveals(gsap);
    imageReveals(gsap);
    staggerGroups(gsap);
    productSections(gsap);

    // Lazy images / fonts change layout after load — keep trigger positions honest.
    window.addEventListener('load', () => ScrollTrigger.refresh());
    document.fonts?.ready.then(() => ScrollTrigger.refresh());
}
