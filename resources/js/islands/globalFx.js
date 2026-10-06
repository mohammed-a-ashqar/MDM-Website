/*
 * Site-wide effects that don't need React: scroll progress bar and a
 * custom cursor (fine pointers only, disabled for reduced motion).
 */
export function initGlobalFx() {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Scroll progress bar
    const bar = document.createElement('div');
    bar.className = 'mx-scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.appendChild(bar);

    let ticking = false;
    const updateBar = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const p = max > 0 ? window.scrollY / max : 0;
        bar.style.transform = `scaleX(${p})`;
        ticking = false;
    };
    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(updateBar);
            }
        },
        { passive: true },
    );
    updateBar();

    if (reduced || !window.matchMedia('(pointer: fine)').matches) return;

    // Custom cursor: a dot that tracks exactly and a ring that trails behind.
    const dot = document.createElement('div');
    const ring = document.createElement('div');
    dot.className = 'mx-cursor-dot';
    ring.className = 'mx-cursor-ring';
    dot.setAttribute('aria-hidden', 'true');
    ring.setAttribute('aria-hidden', 'true');
    document.body.append(dot, ring);
    document.documentElement.classList.add('mx-has-cursor');

    let mx = -100;
    let my = -100;
    let rx = mx;
    let ry = my;

    window.addEventListener(
        'pointermove',
        (e) => {
            mx = e.clientX;
            my = e.clientY;
            dot.style.transform = `translate3d(${mx}px, ${my}px, 0)`;
        },
        { passive: true },
    );

    const interactive = 'a, button, [role="button"], input, textarea, select, label, .mx-card';
    document.addEventListener('pointerover', (e) => {
        if (e.target.closest?.(interactive)) ring.classList.add('is-hover');
    });
    document.addEventListener('pointerout', (e) => {
        if (e.target.closest?.(interactive)) ring.classList.remove('is-hover');
    });
    document.addEventListener('pointerdown', () => ring.classList.add('is-down'));
    document.addEventListener('pointerup', () => ring.classList.remove('is-down'));
    document.documentElement.addEventListener('pointerleave', () => {
        dot.style.opacity = ring.style.opacity = '0';
    });
    document.documentElement.addEventListener('pointerenter', () => {
        dot.style.opacity = ring.style.opacity = '';
    });

    const loop = () => {
        rx += (mx - rx) * 0.16;
        ry += (my - ry) * 0.16;
        ring.style.transform = `translate3d(${rx}px, ${ry}px, 0)`;
        requestAnimationFrame(loop);
    };
    loop();
}
