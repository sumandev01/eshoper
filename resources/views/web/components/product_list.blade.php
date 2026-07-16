<div class="row pb-3">
    @forelse ($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 pb-1">
            @include('web.components.product_card', ['product' => $product, 'isFirst' => $loop->iteration <= 4])
        </div>
    @empty
        <div class="col-md-12">
            <h3 class="text-center mt-5">No Product Found</h3>
        </div>
    @endforelse
    <div class="col-12 pb-1">
        <div class="d-flex align-items-center mb-4" style="position: relative;">
            <div class="text-start">
                <p class="mb-0">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                    {{ $products->total() }} results</p>
            </div>
            <div class="mx-auto" id="pagination-links" aria-label="Page navigation">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

