import React, { useMemo, useRef, useState } from 'react';
import { AnimatePresence, motion, useMotionValue, useSpring } from 'framer-motion';
import { ArrowIcon, EASE } from './shared';

/** Product card with 3D tilt, a cursor-following spotlight and a shine sweep. */
function TiltCard({ product, index }) {
    const ref = useRef(null);
    const rx = useSpring(useMotionValue(0), { stiffness: 200, damping: 20 });
    const ry = useSpring(useMotionValue(0), { stiffness: 200, damping: 20 });

    const onMove = (e) => {
        if (e.pointerType !== 'mouse') return;
        const r = ref.current.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width;
        const y = (e.clientY - r.top) / r.height;
        ry.set((x - 0.5) * 14);
        rx.set((0.5 - y) * 14);
        ref.current.style.setProperty('--mx-x', `${x * 100}%`);
        ref.current.style.setProperty('--mx-y', `${y * 100}%`);
    };
    const onLeave = () => {
        rx.set(0);
        ry.set(0);
    };

    return (
        <motion.article
            layout
            className="mx-card-wrap"
            initial={{ opacity: 0, y: 60, scale: 0.94 }}
            whileInView={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, scale: 0.85, filter: 'blur(8px)', transition: { duration: 0.3 } }}
            viewport={{ once: true, amount: 0.2 }}
            transition={{ duration: 0.8, ease: EASE, delay: (index % 4) * 0.08 }}
        >
            <motion.a
                ref={ref}
                href={product.url}
                className="mx-card"
                style={{ rotateX: rx, rotateY: ry }}
                onPointerMove={onMove}
                onPointerLeave={onLeave}
                whileTap={{ scale: 0.98 }}
            >
                <span className="mx-card__media">
                    <img src={product.image} alt={product.title} loading="lazy" decoding="async" />
                    <span className="mx-card__shine" aria-hidden="true" />
                </span>
                <span className="mx-card__spot" aria-hidden="true" />
                {product.badge && <span className="mx-card__badge">{product.badge}</span>}
                <span className="mx-card__body">
                    {product.brand && <span className="mx-card__brand">{product.brand}</span>}
                    <span className="mx-card__title">{product.title}</span>
                    <span className="mx-card__more">
                        View details <ArrowIcon size={16} />
                    </span>
                </span>
            </motion.a>
        </motion.article>
    );
}

export function ProductCards({ products }) {
    return (
        <motion.div layout className="mx-gallery__grid">
            <AnimatePresence mode="popLayout">
                {products.map((p, i) => (
                    <TiltCard key={p.id} product={p} index={i} />
                ))}
            </AnimatePresence>
        </motion.div>
    );
}

export default function ProductGallery({ products = [], toolbar = true, emptyText = 'No products found.' }) {
    const [query, setQuery] = useState('');
    const [brand, setBrand] = useState('');
    const [sort, setSort] = useState('az');

    const brands = useMemo(() => [...new Set(products.map((p) => p.brand).filter(Boolean))].sort(), [products]);

    const visible = useMemo(() => {
        const q = query.trim().toLowerCase();
        const list = products.filter(
            (p) =>
                (!brand || p.brand === brand) &&
                (!q || [p.title, p.brand, p.category].filter(Boolean).some((v) => v.toLowerCase().includes(q))),
        );
        const sorted = [...list];
        if (sort === 'az') sorted.sort((a, b) => a.title.localeCompare(b.title));
        if (sort === 'za') sorted.sort((a, b) => b.title.localeCompare(a.title));
        if (sort === 'featured') sorted.sort((a, b) => Number(b.featured) - Number(a.featured));
        return sorted;
    }, [products, query, brand, sort]);

    return (
        <div className="mx-gallery">
            {toolbar && products.length > 1 && (
                <motion.div
                    className="mx-toolbar"
                    initial={{ opacity: 0, y: 24 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.7, ease: EASE }}
                >
                    <label className="mx-search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" strokeWidth="2" />
                            <path d="m20 20-3.5-3.5" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                        </svg>
                        <input type="search" value={query} onChange={(e) => setQuery(e.target.value)} placeholder="Filter products…" aria-label="Filter products" />
                    </label>

                    {brands.length > 1 && (
                        <div className="mx-chips" role="group" aria-label="Filter by brand">
                            {['', ...brands].map((b) => (
                                <button type="button" key={b || 'all'} className={`mx-filter${brand === b ? ' is-active' : ''}`} onClick={() => setBrand(b)}>
                                    {brand === b && <motion.span layoutId="mx-filter-pill" className="mx-filter__pill" transition={{ type: 'spring', stiffness: 400, damping: 32 }} />}
                                    <span className="mx-filter__label">{b || 'All'}</span>
                                </button>
                            ))}
                        </div>
                    )}

                    <div className="mx-toolbar__end">
                        <span className="mx-count">
                            <motion.strong key={visible.length} initial={{ y: -10, opacity: 0 }} animate={{ y: 0, opacity: 1 }}>
                                {visible.length}
                            </motion.strong>{' '}
                            {visible.length === 1 ? 'product' : 'products'}
                        </span>
                        <select className="mx-select" value={sort} onChange={(e) => setSort(e.target.value)} aria-label="Sort products">
                            <option value="az">Name A–Z</option>
                            <option value="za">Name Z–A</option>
                            <option value="featured">Featured first</option>
                        </select>
                    </div>
                </motion.div>
            )}

            <ProductCards products={visible} />

            <AnimatePresence>
                {visible.length === 0 && (
                    <motion.div className="mx-empty" initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}>
                        <span className="mx-empty__orb" aria-hidden="true" />
                        <p>{emptyText}</p>
                        {(query || brand) && (
                            <button
                                type="button"
                                className="mx-btn mx-btn--glow"
                                onClick={() => {
                                    setQuery('');
                                    setBrand('');
                                }}
                            >
                                Clear filters
                            </button>
                        )}
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}
