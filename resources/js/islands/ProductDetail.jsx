import React, { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useMotionValue, useSpring, useTransform } from 'framer-motion';
import { ArrowIcon, EASE, MagneticButton } from './shared';

const reveal = (delay = 0) => ({
    initial: { opacity: 0, y: 34, filter: 'blur(8px)' },
    animate: { opacity: 1, y: 0, filter: 'blur(0px)' },
    transition: { duration: 0.9, ease: EASE, delay },
});

/** Image stage: 3D tilt, magnifier lens, animated image swaps. */
function Stage({ images, index, setIndex, title, onOpen }) {
    const ref = useRef(null);
    const [lens, setLens] = useState(null);
    const px = useMotionValue(0);
    const py = useMotionValue(0);
    const rotY = useSpring(useTransform(px, [-0.5, 0.5], [-10, 10]), { stiffness: 140, damping: 18 });
    const rotX = useSpring(useTransform(py, [-0.5, 0.5], [8, -8]), { stiffness: 140, damping: 18 });

    const onMove = (e) => {
        const r = ref.current.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width;
        const y = (e.clientY - r.top) / r.height;
        px.set(x - 0.5);
        py.set(y - 0.5);
        if (e.pointerType === 'mouse') setLens({ x, y });
    };
    const onLeave = () => {
        px.set(0);
        py.set(0);
        setLens(null);
    };

    const count = images.length;
    const go = (d) => setIndex((index + d + count) % count);

    return (
        <div className="mx-pd-stage">
            <div className="mx-stage__ring" aria-hidden="true" />
            <div className="mx-stage__ring mx-stage__ring--inner" aria-hidden="true" />

            <motion.div
                ref={ref}
                className="mx-pd-frame"
                style={{ rotateX: rotX, rotateY: rotY }}
                onPointerMove={onMove}
                onPointerLeave={onLeave}
                onClick={onOpen}
                initial={{ opacity: 0, scale: 0.8, rotateY: -25, y: 60 }}
                animate={{ opacity: 1, scale: 1, rotateY: 0, y: 0 }}
                transition={{ duration: 1.3, ease: EASE, delay: 0.15 }}
                role="button"
                tabIndex={0}
                aria-label="Open full-screen image"
                onKeyDown={(e) => e.key === 'Enter' && onOpen()}
            >
                <AnimatePresence mode="popLayout" initial={false}>
                    <motion.img
                        key={images[index]}
                        src={images[index]}
                        alt={title}
                        layoutId={`mx-pd-img-${index}`}
                        initial={{ opacity: 0, scale: 1.15, filter: 'blur(12px)' }}
                        animate={{ opacity: 1, scale: 1, filter: 'blur(0px)' }}
                        exit={{ opacity: 0, scale: 0.9, filter: 'blur(12px)' }}
                        transition={{ duration: 0.7, ease: EASE }}
                        draggable={false}
                    />
                </AnimatePresence>
                <span className="mx-stage__shine" aria-hidden="true" />
                <AnimatePresence>
                    {lens && (
                        <motion.span
                            className="mx-pd-lens"
                            initial={{ opacity: 0, scale: 0.6 }}
                            animate={{ opacity: 1, scale: 1 }}
                            exit={{ opacity: 0, scale: 0.6 }}
                            transition={{ duration: 0.25 }}
                            style={{
                                left: `${lens.x * 100}%`,
                                top: `${lens.y * 100}%`,
                                backgroundImage: `url("${images[index]}")`,
                                backgroundPosition: `${lens.x * 100}% ${lens.y * 100}%`,
                            }}
                            aria-hidden="true"
                        />
                    )}
                </AnimatePresence>
            </motion.div>
            <div className="mx-stage__shadow" aria-hidden="true" />

            {count > 1 && (
                <>
                    <button type="button" className="mx-round mx-pd-arrow mx-pd-arrow--prev" onClick={() => go(-1)} aria-label="Previous image">
                        <span style={{ transform: 'rotate(180deg)', display: 'inline-flex' }}>
                            <ArrowIcon />
                        </span>
                    </button>
                    <button type="button" className="mx-round mx-pd-arrow mx-pd-arrow--next" onClick={() => go(1)} aria-label="Next image">
                        <ArrowIcon />
                    </button>
                </>
            )}
        </div>
    );
}

