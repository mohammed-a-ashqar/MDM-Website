@extends('layouts.master')

@php
    use Illuminate\Support\Str;

    $seoDesc = filled($product->description)
        ? Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($product->description))), 160)
        : $product->title . ' — available at ' . config('app.name', 'MDM') . ', supplier of dermatology and medical aesthetic products for professionals.';
    $seoImage = $product->mainImageUrl();
@endphp

@section('title', $product->title . ' — ' . config('app.name', 'MDM'))
@section('meta_description', $seoDesc)
@section('og_type', 'product')
@if ($seoImage)
    @section('og_image', $seoImage)
@endif

@section('structured_data')
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->title,
        'description' => $seoDesc,
        'image' => $product->galleryUrls(),
        'sku' => (string) $product->id,
        'brand' => optional($product->brand)->name ? [
            '@type' => 'Brand',
            'name' => $product->brand->name,
        ] : null,
        'category' => optional($product->category)->name,
        'url' => route('products.show', $product),
        'offers' => (float) $product->price > 0 ? [
            '@type' => 'Offer',
            'url' => route('products.show', $product),
            'priceCurrency' => 'USD',
            'price' => number_format((float) $product->price, 2, '.', ''),
            'availability' => 'https://schema.org/InStock',
        ] : null,
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('css')
    <style>
        .page-product-detail {
            --product-surface: #f9f7f2;
        }

        html[data-bs-theme="dark"] .page-product-detail {
            --product-surface: var(--bs-body-bg);
        }

        .page-product-detail .product-detail-hero {
            background: var(--product-surface);
        }

        .page-product-detail .product-detail-gallery-wrap {
            background: var(--bs-body-bg);
            border-radius: 1.25rem;
            padding: clamp(0.75rem, 2vw, 1.25rem);
            box-shadow: 0 0.4rem 1.75rem rgba(var(--bs-body-color-rgb), 0.07);
            border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
        }

        html[data-bs-theme="dark"] .page-product-detail .product-detail-gallery-wrap {
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.25);
            border-color: rgba(255, 255, 255, 0.06);
        }

        .page-product-detail .product-detail-gallery-wrap .hover-zoom-in img {
            border-radius: 0.75rem;
        }

        .page-product-detail .product-detail-title {
            font-size: clamp(1.65rem, 3vw, 2.15rem);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--bs-primary);
        }

        .page-product-detail .product-detail-lead {
            font-size: 1.0625rem;
            line-height: 1.7;
            color: var(--bs-body-color);
            max-width: 36rem;
        }

        .page-product-detail .product-detail-tabs-section {
            background: var(--bs-body-bg);
        }

        .page-product-detail .nav-tabs .nav-link {
            border-radius: 0;
        }

        .page-product-detail #productTabs .nav-link.active {
            color: var(--bs-primary) !important;
            border-bottom: 2px solid var(--bs-primary) !important;
            background: transparent !important;
        }

        .page-product-detail #productTabs .nav-link:not(.active):hover {
            color: var(--bs-primary);
            opacity: 0.85;
        }

        .page-product-detail .product-related-heading {
            color: var(--bs-primary);
            font-weight: 700;
        }

        .page-product-detail .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
        }
    </style>
@endsection

@section('title_page')
@endsection

@section('title_page2')
@endsection

