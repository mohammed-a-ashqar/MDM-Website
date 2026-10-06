import React, { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import { AnimatePresence, motion, useReducedMotion } from 'framer-motion';
import { ArrowIcon, EASE, MagneticButton, SplitText } from './shared';

const SEEN_KEY = 'mdm-intro-seen';

function hasSeenIntro() {
    try {
        return sessionStorage.getItem(SEEN_KEY) === '1';
    } catch (e) {
        return false;
    }
}

/** Full-screen intro: a counter runs to 100, then the curtain splits away. */
function Preloader({ brand, onDone }) {
    const [count, setCount] = useState(0);
    const [leaving, setLeaving] = useState(false);

    useEffect(() => {
        document.documentElement.classList.add('mx-lock');
        window.__lenis?.stop();
        const start = performance.now();
        const duration = 1700;
        let raf;
        const tick = (now) => {
            const t = Math.min(1, (now - start) / duration);
            setCount(Math.round((1 - Math.pow(1 - t, 3)) * 100));
            if (t < 1) raf = requestAnimationFrame(tick);
            else setTimeout(() => setLeaving(true), 250);
        };
        raf = requestAnimationFrame(tick);
        return () => {
            cancelAnimationFrame(raf);
            document.documentElement.classList.remove('mx-lock');
        };
    }, []);

    return createPortal(
        <AnimatePresence
            onExitComplete={() => {
                document.documentElement.classList.remove('mx-lock');
                window.__lenis?.start();
                onDone();
            }}
        >
            {!leaving && (
                <motion.div className="mx-preloader" key="pre" aria-hidden="true">
                    <motion.div
                        className="mx-preloader__panel mx-preloader__panel--top"
                        exit={{ y: '-100%' }}
                        transition={{ duration: 1, ease: [0.76, 0, 0.24, 1] }}
                    />
                    <motion.div
                        className="mx-preloader__panel mx-preloader__panel--bottom"
                        exit={{ y: '100%' }}
                        transition={{ duration: 1, ease: [0.76, 0, 0.24, 1] }}
                    />
                    <motion.div
                        className="mx-preloader__inner"
                        exit={{ opacity: 0, scale: 1.15, filter: 'blur(12px)' }}
                        transition={{ duration: 0.5, ease: EASE }}
                    >
                        <div className="mx-preloader__brand">
                            <SplitText text={brand} stagger={0.08} />
                            <span className="mx-preloader__fill" style={{ clipPath: `inset(0 ${100 - count}% 0 0)` }}>
                                {brand}
                            </span>
                        </div>
                        <div className="mx-preloader__meta">
                            <span>Advanced Dermatology</span>
                            <span className="mx-preloader__count">{String(count).padStart(3, '0')}</span>
                        </div>
                        <div className="mx-preloader__bar">
                            <span style={{ transform: `scaleX(${count / 100})` }} />
                        </div>
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>,
        document.body,
    );
}

export default function HeroIntro({
    brand = 'MDM',
    eyebrow = 'MDM Derma',
    lines = ['Advanced', 'Dermatology'],
    words = ['Redefined.'],
    lead = '',
    primary,
    secondary,
    badges = [],
}) {
    const reduced = useReducedMotion();
    const [ready, setReady] = useState(() => reduced || hasSeenIntro());
    const [wordIndex, setWordIndex] = useState(0);

    useEffect(() => {
        if (!ready) return;
        try {
            sessionStorage.setItem(SEEN_KEY, '1');
        } catch (e) {}
        if (words.length < 2) return;
        const id = setInterval(() => setWordIndex((i) => (i + 1) % words.length), 2600);
        return () => clearInterval(id);
    }, [ready, words.length]);

    const base = 0.15;
    const lineDelay = (n) => base + n * 0.18;
    const afterLines = lineDelay(lines.length) + 0.25;

    return (
        <div className="mx-hero">
            {!ready && <Preloader brand={brand} onDone={() => setReady(true)} />}

            <motion.div
                className="mx-hero__eyebrow"
                initial={{ opacity: 0, x: -24 }}
                animate={ready ? { opacity: 1, x: 0 } : undefined}
                transition={{ duration: 0.8, ease: EASE, delay: base }}
            >
                <span className="mx-hero__pulse" />
                {eyebrow}
            </motion.div>

            <h1 className="mx-hero__title">
                {lines.map((line, n) => (
                    <span className="mx-hero__line" key={n}>
                        <SplitText text={line} delay={lineDelay(n)} stagger={0.028} animate={ready} />
                    </span>
                ))}
                <span className="mx-hero__line mx-hero__line--accent">
                    <span className="mx-hero__rotator">
                        <AnimatePresence mode="popLayout">
                            {ready && (
                                <motion.span
                                    key={wordIndex}
                                    className="mx-hero__word"
                                    initial={{ y: '100%', opacity: 0, filter: 'blur(8px)' }}
                                    animate={{ y: '0%', opacity: 1, filter: 'blur(0px)' }}
                                    exit={{ y: '-100%', opacity: 0, filter: 'blur(8px)' }}
                                    transition={{ duration: 0.7, ease: EASE, delay: wordIndex === 0 ? afterLines - 0.2 : 0 }}
                                >
                                    {words[wordIndex]}
                                </motion.span>
                            )}
                        </AnimatePresence>
                    </span>
                </span>
            </h1>

            <div className="mx-hero__bottom">
                {lead && (
                    <motion.p
                        className="mx-hero__lead"
                        initial={{ opacity: 0, y: 20 }}
                        animate={ready ? { opacity: 1, y: 0 } : undefined}
                        transition={{ duration: 0.9, ease: EASE, delay: afterLines }}
                    >
                        {lead}
                    </motion.p>
                )}

                <motion.div
                    className="mx-hero__actions"
                    initial={{ opacity: 0, y: 20 }}
                    animate={ready ? { opacity: 1, y: 0 } : undefined}
                    transition={{ duration: 0.9, ease: EASE, delay: afterLines + 0.12 }}
                >
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

                {badges.length > 0 && (
                    <ul className="mx-hero__badges">
                        {badges.map((b, i) => (
                            <motion.li
                                key={b}
                                initial={{ opacity: 0, y: 16 }}
                                animate={ready ? { opacity: 1, y: 0 } : undefined}
                                transition={{ duration: 0.7, ease: EASE, delay: afterLines + 0.3 + i * 0.08 }}
                            >
                                {b}
                            </motion.li>
                        ))}
                    </ul>
                )}
            </div>

            <motion.a
                href="#science-aesthetics"
                className="mx-hero__scroll"
                aria-label="Scroll down"
                initial={{ opacity: 0 }}
                animate={ready ? { opacity: 1 } : undefined}
                transition={{ delay: afterLines + 0.6, duration: 1 }}
            >
                <span>Scroll</span>
                <span className="mx-hero__scroll-line" />
            </motion.a>
        </div>
    );
}
