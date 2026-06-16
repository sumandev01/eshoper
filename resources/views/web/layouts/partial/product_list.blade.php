<div class="row pb-3">
    @forelse ($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
            @include('web.layouts.partial.product_card', ['product' => $product])
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