@section('content')
    @php
        $galleryUrls = $product->galleryUrls();
        $placeholder = asset('assets/images/products/product-11-330x440.jpg');
    @endphp

    <main id="content" class="wrapper layout-page page-product-detail">
        @php
            $pdImages = count($galleryUrls) ? $galleryUrls : [$placeholder];
            $pdProps = [
                'product' => $product->toIslandArray(),
                'images' => array_values($pdImages),
                'crumbs' => [
                    ['label' => 'Home', 'url' => url('/')],
                    ['label' => 'Products', 'url' => route('products')],
                    ['label' => $product->title],
                ],
                'primary' => ['label' => 'Request this product', 'url' => route('contact')],
                'secondary' => filled($product->how_to_use)
                    ? ['label' => 'How to use', 'url' => '#product-info']
                    : ['label' => 'Full details', 'url' => '#product-info'],
            ];
        @endphp
        <section class="mx-pd-section">
            <div class="container" data-island="ProductDetail" data-props="{{ json_encode($pdProps) }}">
                <div class="mx-ssr row g-5 align-items-center">
                    <div class="col-md-6">
                        <img src="{{ $pdImages[0] }}" class="img-fluid rounded-4 bg-white" alt="{{ $product->title }}">
                    </div>
                    <div class="col-md-6">
                        <h1 class="text-white">{{ $product->title }}</h1>
                    </div>
                </div>
            </div>
        </section>


        <section id="product-info" class="product-detail-tabs-section container pt-14 pb-12 pt-lg-16 pb-lg-20">
            <div class="collapse-tabs">
                <ul class="nav nav-tabs border-0 justify-content-center pb-10 pb-md-12 d-none d-md-flex gap-md-2"
                    id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link m-auto fw-semibold py-3 px-6 fs-5 border-0 text-body-emphasis active"
                            id="product-details-tab" data-bs-toggle="tab" data-bs-target="#product-details" type="button"
                            role="tab" aria-controls="product-details" aria-selected="true">Product details</button>
                    </li>
                    @if (filled($product->how_to_use))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link m-auto fw-semibold py-3 px-6 fs-5 border-0 text-body-emphasis"
                                id="how-to-use-tab" data-bs-toggle="tab" data-bs-target="#how-to-use" type="button"
                                role="tab" aria-controls="how-to-use" aria-selected="false">How to use</button>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-inner">
                        <div class="tab-pane fade show active" id="product-details" role="tabpanel"
                            aria-labelledby="product-details-tab" tabindex="0">
                            <div class="card border-0 bg-transparent">
                                <div
                                    class="card-header border-0 bg-transparent px-0 py-4 product-tabs-mobile d-block d-md-none">
                                    <h5 class="mb-0">
                                        <button class="btn lh-2 fs-5 py-3 px-6 shadow-none w-100 border text-primary"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse-product-detail"
                                            aria-expanded="true" aria-controls="collapse-product-detail">Product details</button>
                                    </h5>
                                </div>
                                <div class="collapse show border-md-0 border rounded-3 p-md-0 p-6 bg-body-tertiary bg-opacity-25"
                                    id="collapse-product-detail">
                                    <div class="prose fs-15px text-body product-detail-prose mx-auto" style="max-width: 52rem;">
                                        @if (filled($product->description))
                                            {!! $product->description !!}
                                        @else
                                            <p class="text-body-secondary mb-0">No description has been added for this product yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (filled($product->how_to_use))
                            <div class="tab-pane fade" id="how-to-use" role="tabpanel" aria-labelledby="how-to-use-tab"
                                tabindex="0">
                                <div class="card border-0 bg-transparent">
                                    <div
                                        class="card-header border-0 bg-transparent px-0 py-4 product-tabs-mobile d-block d-md-none">
                                        <h5 class="mb-0">
                                            <button class="btn lh-2 fs-5 py-3 px-6 shadow-none w-100 border text-primary"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse-how-to-use"
                                                aria-expanded="false" aria-controls="collapse-how-to-use">How to use</button>
                                        </h5>
                                    </div>
                                    <div class="collapse border-md-0 border rounded-3 p-md-0 p-6 bg-body-tertiary bg-opacity-25"
                                        id="collapse-how-to-use">
                                        <div class="prose fs-15px text-body mx-auto" style="max-width: 52rem;">
                                            {!! $product->how_to_use !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if ($relatedProducts->isNotEmpty())
            <div class="border-top border-opacity-10 w-100"></div>
            <section class="container pt-14 pb-15 pt-lg-16 pb-lg-20">
                <div class="text-center mb-10 mb-lg-11">
                    <p class="mx-eyebrow justify-content-center">Keep exploring</p>
                    <h2 class="mx-reveal-words mx-related-heading mb-0">You may also like</h2>
                </div>
                <div data-island="ProductGallery"
                    data-props="{{ json_encode(['products' => $relatedProducts->map->toIslandArray()->values(), 'toolbar' => false]) }}">
                    <div class="mx-ssr row gy-50px justify-content-center">
                        @foreach ($relatedProducts as $related)
                            @include('partials.product-grid-card', ['product' => $related])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection

@section('scripts')
@endsection
