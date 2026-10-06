import React, { useCallback, useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useInView, useMotionValue, useSpring, useTransform } from 'framer-motion';
import { ArrowIcon, EASE, MagneticButton } from './shared';

const AUTOPLAY_MS = 6500;
const GLOWS = ['#3277d8', '#7c5cff', '#14b8a6', '#e2557b', '#f59e0b'];
const pad = (n) => String(n).padStart(2, '0');

/**
 * Cinematic featured-products stage: one product at a time, floating on a
 * glowing pedestal, with autoplay, swipe/drag, keyboard and a numbered index.
 */
export default function ProductShowcase({ products = [], eyebrow, heading, intro, allUrl, allLabel = 'View all products' }) {
    const [[index, dir], setState] = useState([0, 1]);
    const [paused, setPaused] = useState(false);
    const progress = useMotionValue(0);
    const rootRef = useRef(null);
    const dragged = useRef(false);
    const inView = useInView(rootRef, { amount: 0.35 });
    const count = products.length;
    const product = products[index];
    const glow = GLOWS[index % GLOWS.length];

    const go = useCallback(
        (next, d) => {
            if (!count) return;
            setState([(next + count) % count, d]);
            progress.set(0);
        },
        [count, progress],
    );
    const next = useCallback(() => go(index + 1, 1), [go, index]);
    const prev = useCallback(() => go(index - 1, -1), [go, index]);

    // Autoplay: progress lives in a motion value, so no re-render per frame.
    useEffect(() => {
        if (count < 2 || paused || !inView) return;
        let raf;
        let last = performance.now();
        const tick = (now) => {
            const p = progress.get() + (now - last) / AUTOPLAY_MS;
            last = now;
            if (p >= 1) {
                next();
                return;
            }
            progress.set(p);
            raf = requestAnimationFrame(tick);
        };
        raf = requestAnimationFrame(tick);
        return () => cancelAnimationFrame(raf);
    }, [count, paused, inView, next, progress]);

    // Pointer parallax on the stage.
    const px = useMotionValue(0);
    const py = useMotionValue(0);
    const rotY = useSpring(useTransform(px, [-0.5, 0.5], [-14, 14]), { stiffness: 120, damping: 18 });
    const rotX = useSpring(useTransform(py, [-0.5, 0.5], [10, -10]), { stiffness: 120, damping: 18 });
    const shiftX = useSpring(useTransform(px, [-0.5, 0.5], [-26, 26]), { stiffness: 80, damping: 20 });
    const shiftY = useSpring(useTransform(py, [-0.5, 0.5], [-18, 18]), { stiffness: 80, damping: 20 });

    const onStageMove = (e) => {
        const r = e.currentTarget.getBoundingClientRect();
        px.set((e.clientX - r.left) / r.width - 0.5);
        py.set((e.clientY - r.top) / r.height - 0.5);
    };
    const onStageLeave = () => {
        px.set(0);
        py.set(0);
    };

    const onKey = (e) => {
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
    };

    if (!count) return null;

    const imageVariants = {
        enter: (d) => ({ opacity: 0, x: d * 140, rotate: d * 12, scale: 0.8, filter: 'blur(14px)' }),
        center: { opacity: 1, x: 0, rotate: 0, scale: 1, filter: 'blur(0px)' },
        exit: (d) => ({ opacity: 0, x: d * -140, rotate: d * -12, scale: 0.85, filter: 'blur(14px)' }),
    };

    return (
        <div
            ref={rootRef}
            className="mx-showcase"
            style={{ '--mx-glow': glow }}
            onMouseEnter={() => setPaused(true)}
            onMouseLeave={() => setPaused(false)}
            onKeyDown={onKey}
            role="region"
            aria-roledescription="carousel"
            aria-label={heading || 'Featured products'}
        >
            <div className="mx-showcase__bg" aria-hidden="true">
                <motion.div className="mx-showcase__aurora" animate={{ background: `radial-gradient(closest-side, ${glow}55, transparent)` }} transition={{ duration: 1.2 }} />
                <div className="mx-showcase__grid" />
            </div>

            <div className="mx-showcase__head">
                <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.6 }} transition={{ duration: 0.9, ease: EASE }}>
                    {eyebrow && <p className="mx-eyebrow">{eyebrow}</p>}
                    {heading && <h2 className="mx-showcase__heading">{heading}</h2>}
                </motion.div>
                {intro && (
                    <motion.p className="mx-showcase__intro" initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.9, ease: EASE, delay: 0.1 }}>
                        {intro}
                    </motion.p>
                )}
            </div>

            <div className="mx-showcase__body">
                {/* Stage */}
                <motion.div
                    className="mx-stage"
                    onPointerMove={onStageMove}
                    onPointerLeave={onStageLeave}
                    initial={{ opacity: 0, scale: 0.92 }}
                    whileInView={{ opacity: 1, scale: 1 }}
                    viewport={{ once: true, amount: 0.3 }}
                    transition={{ duration: 1.1, ease: EASE }}
                >
                    <AnimatePresence mode="popLayout" initial={false}>
                        <motion.span
                            key={`num-${index}`}
                            className="mx-stage__num"
                            initial={{ opacity: 0, y: 60 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -60 }}
                            transition={{ duration: 0.8, ease: EASE }}
                            aria-hidden="true"
                        >
                            {pad(index + 1)}
                        </motion.span>
                    </AnimatePresence>

                    <motion.div className="mx-stage__ring" style={{ x: shiftX, y: shiftY }} aria-hidden="true" />
                    <motion.div className="mx-stage__ring mx-stage__ring--inner" style={{ x: shiftX, y: shiftY }} aria-hidden="true" />

                    <motion.div
                        className="mx-stage__drag"
                        drag={count > 1 ? 'x' : false}
                        dragConstraints={{ left: 0, right: 0 }}
                        dragElastic={0.25}
                        onDragStart={() => {
                            dragged.current = true;
                        }}
                        onClickCapture={(e) => {
                            if (dragged.current) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        }}
                        onPointerDown={() => {
                            dragged.current = false;
                        }}
                        onDragEnd={(_, info) => {
                            if (info.offset.x < -70 || info.velocity.x < -500) next();
                            else if (info.offset.x > 70 || info.velocity.x > 500) prev();
                        }}
                        style={{ rotateX: rotX, rotateY: rotY }}
                    >
                        <AnimatePresence custom={dir} mode="popLayout" initial={false}>
                            <motion.a
                                key={product.id}
                                href={product.url}
                                className="mx-stage__card"
                                custom={dir}
                                variants={imageVariants}
                                initial="enter"
                                animate="center"
                                exit="exit"
                                transition={{ duration: 0.9, ease: EASE }}
                                draggable={false}
                                aria-label={product.title}
                            >
                                <motion.img
                                    src={product.image}
                                    alt={product.title}
                                    draggable={false}
                                    animate={{ y: [0, -14, 0] }}
                                    transition={{ duration: 5, repeat: Infinity, ease: 'easeInOut' }}
                                />
                                <span className="mx-stage__shine" aria-hidden="true" />
                            </motion.a>
                        </AnimatePresence>
                    </motion.div>

                    <div className="mx-stage__shadow" aria-hidden="true" />
                    {count > 1 && <span className="mx-stage__hint">Drag ⟷</span>}
                </motion.div>

                {/* Copy + index */}
                <div className="mx-showcase__info">
                    <div className="mx-showcase__copy" aria-live="polite">
                        <AnimatePresence mode="wait" initial={false}>
                            <motion.div
                                key={product.id}
                                initial="hidden"
                                animate="show"
                                exit="hide"
                                variants={{
                                    hidden: {},
                                    show: { transition: { staggerChildren: 0.07 } },
                                    hide: { transition: { staggerChildren: 0.03 } },
                                }}
                            >
                                {[
                                    product.brand && (
                                        <span className="mx-chip" key="brand">
                                            {product.brand}
                                        </span>
                                    ),
                                    <h3 className="mx-showcase__title" key="title">
                                        {product.title}
                                    </h3>,
                                    product.excerpt && (
                                        <p className="mx-showcase__excerpt" key="ex">
                                            {product.excerpt}
                                        </p>
                                    ),
                                    <div className="mx-showcase__cta" key="cta">
                                        <MagneticButton href={product.url} className="mx-btn mx-btn--glow">
                                            <span>Discover product</span>
                                            <span className="mx-btn__icon">
                                                <ArrowIcon />
                                            </span>
                                        </MagneticButton>
                                    </div>,
                                ]
                                    .filter(Boolean)
                                    .map((node) => (
                                        <motion.div
                                            key={node.key}
                                            variants={{
                                                hidden: { opacity: 0, y: 28, filter: 'blur(6px)' },
                                                show: { opacity: 1, y: 0, filter: 'blur(0px)', transition: { duration: 0.7, ease: EASE } },
                                                hide: { opacity: 0, y: -18, filter: 'blur(6px)', transition: { duration: 0.3 } },
                                            }}
                                        >
                                            {node}
                                        </motion.div>
                                    ))}
                            </motion.div>
                        </AnimatePresence>
                    </div>

                    {count > 1 && (
                        <>
                            <ol className="mx-index">
                                {products.map((p, i) => (
                                    <li key={p.id}>
                                        <button
                                            type="button"
                                            className={`mx-index__item${i === index ? ' is-active' : ''}`}
                                            onClick={() => go(i, i > index ? 1 : -1)}
                                            aria-current={i === index}
                                        >
                                            <span className="mx-index__num">{pad(i + 1)}</span>
                                            <span className="mx-index__thumb">
                                                <img src={p.image} alt="" loading="lazy" />
                                            </span>
                                            <span className="mx-index__name">{p.title}</span>
                                            <span className="mx-index__track">
                                                {i === index ? <motion.span style={{ scaleX: progress }} /> : <span style={{ transform: `scaleX(${i < index ? 1 : 0})` }} />}
                                            </span>
                                        </button>
                                    </li>
                                ))}
                            </ol>

                            <div className="mx-showcase__nav">
                                <button type="button" className="mx-round" onClick={prev} aria-label="Previous product">
                                    <span style={{ transform: 'rotate(180deg)', display: 'inline-flex' }}>
                                        <ArrowIcon />
                                    </span>
                                </button>
                                <span className="mx-showcase__counter">
                                    <strong>{pad(index + 1)}</strong> / {pad(count)}
                                </span>
                                <button type="button" className="mx-round" onClick={next} aria-label="Next product">
                                    <ArrowIcon />
                                </button>
                                {allUrl && (
                                    <a href={allUrl} className="mx-link">
                                        {allLabel} <ArrowIcon size={16} />
                                    </a>
                                )}
                            </div>
                        </>
                    )}
                    {count === 1 && allUrl && (
                        <a href={allUrl} className="mx-link mt-4">
                            {allLabel} <ArrowIcon size={16} />
                        </a>
                    )}
                </div>
            </div>
        </div>
    );
}