function Lightbox({ images, index, setIndex, title, onClose }) {
    useEffect(() => {
        const onKey = (e) => {
            if (e.key === 'Escape') onClose();
            if (e.key === 'ArrowRight') setIndex((index + 1) % images.length);
            if (e.key === 'ArrowLeft') setIndex((index - 1 + images.length) % images.length);
        };
        window.addEventListener('keydown', onKey);
        window.__lenis?.stop();
        document.documentElement.classList.add('mx-lock');
        return () => {
            window.removeEventListener('keydown', onKey);
            window.__lenis?.start();
            document.documentElement.classList.remove('mx-lock');
        };
    }, [index, images.length, onClose, setIndex]);

    return (
        <motion.div className="mx-lightbox" initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} onClick={onClose} role="dialog" aria-modal="true" aria-label={title}>
            <motion.img
                src={images[index]}
                alt={title}
                layoutId={`mx-pd-img-${index}`}
                transition={{ duration: 0.6, ease: EASE }}
                onClick={(e) => e.stopPropagation()}
            />
            <button type="button" className="mx-round mx-lightbox__close" onClick={onClose} aria-label="Close">
                ✕
            </button>
            {images.length > 1 && (
                <span className="mx-lightbox__count">
                    {index + 1} / {images.length}
                </span>
            )}
        </motion.div>
    );
}

export default function ProductDetail({ product, images = [], crumbs = [], primary, secondary }) {
    const [index, setIndex] = useState(0);
    const [open, setOpen] = useState(false);
    const words = String(product.title).split(' ');

    return (
        <div className="mx-pd">
            <div className="mx-pd__grid">
                <div className="mx-pd__media">
                    <Stage images={images} index={index} setIndex={setIndex} title={product.title} onOpen={() => setOpen(true)} />

                    {images.length > 1 && (
                        <motion.div className="mx-pd-thumbs" {...reveal(0.6)}>
                            {images.map((src, i) => (
                                <button type="button" key={src} className={`mx-pd-thumb${i === index ? ' is-active' : ''}`} onClick={() => setIndex(i)} aria-label={`Image ${i + 1}`}>
                                    <img src={src} alt="" loading="lazy" />
                                    {i === index && <motion.span layoutId="mx-pd-thumb-ring" className="mx-pd-thumb__ring" transition={{ type: 'spring', stiffness: 380, damping: 30 }} />}
                                </button>
                            ))}
                        </motion.div>
                    )}
                </div>

                <div className="mx-pd__info">
                    {crumbs.length > 0 && (
                        <motion.nav className="mx-pd-crumbs" aria-label="Breadcrumb" {...reveal(0.1)}>
                            {crumbs.map((c, i) =>
                                c.url ? (
                                    <React.Fragment key={i}>
                                        <a href={c.url}>{c.label}</a>
                                        <span aria-hidden="true">/</span>
                                    </React.Fragment>
                                ) : (
                                    <span key={i} aria-current="page">
                                        {c.label}
                                    </span>
                                ),
                            )}
                        </motion.nav>
                    )}

                    <motion.div className="mx-pd-tags" {...reveal(0.2)}>
                        {product.brand && <span className="mx-chip">{product.brand}</span>}
                        {product.category && <span className="mx-chip mx-chip--ghost">{product.category}</span>}
                        {product.badge && <span className="mx-chip mx-chip--hot">{product.badge}</span>}
                    </motion.div>

                    <h1 className="mx-pd__title" aria-label={product.title}>
                        {words.map((w, i) => (
                            <React.Fragment key={i}>
                                <span className="mx-w" aria-hidden="true">
                                    <motion.span
                                        className="mx-w__i"
                                        initial={{ yPercent: 110, rotate: 5 }}
                                        animate={{ yPercent: 0, rotate: 0 }}
                                        transition={{ duration: 1, ease: EASE, delay: 0.3 + i * 0.07 }}
                                    >
                                        {w}
                                    </motion.span>
                                </span>
                                {i < words.length - 1 ? ' ' : null}
                            </React.Fragment>
                        ))}
                    </h1>

                    {product.excerpt && (
                        <motion.p className="mx-pd__lead" {...reveal(0.55)}>
                            {product.excerpt}
                        </motion.p>
                    )}

                    <motion.div className="mx-pd__actions" {...reveal(0.7)}>
                        {primary && (
                            <MagneticButton href={primary.url} className="mx-btn mx-btn--light">
                                <span>{primary.label}</span>
                                <span className="mx-btn__icon">
                                    <ArrowIcon />
                                </span>
                            </MagneticButton>
                        )}
                        {secondary && (
                            <MagneticButton href={secondary.url} className="mx-btn mx-btn--ghost">
                                <span>{secondary.label}</span>
                            </MagneticButton>
                        )}
                    </motion.div>

                    <motion.ul className="mx-pd__facts" {...reveal(0.85)}>
                        {['Professional use', 'Expert support', 'Fast delivery'].map((f) => (
                            <li key={f}>
                                <span className="mx-pd__tick" aria-hidden="true">✓</span>
                                {f}
                            </li>
                        ))}
                    </motion.ul>
                </div>
            </div>

            <AnimatePresence>{open && <Lightbox images={images} index={index} setIndex={setIndex} title={product.title} onClose={() => setOpen(false)} />}</AnimatePresence>
        </div>
    );
}
