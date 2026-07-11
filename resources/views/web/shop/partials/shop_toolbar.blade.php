<div class="col-12 pb-1">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <form onsubmit="return false;">
            <input type="text" id="search-product" class="form-control border-5" placeholder="Search by Product Name"
                value="{{ request('search') }}">
        </form>
        <div class="dropdown ms-4">
            <button class="btn border dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Sort by
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                <a class="dropdown-item sort-item" href="#" data-sort="latest">Latest</a>
                <a class="dropdown-item sort-item" href="#" data-sort="price_low">Price: Low to
                    High</a>
                <a class="dropdown-item sort-item" href="#" data-sort="price_high">Price: High to
                    Low</a>
            </div>
        </div>
    </div>
</div>
