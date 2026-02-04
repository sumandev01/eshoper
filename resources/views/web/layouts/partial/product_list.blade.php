<div class="row pb-3">
    @forelse ($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
            <div class="card product-item border-0 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ $product->thumbnail }}" style="aspect-ratio: 5/5" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3" title="{{ $product->name }}">
                        {{ Str::limit($product->name, 30, '...') }}</h6>
                    <div class="d-flex justify-content-center">
                        @if ($product->discount > 0)
                            <h6>৳{{ $product->discount }}</h6>
                            <h6 class="text-muted ml-2"><del>৳{{ $product->price }}</del></h6>
                        @else
                            <h6>৳{{ $product->price }}</h6>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="{{ route('product', $product->slug) }}" class="btn btn-sm text-dark p-0"><i
                            class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                    <a href="" class="btn btn-sm text-dark p-0"><i
                            class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <h3 class="text-center mt-5">No Product Found</h3>
        </div>
    @endforelse
    <div class="col-12 pb-1">
        <div class="d-flex align-items-center mb-4" style="position: relative;">
            <div class="text-left">
                <p class="mb-0">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                    {{ $products->total() }} results</p>
            </div>
            <div class="mx-auto" id="pagination-links" aria-label="Page navigation">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
