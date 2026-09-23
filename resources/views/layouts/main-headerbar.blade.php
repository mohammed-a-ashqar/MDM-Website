@php
    $mdmLogoPath = 'assets/images/mdm.png';
    $mdmLogoFile = public_path($mdmLogoPath);
    $hasMdmLogo = file_exists($mdmLogoFile);
    $mdmLogoUrl = $hasMdmLogo ? asset($mdmLogoPath) : null;
    $brandName = config('app.name', 'MDM');
@endphp

<header id="header"
	class="header header-sticky header-transparent header-sticky-smart disable-transition-all position-absolute start-0 end-0 z-index-5">
	<div class="sticky-area">
		<div
			class="main-header nav navbar navbar-dark bg-transparent navbar-expand-xl transition-all-1 py-4 py-xl-5">
			<div class="container-wide container">
				<div class="header-bar-grid">
					<a href="{{ route('home') }}"
						class="header-bar-brand navbar-brand d-inline-flex align-items-center py-2 my-0 flex-shrink-0 text-decoration-none"
						aria-label="{{ $brandName }} — Home">
						@if ($mdmLogoUrl)
							<img src="{{ $mdmLogoUrl }}" alt="{{ $brandName }}" class="img-fluid d-block header-bar-logo">
						@else
							<span class="fs-1 fw-bold lh-sm text-primary">{{ $brandName }}</span>
						@endif
					</a>
					<div class="header-bar-icons d-flex align-items-center flex-shrink-0">
						<button type="button"
							class="btn btn-sm btn-outline-light rounded-pill header-ctrl-outline border d-inline-flex d-xl-none align-items-center justify-content-center"
							style="width: 2.5rem; height: 2.5rem;"
							data-bs-toggle="offcanvas"
							data-bs-target="#offCanvasNavBar"
							aria-controls="offCanvasNavBar"
							aria-label="Open menu">
							<i class="bi bi-list" aria-hidden="true"></i>
						</button>
						@if (!empty($navBrands) && $navBrands->isNotEmpty())
							<div class="dropdown d-none d-xl-inline-flex header-products-dropdown">
								<a href="{{ route('products') }}" id="headerProductsMenu"
									class="btn btn-sm header-nav-link dropdown-toggle {{ request()->routeIs('products') || request()->routeIs('products.show') ? 'is-active' : '' }}"
									role="button"
									data-bs-toggle="dropdown" aria-expanded="false">
									Products
								</a>
								<ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 header-products-menu"
									aria-labelledby="headerProductsMenu"
									style="min-width: 16rem; max-height: 70vh; overflow-y: auto;">
									<li>
										<a class="dropdown-item fw-semibold" href="{{ route('products') }}">All products</a>
									</li>
									<li><hr class="dropdown-divider my-2"></li>
									@foreach ($navBrands as $navBrand)
										<li>
											<a class="dropdown-item fw-semibold"
												href="{{ route('products', ['brand' => $navBrand->slug]) }}">
												{{ $navBrand->name }}
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						@else
							<a href="{{ route('products') }}"
								class="btn btn-sm header-nav-link d-none d-xl-inline-flex {{ request()->routeIs('products') || request()->routeIs('products.show') ? 'is-active' : '' }}">
								Products
							</a>
						@endif
						{{-- Blogs hidden from header
						<a href="{{ route('blogs.index') }}"
							class="btn btn-sm header-nav-link d-none d-xl-inline-flex {{ request()->routeIs('blogs.*') ? 'is-active' : '' }}">
							Blogs
						</a>
						--}}
						<a href="{{ route('about') }}"
							class="btn btn-sm header-nav-link d-none d-xl-inline-flex {{ request()->routeIs('about') ? 'is-active' : '' }}">
							About
						</a>
						<a href="{{ route('contact') }}"
							class="btn btn-sm header-cta d-none d-xl-inline-flex align-items-center">
							Contact
						</a>
						<button type="button" id="header-theme-toggle"
							class="btn btn-sm btn-outline-light rounded-pill header-ctrl-outline border d-inline-flex align-items-center justify-content-center"
							style="width: 2.5rem; height: 2.5rem;" aria-label="Toggle color theme">
							<i class="bi bi-moon-fill theme-icon-when-light" aria-hidden="true"></i>
							<i class="bi bi-sun-fill theme-icon-when-dark d-none" aria-hidden="true"></i>
						</button>
					</div>
					<form class="header-bar-search d-flex align-items-center min-w-0" method="get"
						action="{{ route('products') }}" role="search">
						<label for="header-search-q" class="visually-hidden">Search products</label>
						<input id="header-search-q" type="search" name="q" value="{{ old('q', request('q')) }}"
							class="form-control form-control-sm header-search-input rounded-pill px-3"
							placeholder="Search products" autocomplete="off">
						<button type="submit"
							class="btn btn-sm rounded-pill px-3 header-search-btn flex-shrink-0"
							aria-label="Search">
							<i class="bi bi-search" aria-hidden="true"></i>
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</header>

<div id="offCanvasNavBar" class="offcanvas offcanvas-end" tabindex="-1" aria-labelledby="offCanvasNavBarLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="offCanvasNavBarLabel">{{ $brandName }}</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body">
		<ul class="list-unstyled mb-0">
			<li class="mb-3">
				<a class="text-decoration-none fw-semibold" href="{{ route('home') }}">Home</a>
			</li>
			<li class="mb-3">
				@if (!empty($navBrands) && $navBrands->isNotEmpty())
					<div class="d-flex align-items-center justify-content-between">
						<a class="text-decoration-none fw-semibold" href="{{ route('products') }}">Products</a>
						<button type="button" class="btn btn-sm btn-link text-decoration-none p-0 ms-2 collapsed" data-bs-toggle="collapse" data-bs-target="#offcanvasProductsMenu" aria-expanded="false" aria-controls="offcanvasProductsMenu" aria-label="Toggle brands">
							<i class="bi bi-chevron-down" aria-hidden="true"></i>
						</button>
					</div>
					<ul id="offcanvasProductsMenu" class="collapse list-unstyled ps-3 mt-2 mb-0">
						@foreach ($navBrands as $navBrand)
							<li class="mb-2">
								<a class="text-decoration-none fw-medium"
									href="{{ route('products', ['brand' => $navBrand->slug]) }}">
									{{ $navBrand->name }}
								</a>
							</li>
						@endforeach
					</ul>
				@else
					<a class="text-decoration-none fw-semibold" href="{{ route('products') }}">Products</a>
				@endif
			</li>
			{{-- Blogs hidden from header
			<li class="mb-3">
				<a class="text-decoration-none fw-semibold" href="{{ route('blogs.index') }}">Blogs</a>
			</li>
			--}}
			<li class="mb-3">
				<a class="text-decoration-none fw-semibold" href="{{ route('about') }}">About</a>
			</li>
			<li class="mb-3">
				<a class="text-decoration-none fw-semibold" href="{{ route('contact') }}">Contact</a>
			</li>
			{{-- Privacy Policy & Terms hidden from header
			<li class="mt-4 pt-3 border-top">
				<a class="text-decoration-none" href="{{ route('privacy') }}">Privacy Policy</a>
			</li>
			<li class="mt-3">
				<a class="text-decoration-none" href="{{ route('terms') }}">Terms &amp; Conditions</a>
			</li>
			--}}
		</ul>
	</div>
</div>
