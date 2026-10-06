import React from 'react';
import { createRoot } from 'react-dom/client';
import { MotionConfig } from 'framer-motion';
import './islands/fx.css';
import HeroIntro from './islands/HeroIntro';
import ProductShowcase from './islands/ProductShowcase';
import ProductGallery from './islands/ProductGallery';
import ProductDetail from './islands/ProductDetail';
import { initGlobalFx } from './islands/globalFx';
import { initScrollFx } from './islands/scrollFx';

/*
 * React "islands": Blade renders a server-side fallback inside
 * <div data-island="Name" data-props="{json}">, and React takes it over here.
 */
const registry = { HeroIntro, ProductShowcase, ProductGallery, ProductDetail };

function mountIslands() {
    document.querySelectorAll('[data-island]').forEach((el) => {
        const Component = registry[el.dataset.island];
        if (!Component || el.dataset.islandMounted) return;

        let props = {};
        try {
            props = JSON.parse(el.dataset.props || '{}');
        } catch (e) {
            console.error(`[islands] bad props for ${el.dataset.island}`, e);
            return;
        }

        el.dataset.islandMounted = '1';
        el.classList.add('mx-island-ready');
        createRoot(el).render(
            <MotionConfig reducedMotion="user">
                <Component {...props} />
            </MotionConfig>,
        );
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        mountIslands();
        initGlobalFx();
        initScrollFx();
    });
} else {
    mountIslands();
    initGlobalFx();
    initScrollFx();
}
