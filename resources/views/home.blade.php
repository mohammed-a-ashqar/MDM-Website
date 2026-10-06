@extends('layouts.master')
@section('title', 'MDM Derma — Advanced Dermatology & Medical Aesthetic Products')
@section('meta_description', 'MDM Derma supplies advanced dermatology and medical aesthetic products, devices, and professional skincare brands for clinics and practitioners. Discover our brands and full catalog.')
@section('meta_keywords', 'MDM Derma, dermatology products, medical aesthetics, aesthetic devices, professional skincare, mesotherapy, skin care clinic supplies')
@section('css')
    <style>
        .our-products-title {
            letter-spacing: 0.04em;
        }

        .our-products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
            width: 100%;
        }

        .our-products-tile {
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            min-height: 460px;
            padding: 2.5rem 1.5rem 3rem;
            background-color: #eceef0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-decoration: none;
            overflow: hidden;
        }

        .our-products-tile::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            transition: background .3s ease;
        }

        .our-products-tile:hover::before {
            background: rgba(0, 0, 0, 0.06);
        }

        .our-products-tile-name {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            text-align: center;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 700;
            color: #1f2937;
            padding: 0 1rem;
        }

        .our-products-btn {
            position: relative;
            z-index: 2;
            display: inline-block;
            background-color: var(--bs-primary);
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: .01em;
            padding: .85rem 2.25rem;
            border-radius: .25rem;
            transition: filter .25s ease, transform .25s ease;
        }

        .our-products-tile:hover .our-products-btn {
            filter: brightness(0.9);
            transform: translateY(-3px);
        }

        @media (min-width: 992px) {
            .our-products-tile {
                min-height: 560px;
            }
        }

        @media (max-width: 767.98px) {
            .our-products-grid {
                grid-template-columns: 1fr;
            }

            .our-products-tile {
                min-height: 360px;
            }
        }

        .clinical-innovation-section {
            --clinical-radius: clamp(1.5rem, 2vw, 2.5rem);
            background-color: #f4efe6;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath fill='%23666' fill-opacity='0.07' d='M40 4c8 12-16 20-4 32s28-8 32 4-24 20-12 32 28-4 28 4-12 16-20 24S48 76 40 68 12 84 4 76s8-28 20-36 4-28-8-32S4 40 4 28 12 4 40 4z'/%3E%3C/svg%3E");
            background-size: 80px 80px;
        }

        html[data-bs-theme="dark"] .clinical-innovation-section {
            background-color: var(--bs-body-bg);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath fill='%235b8cff' fill-opacity='0.05' d='M40 4c8 12-16 20-4 32s28-8 32 4-24 20-12 32 28-4 28 4-12 16-20 24S48 76 40 68 12 84 4 76s8-28 20-36 4-28-8-32S4 40 4 28 12 4 40 4z'/%3E%3C/svg%3E");
        }

        .clinical-innovation-section .clinical-heading {
            color: var(--bs-primary);
            font-weight: 700;
            line-height: 1.15;
            max-width: 20ch;
            font-size: clamp(2rem, 4.2vw, 3.35rem);
        }

        .clinical-card {
            border-radius: var(--clinical-radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 1rem 2.5rem rgba(var(--bs-body-color-rgb), 0.08);
        }

        html[data-bs-theme="dark"] .clinical-card {
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.35);
        }

        .clinical-card__text {
            padding: clamp(1.75rem, 4vw, 2.75rem);
        }

        .clinical-card__text--inverse {
            background-color: var(--bs-primary);
            color: var(--bs-white);
        }

        .clinical-card__text--surface {
            background-color: var(--bs-white);
            color: var(--bs-gray-800);
        }

        html[data-bs-theme="dark"] .clinical-card__text--surface {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }

        .clinical-card__text--surface .clinical-card__title {
            color: var(--bs-primary);
        }

        .clinical-badge {
            display: inline-block;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1;
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }

        .clinical-badge--on-dark {
            background: var(--bs-white);
            color: var(--bs-gray-800);
        }

        html[data-bs-theme="dark"] .clinical-badge--on-dark {
            color: var(--bs-dark);
        }

        .clinical-badge--on-light {
            background: rgba(var(--bs-primary-rgb), 0.12);
            color: var(--bs-primary);
        }

        .clinical-card__title {
            font-size: clamp(1.5rem, 2.4vw, 2rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .clinical-card__body {
            font-size: 1rem;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .clinical-card__text--inverse .clinical-card__body {
            opacity: 0.95;
        }

        .clinical-card__media {
            margin-top: auto;
        }

        .clinical-card__media img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 3 / 2;
            object-fit: cover;
        }

    </style>
@endsection
@section('title_page')


@endsection
@section('title_page2')



@endsection
@section('content')

<main id="content" class="wrapper layout-page">
    <section class="overflow-hidden">

        <div class="hero vh-100 position-relative d-flex align-items-end">
            <div class="video-cover">
                <video id="hero-video" title="Hero video" playsinline muted autoplay loop preload="metadata"
                    class="border-0">
                    <source src="{{ asset('assets/video/11.mp4') }} " type="video/mp4">
                </video>
                <div class="card-img-overlay mx-hero-overlay"></div>
                <div class="mx-hero-noise" aria-hidden="true"></div>
            </div>
            <div class="container container-wide pb-10 px-xl-15 position-relative">
                @php
                    $heroProps = [
                        'brand' => 'MDM',
                        'eyebrow' => 'Medical Aesthetics',
                        'lines' => ['Advanced', 'Dermatology,'],
                        'words' => ['Redefined.', 'Perfected.', 'Elevated.'],
                        'primary' => ['label' => 'Explore products', 'url' => route('products')],
                        'secondary' => ['label' => 'Contact', 'url' => '#get-in-touch'],
                    ];
                @endphp
                <div class="hero-content" data-island="HeroIntro" data-props="{{ json_encode($heroProps) }}">
                    <div class="mx-ssr text-white">
                        <h1 class="text-white">Advanced Dermatology, Redefined.</h1>
                        <a href="{{ route('products') }}" class="btn btn-light rounded-pill px-6 mt-4">Explore products</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $marqueeRows = [
            ['dir' => 'left', 'outline' => false, 'items' => ['Mesotherapy', 'Skin Rejuvenation', 'Anti-Aging', 'Hair Restoration']],
            ['dir' => 'right', 'outline' => true, 'items' => ['Professional Skincare', 'Pigmentation', 'Hydration', 'Clinical Results']],
        ];
    @endphp
    <section class="mx-marquee" aria-hidden="true">
        @foreach ($marqueeRows as $row)
            <div class="mx-marquee__row {{ $row['outline'] ? 'mx-marquee__row--outline' : '' }}" data-dir="{{ $row['dir'] }}">
                <div class="mx-marquee__track">
                    @for ($copy = 0; $copy < 2; $copy++)
                        <div class="mx-marquee__group">
                            @foreach ($row['items'] as $item)
                                <span class="mx-marquee__item">{{ $item }}</span>
                                <span class="mx-marquee__star">✦</span>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach
    </section>

    @include('partials.science-aesthetics', ['home' => $home, 'sectionId' => 'science-aesthetics', 'extraClass' => ''])

    <section class="mx-statement" aria-label="Our promise">
        <div class="container">
            <p class="mx-eyebrow">Our promise</p>
            <p class="mx-statement__text" data-mx-words>
                We bring the world’s most trusted dermatology brands to the clinics shaping <em>tomorrow’s skin</em> — with science you can measure and results you can see.
            </p>
            <div class="mx-statement__foot">
                <span>MDM Derma · Medical Aesthetic Solutions</span>
                <a href="{{ route('about') }}" class="mx-link">Discover our story →</a>
            </div>
        </div>
    </section>

    @if ($featuredProducts->isNotEmpty())
        @php
            $showcaseProps = [
                'eyebrow' => 'Featured formulations',
                'heading' => 'Results you can see.',
                'intro' => 'Hand-picked products our clinical partners rely on every day. Drag, swipe or use the arrows to explore.',
                'allUrl' => route('products'),
                'products' => $featuredProducts->map->toIslandArray()->values(),
            ];
        @endphp
        <section id="featured-products" class="mx-showcase-section" aria-label="Featured products">
            <div class="container-fluid px-9" data-island="ProductShowcase" data-props="{{ json_encode($showcaseProps) }}">
                <div class="mx-ssr py-14">
                    <h2 class="text-white mb-8">Featured products</h2>
                    <div class="row gy-50px">
                        @foreach ($featuredProducts as $product)
                            @include('partials.product-grid-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($categories->isNotEmpty())
        <section id="shop-by-category" class="mx-cats" aria-labelledby="mx-cats-heading">
            <div class="container">
                <div class="mx-cats__head">
                    <div>
                        <p class="mx-eyebrow">Shop by category</p>
                        <h2 id="mx-cats-heading" class="mx-cats__title mx-reveal-words">Find the right solution</h2>
                    </div>
                    <a href="{{ route('products') }}" class="mx-cats__all">All products →</a>
                </div>

                <ol class="mx-cats__list">
                    @foreach ($categories as $category)
                        @php
                            $preview = $category->products->first()?->mainImageUrl();
                        @endphp
                        <li class="mx-cats__item">
                            <a href="{{ route('products', ['category' => $category->slug]) }}" class="mx-cats__row"
                                @if ($preview) data-preview="{{ $preview }}" @endif>
                                <span class="mx-cats__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="mx-cats__name">
                                    <span class="mx-roll" data-text="{{ $category->name }}"><span>{{ $category->name }}</span></span>
                                </span>
                                @if ($category->activeSubcategories->isNotEmpty())
                                    <span class="mx-cats__subs">
                                        {{ $category->activeSubcategories->take(3)->pluck('name')->join(' · ') }}
                                    </span>
                                @endif
                                <span class="mx-cats__count">
                                    {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                </span>
                                <span class="mx-cats__arrow" aria-hidden="true">
                                    <svg width="22" height="22" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="mx-cats__preview" aria-hidden="true"><img alt=""></div>
        </section>
    @endif

    @php
        $clinicalImgFallback = 'assets/images/banner/banner-29.jpg';
        $clinicalCard1Remote =
            'https://dermalife.ae/wp-content/uploads/2025/09/view-from-rejuvenation-beautiful-woman-enjoying-cosmetology-procedures-beauty-salon-dermatology-hands-blue-glows-healthcare-therapy-botox-scaled.webp';
        $clinicalCard2Remote =
            'https://dermalife.ae/wp-content/uploads/2025/09/cosmetologist-doctor-patient-girl-look-mirror-face-skin-cosmetology-clinic-scaled.webp';

        $clinicalHeading = filled($home->clinical_heading ?? null)
            ? $home->clinical_heading
            : 'Clinical Innovation<br>for Professionals';

        $c1Badge = filled($home->clinical_card_1_badge ?? null) ? $home->clinical_card_1_badge : 'For Dermatologists';
        $c1Title = filled($home->clinical_card_1_title ?? null)
            ? $home->clinical_card_1_title
            : 'Advanced<br>Mesotherapy';
        $c1Body =
            filled($home->clinical_card_1_body ?? null) ? $home->clinical_card_1_body
            : 'Derma Life delivers advanced mesotherapy solutions addressing anti-aging, skin rejuvenation, pigmentation, and hair restoration. Each formula is sourced from leading international partners and developed through biotechnology and dermatological research, ensuring safe, predictable outcomes and consistent results for clinical practice in the UAE.';
        $c1Alt = filled($home->clinical_card_1_image_alt ?? null)
            ? $home->clinical_card_1_image_alt
            : 'Dermatology professional performing a facial treatment';
        $c1Src = filled($home->clinical_card_1_image ?? null)
            ? $home->mediaUrl($home->clinical_card_1_image, $clinicalImgFallback)
            : $clinicalCard1Remote;

        $c2Badge = filled($home->clinical_card_2_badge ?? null) ? $home->clinical_card_2_badge : 'For Clinics';
        $c2Title = filled($home->clinical_card_2_title ?? null)
            ? $home->clinical_card_2_title
            : 'Professional<br>Skincare Kits';
        $c2Body =
            filled($home->clinical_card_2_body ?? null) ? $home->clinical_card_2_body
            : 'Derma Life provides professional skincare kits that fully support doctors before and after treatment. Combining peptides, active ingredients, and hydration boosters, each kit enhances recovery, improves treatment outcomes, and ensures dermatologists and clinics achieve safe, predictable results with full confidence in daily practice.';
        $c2Alt = filled($home->clinical_card_2_image_alt ?? null)
            ? $home->clinical_card_2_image_alt
            : 'Cosmetologist with patient reviewing results in a mirror';
        $c2Src = filled($home->clinical_card_2_image ?? null)
            ? $home->mediaUrl($home->clinical_card_2_image, $clinicalImgFallback)
            : $clinicalCard2Remote;
    @endphp

    <section id="clinical-innovation" class="clinical-innovation-section py-14 py-lg-18 overflow-hidden"
        aria-labelledby="clinical-innovation-heading">
        <div class="container-fluid px-9">
            <div class="row align-items-start g-4 gx-lg-5">
                <div class="col-12 col-lg-4 col-xl-3" data-animate="fadeInUp">
                    <h2 id="clinical-innovation-heading" class="clinical-heading mb-0 pe-lg-4">{!! $clinicalHeading !!}</h2>
                </div>
                <div class="col-12 col-lg-8 col-xl-9">
                    <div class="row g-4 gx-lg-5 gy-5">
                        <div class="col-12 col-md-6" data-animate="fadeInUp">
                            <article class="clinical-card">
                                <div class="clinical-card__text clinical-card__text--inverse">
                                    <span class="clinical-badge clinical-badge--on-dark">{{ $c1Badge }}</span>
                                    <h3 class="clinical-card__title text-white">{!! $c1Title !!}</h3>
                                    <p class="clinical-card__body">{{ $c1Body }}</p>
                                </div>
                                <div class="clinical-card__media">
                                    <img src="{{ $c1Src }}" width="2560" height="1707" alt="{{ $c1Alt }}"
                                        loading="lazy" decoding="async"
                                        sizes="(max-width:767px) 100vw, (max-width:1200px) 50vw, 800px">
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-md-6" data-animate="fadeInUp">
                            <article class="clinical-card">
                                <div class="clinical-card__text clinical-card__text--surface">
                                    <span class="clinical-badge clinical-badge--on-light">{{ $c2Badge }}</span>
                                    <h3 class="clinical-card__title">{!! $c2Title !!}</h3>
                                    <p class="clinical-card__body text-body">{{ $c2Body }}</p>
                                </div>
                                <div class="clinical-card__media">
                                    <img src="{{ $c2Src }}" width="2560" height="1707" alt="{{ $c2Alt }}"
                                        loading="lazy" decoding="async"
                                        sizes="(max-width:767px) 100vw, (max-width:1200px) 50vw, 800px">
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (!empty($brands) && $brands->isNotEmpty())
        <section id="our-products" class="our-products-section pt-13 pt-lg-15">
            <div class="text-center mb-9 mb-lg-11" data-animate="fadeInUp">
                <h2 class="h3 our-products-title text-uppercase mb-0">Our Products</h2>
            </div>

            <div class="our-products-grid">
                @foreach ($brands as $brand)
                    <a href="{{ route('products', ['brand' => $brand->slug]) }}"
                        class="our-products-tile"
                        @if ($brand->logoUrl()) style="background-image: url('{{ $brand->logoUrl() }}');" @endif
                        aria-label="{{ $brand->name }}">
                        @if (!$brand->logoUrl())
                            <span class="our-products-tile-name">{{ $brand->name }}</span>
                        @endif
                        <span class="our-products-btn">Learn More</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @php
        $chooseHeading = (string) ($home->choose_us_heading ?? '');
        $chooseIntro = (string) ($home->choose_us_intro ?? '');
        $chooseItems = is_array($home->choose_us_items ?? null) ? $home->choose_us_items : [];
    @endphp

    @if (filled($chooseHeading) || filled($chooseIntro) || count($chooseItems))
        <section id="why-choose-us" class="pt-14 pb-16 py-lg-18">
            <div class="container">
                <div class="text-center mb-12" data-animate="fadeInUp">
                    @if (filled($chooseHeading))
                        <h2 class="mb-5">{{ $chooseHeading }}</h2>
                    @endif
                    @if (filled($chooseIntro))
                        <p class="mb-0 mw-xl-60 mw-lg-75 ms-auto me-auto text-body">{{ $chooseIntro }}</p>
                    @endif
                </div>

                <div class="row g-0 choose-us-grid rounded-4 overflow-hidden bg-body shadow-sm" data-animate="fadeInUp">
                    @foreach (array_slice($chooseItems, 0, 4) as $item)
                        @php
                            $icon = trim((string) ($item['icon'] ?? 'bi bi-star'));
                            $title = trim((string) ($item['title'] ?? ''));
                            $body = trim((string) ($item['body'] ?? ''));
                        @endphp
                        <div class="col-12 col-md-6 col-lg-3 choose-us-item p-8 p-lg-9">
                            <div class="choose-us-icon text-primary mb-6">
                                <span class="choose-us-icon-ring d-inline-flex align-items-center justify-content-center">
                                    <i class="{{ $icon }}" aria-hidden="true"></i>
                                </span>
                            </div>
                            @if ($title !== '')
                                <h3 class="fs-5 mb-4">{{ $title }}</h3>
                            @endif
                            @if ($body !== '')
                                <p class="mb-0 text-body">{{ $body }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-10" data-animate="fadeInUp">
                    <p class="mb-0 text-body-secondary">
                        With fast nationwide delivery and expert support, we ensure a seamless experience from order to application.
                    </p>
                </div>
            </div>
        </section>
    @endif

    <section id="from_our_blog_2" class="pt-14 pb-16 py-lg-18 mt-1">

        <div class="container">
            <div class="text-center" data-animate="fadeInUp">
                @if (filled($home->blog_section_heading))
                    <h2 class="mb-6">{{ $home->blog_section_heading }}</h2>
                @endif
                @if (filled($home->blog_section_intro))
                    <p class="fs-18px mb-0 mw-xl-50 mw-lg-75 ms-auto me-auto">{{ $home->blog_section_intro }}</p>
                @endif
            </div>

        </div>
        <div class="container mt-12 pt-3">
            @if ($blogs->isEmpty())
                <p class="text-center text-body-secondary mb-0">No blog posts yet. Add posts under Admin → Content →
                    Blog.</p>
            @else
                <div class="slick-slider"
                    data-slick-options='{&#34;arrows&#34;:false,&#34;dots&#34;:false,&#34;responsive&#34;:[{&#34;breakpoint&#34;:1200,&#34;settings&#34;:{&#34;slidesToShow&#34;:3}},{&#34;breakpoint&#34;:992,&#34;settings&#34;:{&#34;dots&#34;:true,&#34;slidesToShow&#34;:2}},{&#34;breakpoint&#34;:768,&#34;settings&#34;:{&#34;dots&#34;:true,&#34;slidesToShow&#34;:1}}],&#34;slidesToShow&#34;:3}'>
                    @foreach ($blogs as $post)
                        @php
                            $thumb =
                                $post->mainImageUrl() ??
                                asset('assets/images/blog/post-04-370x450.jpg');
                        @endphp
                        <div>
                            <article class="card card-post-grid-1 bg-transparent border-0" data-animate="fadeInUp">
                                <figure class="card-img-top position-relative mb-10">
                                    <a href="{{ route('blogs.show', $post) }}" class="hover-shine hover-zoom-in d-block"
                                        title="{{ $post->title }}">
                                        <img data-src="{{ $thumb }}" class="img-fluid lazy-image w-100"
                                            alt="{{ $post->title }}" width="370" height="450" src="#">
                                    </a>
                                    @if (filled($post->name))
                                        <span
                                            class="post-item-cate btn btn-light btn-text-light-body-emphasis btn-hover-bg-dark btn-hover-text-light fw-500 post-cat position-absolute top-100 start-50 translate-middle py-2 px-7 border-0">{{ $post->name }}</span>
                                    @endif
                                </figure>
                                <div class="card-body text-center px-md-9 py-0">
                                    <h4 class="card-title lh-base mb-9">
                                        <a class="text-decoration-none" href="{{ route('blogs.show', $post) }}"
                                            title="{{ $post->title }}">{{ $post->title }}</a>
                                    </h4>
                                    <ul class="post-meta list-inline lh-1 d-flex flex-wrap justify-content-center m-0">
                                        <li class="list-inline-item">{{ $post->created_at?->format('M j, Y') }}</li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section id="get-in-touch" class="pt-14 pb-16 py-lg-18 bg-section-2">
        <div class="container">
            <div class="row align-items-stretch gy-10">
                <div class="col-lg-5" data-animate="fadeInUp">
                    <div class="pe-lg-10">
                        <p class="text-primary fw-semibold mb-3 text-uppercase ls-1">Get In Touch</p>
                        <h2 class="mb-6">We’d love to hear from you</h2>
                        <p class="text-body mb-8">Send us a message and our team will reply as soon as possible.</p>

                        <div class="d-flex align-items-start mb-7">
                            <div class="me-5 text-primary">
                                <i class="bi bi-geo-alt fs-2" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-body-emphasis mb-2">{{ $contact->address_heading ?? 'Address' }}</div>
                                @if (filled($contact->address_body ?? ''))
                                    <div class="text-body">{!! nl2br(e($contact->address_body)) !!}</div>
                                @else
                                    <div class="text-body">—</div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-7">
                            <div class="me-5 text-primary">
                                <i class="bi bi-telephone fs-2" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-body-emphasis mb-2">{{ $contact->contact_heading ?? 'Contact' }}</div>
                                @if (filled($contact->mobile ?? ''))
                                    <div class="text-body">{{ $contact->mobile_label ?? 'Mobile:' }} <span class="text-body-emphasis">{{ $contact->mobile }}</span></div>
                                @endif
                                @if (filled($contact->hotline ?? ''))
                                    <div class="text-body">{{ $contact->hotline_label ?? 'Hotline:' }} <span class="text-body-emphasis">{{ $contact->hotline }}</span></div>
                                @endif
                                @if (filled($contact->email ?? ''))
                                    <div class="text-body">{{ $contact->email_label ?? 'Email:' }}
                                        <a class="text-body-emphasis text-decoration-none" href="mailto:{{ e($contact->email) }}">{{ $contact->email }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if (filled($contact->directions_url ?? ''))
                            <a href="{{ $contact->directions_url }}"
                                class="btn btn-outline-primary rounded-pill px-6"
                                @if (str_starts_with((string) $contact->directions_url, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $contact->directions_label ?? 'Get Directions' }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7" data-animate="fadeInUp">
                    <div class="bg-body rounded-4 shadow-sm p-8 p-lg-10 h-100">
                        <h3 class="mb-7">{{ $contact->form_heading ?? 'Send a message' }}</h3>

                        @if (session('contact_sent'))
                            <div class="alert alert-success mb-8" role="alert">
                                Thank you — your message was received.
                            </div>
                        @endif

                        <form class="contact-form" method="post" action="{{ route('contact.submit') }}">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}#get-in-touch">

                            <div class="row mb-7">
                                <div class="col-md-6 mb-6 mb-md-0">
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control input-focus @error('name') is-invalid @enderror"
                                        placeholder="{{ $contact->placeholder_name ?? 'Your name' }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control input-focus @error('email') is-invalid @enderror"
                                        placeholder="{{ $contact->placeholder_email ?? 'Your email' }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <textarea name="message"
                                class="form-control mb-6 input-focus @error('message') is-invalid @enderror"
                                placeholder="{{ $contact->placeholder_message ?? 'Your message' }}" rows="7"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback d-block mb-6">{{ $message }}</div>
                            @enderror

                            @if (filled($contact->checkbox_label ?? ''))
                                <div class="form-check mb-8">
                                    <input class="form-check-input rounded" type="checkbox" value="1" name="remember"
                                        id="homeContactRemember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="homeContactRemember">
                                        {{ $contact->checkbox_label }}
                                    </label>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary btn-hover-bg-primary btn-hover-border-primary rounded-pill px-10">
                                    {{ $contact->submit_label ?? 'Send Message' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="position-relative  lazy-bg bg-image pt-lg-17 pb-lg-17 pt-15 pb-15">

        <div class="container text-center position-relative z-index-2">
            <div class="mx-auto mb-11 text-center" style="max-width:509px" data-animate="fadeInUp">
                <h3 class="mb-6">Stay Up to Date with All News and Exclusive Offers</h3>
            </div>

            <form class="mx-auto up-to-date-form" style="max-width: 546px" data-animate="fadeInUp">
                <div class="text-center">
                    <div class=" input-group position-relative mb-11 form-border-transparent">
                        <input type="email" class="form-control bg-body rounded-left"
                            placeholder="Enter your email address">
                        <button type="submit"
                            class=" btn btn-dark btn-hover-bg-primary btn-hover-border-primary rounded ms-0">Subscribe</button>
                    </div>
                    <div class="form-check d-flex justify-content-center">
                        <input class="form-check-input me-4 rounded" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label text-body fs-14px" for="flexCheckDefault">
                            I accept the <a href="#" class="text-decoration-none border-bottom">terms &
                                conditions</a> and <a href="#" class="text-decoration-none border-bottom">the data
                                protection</a>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        <div class="lazy-bg bg-overlay position-absolute z-index-1 w-100 h-100   light-mode-img"
            data-bg-src="{{ asset('assets/images/others/news-letter-background.jpg') }} ">
        </div>
        <div class="lazy-bg bg-overlay dark-mode-img position-absolute z-index-1 w-100 h-100"
            data-bg-src="{{ asset('assets/images/others/news-letter-background-white-02.jpg') }}">
        </div>
    </section>

</main>

@endsection
@section('scripts')



@endsection

