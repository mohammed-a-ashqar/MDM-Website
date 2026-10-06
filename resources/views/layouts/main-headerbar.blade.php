@php
    $mdmLogoPath = 'assets/images/mdm.png';
    $hasMdmLogo = file_exists(public_path($mdmLogoPath));
    $mdmLogoUrl = $hasMdmLogo ? asset($mdmLogoPath) : null;
    $brandName = config('app.name', 'MDM');
    $navBrands = $navBrands ?? collect();

    $navItems = [
        ['label' => 'Home', 'url' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Products', 'url' => route('products'), 'active' => request()->routeIs('products', 'products.show'), 'mega' => $navBrands->isNotEmpty()],
        ['label' => 'About', 'url' => route('about'), 'active' => request()->routeIs('about')],
        ['label' => 'Contact', 'url' => route('contact'), 'active' => request()->routeIs('contact')],
    ];
@endphp

<header id="mx-header" class="mx-header" data-mx-header>
    <div class="mx-header__bar">
        <a href="{{ route('home') }}" class="mx-header__logo" aria-label="{{ $brandName }} — Home">
            @if ($mdmLogoUrl)
                <img src="{{ $mdmLogoUrl }}" alt="{{ $brandName }}" width="120" height="60">
            @else
                <span>{{ $brandName }}</span>
            @endif
        </a>

        <nav class="mx-header__nav" aria-label="Main">
            <ul>
                @foreach ($navItems as $item)
                    <li @class(['has-mega' => $item['mega'] ?? false])>
                        <a href="{{ $item['url'] }}" @class(['mx-nav-link', 'is-active' => $item['active']])
                            @if ($item['active']) aria-current="page" @endif>
                            <span class="mx-roll" data-text="{{ $item['label'] }}"><span>{{ $item['label'] }}</span></span>
                            @if ($item['mega'] ?? false)
                                <svg class="mx-nav-caret" width="10" height="10" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </a>
                        @if ($item['mega'] ?? false)
                            <div class="mx-mega">
                                <div class="mx-mega__inner">
                                    <div class="mx-mega__intro">
                                        <p class="mx-eyebrow">Our brands</p>
                                        <p class="mx-mega__title">Explore the full catalog</p>
                                        <a href="{{ route('products') }}" class="mx-mega__all">All products →</a>
                                    </div>
                                    <ul class="mx-mega__list">
                                        @foreach ($navBrands as $navBrand)
                                            <li>
                                                <a href="{{ route('products', ['brand' => $navBrand->slug]) }}">
                                                    <span>{{ $navBrand->name }}</span>
                                                    <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="mx-header__tools">
            <button type="button" class="mx-icon-btn" data-mx-search-open aria-label="Search products">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <button type="button" id="header-theme-toggle" class="mx-icon-btn" aria-label="Toggle color theme">
                <i class="bi bi-moon-fill theme-icon-when-light" aria-hidden="true"></i>
                <i class="bi bi-sun-fill theme-icon-when-dark d-none" aria-hidden="true"></i>
            </button>
            <a href="{{ route('contact') }}" class="mx-header__cta">
                <span class="mx-roll" data-text="Get in touch"><span>Get in touch</span></span>
            </a>
            <button type="button" class="mx-burger" data-mx-menu-toggle aria-label="Open menu" aria-expanded="false" aria-controls="mx-menu">
                <span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- Full-screen mobile menu --}}
<div id="mx-menu" class="mx-menu" aria-hidden="true" data-lenis-prevent>
    <div class="mx-menu__bg"></div>
    <nav class="mx-menu__nav" aria-label="Mobile">
        <ul>
            @foreach ($navItems as $i => $item)
                <li style="--i: {{ $i }}">
                    <a href="{{ $item['url'] }}" @class(['is-active' => $item['active']])>
                        <small>0{{ $i + 1 }}</small>{{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if ($navBrands->isNotEmpty())
            <div class="mx-menu__brands" style="--i: {{ count($navItems) }}">
                <p class="mx-eyebrow">Brands</p>
                <div>
                    @foreach ($navBrands as $navBrand)
                        <a href="{{ route('products', ['brand' => $navBrand->slug]) }}">{{ $navBrand->name }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>
</div>

{{-- Full-screen search --}}
<div class="mx-search-overlay" aria-hidden="true" data-mx-search>
    <button type="button" class="mx-round mx-search-overlay__close" data-mx-search-close aria-label="Close search">✕</button>
    <form method="get" action="{{ route('products') }}" role="search" class="mx-search-overlay__form">
        <p class="mx-eyebrow">Search the catalog</p>
        <label for="mx-search-q" class="visually-hidden">Search products</label>
        <input id="mx-search-q" type="search" name="q" value="{{ request('q') }}" placeholder="What are you looking for?" autocomplete="off">
        <p class="mx-search-overlay__hint">Press Enter to search · Esc to close</p>
    </form>
</div>
