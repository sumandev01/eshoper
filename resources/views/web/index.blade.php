@extends('web.layouts.app')
@section('content')
    <h1 class="d-none">{{ ($siteSettings->site_title ?? null) }} - Quality Products and Trending Fashion</h1>
    <!-- Featured Start -->
    <div class="container pt-5">
        <div class="row pb-3">
            @if(isset($storeFeatures) && $storeFeatures->count() > 0)
                @foreach($storeFeatures as $feature)
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="d-flex align-items-center theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white;">
                            <h1 class="{{ str_starts_with($feature->icon, 'fas ') || str_starts_with($feature->icon, 'fab ') ? $feature->icon : 'fa ' . $feature->icon }} text-primary m-0 me-3"></h1>
                            <h5 class="font-weight-semi-bold m-0">{{ $feature->title }}</h5>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white;">
                        <h1 class="fa fa-check text-primary m-0 me-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white;">
                        <h1 class="fa fa-shipping-fast text-primary m-0 me-2"></h1>
                        <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white;">
                        <h1 class="fas fa-exchange-alt text-primary m-0 me-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white;">
                        <h1 class="fa fa-phone-volume text-primary m-0 me-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Featured End -->
    <!-- Categories Start -->
    <div class="container pt-5">
        <div class="row pb-3">
            <div class="col">
                <div class="owl-carousel category-carousel">
                    @foreach (($categories ?? [])->sortByDesc('products_count') as $category)
                        <div class="cat-item d-flex flex-column theme-shadow mb-4 transition-all hover-up" style="padding: 30px; border-radius: 12px; background: white; border: none !important;">
                            <p class="text-right">{{ $category?->products_count }} Products</p>
                            <a href="{{ route('category.products', $category?->slug ?? '#') }}"
                                class="cat-img position-relative overflow-hidden mb-3">
                                <img class="img-fluid" src="{{ $category?->image }}" alt=""
                                    style="aspect-ratio: 4/3;">
                            </a>
                            <h5 class="font-weight-semi-bold m-0">
                                {{ $category?->name }}
                            </h5>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Categories End -->
    <!-- Offer Start -->
    <div class="container offer pt-5">
        <div class="row">
            @if($offer1)
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-end mb-2 py-5 px-5 theme-shadow transition-all hover-up" style="border-radius: 12px; overflow: hidden;">
                    <img src="{{ $offer1->image_url }}" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">{{ $offer1->subtitle ?? '20% off the all order' }}</h5>
                        <h1 class="mb-4 font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111) !important;">{{ $offer1->title ?? 'Spring Collection' }}</h1>
                        <a href="{{ $offer1->link ?? '/shop' }}" class="btn btn-primary py-md-2 px-md-4 transition-all">Shop Now</a>
                    </div>
                </div>
            </div>
            @endif
            
            @if($offer2)
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-start mb-2 py-5 px-5 theme-shadow transition-all hover-up" style="border-radius: 12px; overflow: hidden;">
                    <img src="{{ $offer2->image_url }}" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">{{ $offer2->subtitle ?? '20% off the all order' }}</h5>
                        <h1 class="mb-4 font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111) !important;">{{ $offer2->title ?? 'Winter Collection' }}</h1>
                        <a href="{{ $offer2->link ?? '/shop' }}" class="btn btn-primary py-md-2 px-md-4 transition-all">Shop Now</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Offer End -->
    <!-- Products Start -->
    <div class="container pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">{{ $siteSettings->home_trending_title ?? 'Trendy Products' }}</span></h2>
        </div>
        <div class="row pb-3">
            @forelse ($trendingProducts ?? [] as $product)
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    @include('web.components.product_card', ['product' => $product, 'isFirst' => $loop->iteration <= 4])
                </div>
            @empty
                <div class="col-md-12">
                    <h3 class="text-center mt-5">No Product Found</h3>
                </div>
            @endforelse
        </div>
    </div>
    <!-- Products End -->
    <!-- Subscribe Start -->
    <div class="container-fluid bg-secondary my-5">
        <div class="row justify-content-md-center px-xl-5">
            <div class="col-xl-6 col-lg-8 col-12 py-5">
                <div class="text-center mb-2 pb-2">
                    <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">{{ $siteSettings->subscribe_heading ?? 'Stay Updated' }}</span></h2>
                    <p>{{ $siteSettings->subscribe_text ?? 'Amet lorem at rebum amet dolores. Elitr lorem dolor sed amet diam labore at justo ipsum eirmod duo labore labore.' }}</p>
                </div>
                <form action="{{ route('subscribe') }}" method="POST">
                    @csrf
                      <div class="input-group" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden;">
                          <input type="email" name="email" class="form-control border-white p-3" placeholder="Email Goes Here" required style="border: none !important;">
                          <button class="btn btn-primary px-4" type="submit" style="border-radius: 0;">Subscribe</button>
                      </div>
                    @error('email')
                        <span class="text-danger small mt-1 d-block text-left">{{ $message }}</span>
                    @enderror
                    @if(session('success'))
                        <span class="text-success small mt-1 d-block text-left">{{ session('success') }}</span>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <!-- Subscribe End -->
    <!-- Products Start -->
    <div class="container pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">{{ $siteSettings->home_latest_title ?? 'Just Arrived' }}</span></h2>
        </div>
        <div class="row pb-3">
            @forelse ($latestProducts ?? [] as $product)
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    @include('web.components.product_card', ['product' => $product, 'isFirst' => $loop->iteration <= 4])
                </div>
            @empty
                <div class="col-md-12">
                    <h3 class="text-center mt-5">No Product Found</h3>
                </div>
            @endforelse
        </div>
    </div>
    <!-- Products End -->

    <!-- Blogs Start -->
    @if(isset($blogs) && $blogs->count() > 0)
    <div class="container pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Latest News & Blogs</span></h2>
        </div>
        <div class="row pb-3">
            <div class="col">
                <div class="owl-carousel custom-carousel blog-carousel">
                    @foreach ($blogs as $blog)
                        @include('web.components.blog_card', ['blog' => $blog])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Blogs End -->

    <!-- Reviews Start -->
    @if(isset($reviews) && $reviews->count() > 0)
    <div class="container pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">What Our Customers Say</span></h2>
        </div>
        <div class="row pb-3">
            <div class="col">
                <div class="owl-carousel custom-carousel review-carousel">
                    @foreach ($reviews as $review)
                        <div class="card border-0 mb-4 theme-shadow hover-up transition-all h-100" style="border-radius: 12px; border-bottom: 4px solid var(--primary) !important;">
                            <div class="card-body bg-white p-4 position-relative">
                                <i class="fas fa-quote-left position-absolute text-primary" style="opacity: 0.1; font-size: 40px; top: 20px; right: 20px;"></i>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-primary" style="font-size: 12px;">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for ($i = 0; $i < (5 - $review->rating); $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-4 font-italic text-muted" style="font-size: 14px; line-height: 1.6;">"{{ Str::limit(strip_tags($review->review_text), 120) }}"</p>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white d-flex justify-content-center align-items-center rounded-circle font-weight-bold" style="width: 45px; height: 45px; font-size: 18px;">
                                        {{ strtoupper(substr($review->user?->name ?? 'Guest', 0, 1)) }}
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $review->user?->name ?? 'Guest' }}</h6>
                                        <small class="text-muted"><i class="fas fa-check-circle text-success mr-1"></i>Verified Buyer</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Reviews End -->

    <!-- Vendor Start -->
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    @foreach ($brands ?? [] as $brand)
                        <div class="vendor-item theme-shadow transition-all hover-up p-4" style="border-radius: 12px; background: white; border: none !important;">
                            <img src="{{ Storage::url($brand?->media?->src) }}"
                                style="aspect-ratio: 4/2; object-fit: contain;" alt="{{ $brand?->name }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->
@endsection
@push('styles')
    <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($(".category-carousel").length > 0) {
                $(".category-carousel").owlCarousel({
                    loop: true,
                    dots: false,
                    margin: 25,
                    autoplay: true,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        576: {
                            items: 2
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 4
                        },
                        1200: {
                            items: 5
                        },
                        1400: {
                            items: 5
                        }
                    }
                });
            }

            if ($(".custom-carousel").length > 0) {
                $(".custom-carousel").owlCarousel({
                    loop: true,
                    dots: true,
                    nav: false,
                    margin: 25,
                    autoplay: true,
                    autoplayHoverPause: true,
                    responsive: {
                        0: { items: 1 },
                        768: { items: 2 },
                        992: { items: 3 }
                    }
                });
            }
        });
    </script>
@endpush

