@extends('layouts.master')
@php
    $activeBrand = $activeBrand ?? null;
    $activeCategory = $activeCategory ?? null;
    $activeSubcategory = $activeSubcategory ?? null;
    $seoScope = $activeBrand->name ?? $activeSubcategory->name ?? $activeCategory->name ?? null;
    $seoTitle = ($seoScope ?? 'All Products') . ' — ' . config('app.name', 'MDM');
    $seoDesc = $seoScope
        ? 'Browse ' . $seoScope . ' products at ' . config('app.name', 'MDM') . '. Advanced dermatology and medical aesthetic solutions for professionals.'
        : 'Explore the full ' . config('app.name', 'MDM') . ' catalog of dermatology and medical aesthetic products, devices, and professional skincare brands.';
@endphp
@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('css')



@endsection
@section('title_page')


@endsection
@section('title_page2')



@endsection
@section('content')

    <main id="content" class="wrapper layout-page">
        @php
            $activeBrand = $activeBrand ?? null;
            $activeCategory = $activeCategory ?? null;
            $activeSubcategory = $activeSubcategory ?? null;
            $pageHeading = $activeBrand->name ?? $activeSubcategory->name ?? $activeCategory->name ?? 'Products';
        @endphp
        <section class="mx-products-hero pb-12 pb-lg-15">
            <div class="container">
                <div class="text-center pt-6" data-animate="fadeInUp">
                    @if ($activeBrand && $activeBrand->logoUrl())
                        <div class="mb-4">
                            <img src="{{ $activeBrand->logoUrl() }}" alt="{{ $activeBrand->name }}"
                                class="img-fluid d-inline-block rounded-3 bg-white p-2" style="max-height: 90px; width: auto;">
                        </div>
                    @endif
                    <p class="mx-eyebrow justify-content-center">MDM Derma catalog</p>
                    <h1 class="mb-0">{{ $pageHeading }}</h1>
                    @if (!empty($search))
                        <p class="text-body-secondary mb-0 mt-3">Results for &ldquo;{{ $search }}&rdquo;</p>
                    @elseif ($activeBrand)
                        <p class="text-body-secondary mb-0 mt-3">{{ filled($activeBrand->description) ? $activeBrand->description : 'Products by ' . $activeBrand->name }}</p>
                    @elseif ($activeSubcategory && $activeCategory)
                        <p class="text-body-secondary mb-0 mt-3">{{ $activeCategory->name }} &rsaquo; {{ $activeSubcategory->name }}</p>
                    @elseif ($activeCategory)
                        <p class="text-body-secondary mb-0 mt-3">Browsing the {{ $activeCategory->name }} category</p>
                    @else
                        <p class="text-body-secondary mb-0 mt-3">All items from your catalog</p>
                    @endif
                    @if ($activeBrand || $activeCategory || $activeSubcategory)
                        <a href="{{ route('products') }}" class="btn btn-sm mx-back mt-3">
                            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> All products
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <section class="pt-10 pt-lg-13 pb-15 pb-lg-20">
            <div class="container">
                @php
                    $galleryProps = [
                        'products' => $products->map->toIslandArray()->values(),
                        'emptyText' => $products->isEmpty() ? 'No products yet.' : 'No products match your filters.',
                    ];
                @endphp
                <div data-island="ProductGallery" data-props="{{ json_encode($galleryProps) }}">
                    <div class="mx-ssr row gy-50px justify-content-center">
                        @forelse ($products as $product)
                            @include('partials.product-grid-card', ['product' => $product])
                        @empty
                            <div class="col-12 text-center py-10">
                                <p class="text-body-secondary mb-0">No products yet. Add them in the admin panel under Shop
                                    &rarr; Products.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
@section('scripts')



@endsection
