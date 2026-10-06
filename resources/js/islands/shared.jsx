import React, { useRef } from 'react';
import { motion, useMotionValue, useSpring } from 'framer-motion';

export const EASE = [0.22, 1, 0.36, 1];

/** Link that leans toward the cursor while hovered. */
export function MagneticButton({ href, children, className = '', strength = 0.35, ...rest }) {
    const ref = useRef(null);
    const x = useSpring(useMotionValue(0), { stiffness: 220, damping: 15, mass: 0.4 });
    const y = useSpring(useMotionValue(0), { stiffness: 220, damping: 15, mass: 0.4 });

    const onMove = (e) => {
        const r = ref.current.getBoundingClientRect();
        x.set((e.clientX - (r.left + r.width / 2)) * strength);
        y.set((e.clientY - (r.top + r.height / 2)) * strength);
    };
    const reset = () => {
        x.set(0);
        y.set(0);
    };

    return (
        <motion.a
            ref={ref}
            href={href}
            className={`mx-magnetic ${className}`}
            style={{ x, y }}
            onPointerMove={onMove}
            onPointerLeave={reset}
            whileTap={{ scale: 0.96 }}
            {...rest}
        >
            {children}
        </motion.a>
    );
}

/** Text that rises letter by letter from behind a mask. */
export function SplitText({ text, delay = 0, stagger = 0.03, className = '', as: Tag = 'span', animate = true }) {
    const words = String(text).split(' ');
    let i = 0;

    return (
        <Tag className={`mx-split ${className}`} aria-label={text}>
            {words.map((word, wi) => (
                <span className="mx-split__word" aria-hidden="true" key={wi}>
                    {Array.from(word).map((ch) => {
                        const d = delay + i++ * stagger;
                        return (
                            <span className="mx-split__mask" key={`${wi}-${i}`}>
                                <motion.span
                                    className="mx-split__char"
                                    initial={{ y: '115%', rotate: 8 }}
                                    animate={animate ? { y: '0%', rotate: 0 } : undefined}
                                    transition={{ duration: 0.9, ease: EASE, delay: d }}
                                >
                                    {ch}
                                </motion.span>
                            </span>
                        );
                    })}
                    {wi < words.length - 1 ? ' ' : null}
                </span>
            ))}
        </Tag>
    );
}

export function ArrowIcon({ size = 18 }) {
    return (
        <svg width={size} height={size} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}
