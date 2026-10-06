@php
    $footerContact = \Illuminate\Support\Facades\Cache::remember(
        \App\Models\ContactPage::CACHE_KEY,
        \App\Models\ContactPage::CACHE_TTL_SECONDS,
        fn () => \App\Models\ContactPage::query()->first()
    );
    $footerCategories = \Illuminate\Support\Facades\Cache::remember('footer_categories', 300, fn () =>
        \App\Models\Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->limit(6)->get(['name', 'slug'])
    );
    $footerBrands = ($navBrands ?? collect())->take(6);
    $brandName = config('app.name', 'MDM');
@endphp

<footer class="mx-footer">
    <div class="mx-footer__glow" aria-hidden="true"></div>
    <div class="container mx-footer__inner">
        <div class="mx-footer__top">
            <div class="mx-footer__intro">
                <p class="mx-footer__eyebrow">{{ $brandName }} Derma</p>
                <h2 class="mx-footer__headline">Care for your skin.<br><em>Care for your beauty.</em></h2>
                <a href="{{ route('contact') }}" class="mx-footer__cta">
                    <span>Start a conversation</span>
                    <span class="mx-footer__cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </a>
            </div>

            <nav class="mx-footer__cols" aria-label="Footer">
                <div>
                    <h3 class="mx-footer__h">Explore</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products') }}">Products</a></li>
                        <li><a href="{{ route('about') }}">About us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                @if ($footerCategories->isNotEmpty())
                    <div>
                        <h3 class="mx-footer__h">Categories</h3>
                        <ul>
                            @foreach ($footerCategories as $fc)
                                <li><a href="{{ route('products', ['category' => $fc->slug]) }}">{{ $fc->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($footerBrands->isNotEmpty())
                    <div>
                        <h3 class="mx-footer__h">Brands</h3>
                        <ul>
                            @foreach ($footerBrands as $fb)
                                <li><a href="{{ route('products', ['brand' => $fb->slug]) }}">{{ $fb->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($footerContact && (filled($footerContact->email) || filled($footerContact->mobile) || filled($footerContact->hotline)))
                    <div>
                        <h3 class="mx-footer__h">Get in touch</h3>
                        <ul>
                            @if (filled($footerContact->email))
                                <li><a href="mailto:{{ $footerContact->email }}">{{ $footerContact->email }}</a></li>
                            @endif
                            @if (filled($footerContact->mobile))
                                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerContact->mobile) }}">{{ $footerContact->mobile }}</a></li>
                            @endif
                            @if (filled($footerContact->hotline))
                                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerContact->hotline) }}">{{ $footerContact->hotline }}</a></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </nav>
        </div>

        <div class="mx-footer__bottom">
            <p>© {{ date('Y') }} {{ config('app.copyright_holder') }}. All rights reserved.</p>
            <ul>
                <li><a href="{{ route('terms') }}">Terms &amp; conditions</a></li>
                <li><a href="{{ route('privacy') }}">Privacy policy</a></li>
                <li><button type="button" class="mx-footer__top-btn" data-mx-to-top>Back to top ↑</button></li>
            </ul>
        </div>
    </div>

    <div class="mx-footer__word" aria-hidden="true">{{ $brandName }}</div>
</footer>
