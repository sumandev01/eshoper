@if(isset($products) && count($products) > 0)
    <!-- Product Slider Start -->
    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">{{ $title ?? 'Products' }}</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach ($products as $productItem)
                        @include('web.components.product_card', ['product' => $productItem])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Product Slider End -->
    
    @once
        @push('styles')
            <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
            <style>
                .related-carousel .owl-nav {
                    display: none !important;
                }
            </style>
        @endpush
        @push('vendor-scripts')
            <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
        @endpush
    @endonce
@endif
