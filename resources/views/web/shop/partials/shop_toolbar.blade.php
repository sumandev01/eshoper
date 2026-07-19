<div class="col-12 pb-1">
    <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded" style="background-color: var(--primary-soft);">
        <form onsubmit="return false;" class="flex-grow-1 me-4 position-relative">
            <input type="text" id="search-product" class="form-control rounded-pill ps-4 pe-5 border-0" placeholder="Search by Product Name..."
                value="{{ request('search') }}" style="height: 45px; transition: all 0.3s; background-color: #f9fafb; box-shadow: 0 4px 20px rgba(0,0,0,0.03);" onfocus="this.style.boxShadow='0 0 0 0.25rem rgba(var(--primary-rgb), 0.25)'">
            <i class="fas fa-search position-absolute text-muted" style="top: 50%; right: 20px; transform: translateY(-50%); pointer-events: none;"></i>
        </form>
        <div class="dropdown">
            <button class="btn btn-white bg-white rounded-pill shadow-sm px-4 dropdown-toggle border-0 d-flex align-items-center" type="button" id="triggerId" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" style="height: 45px; font-weight: 500;">
                Sort by
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded" aria-labelledby="triggerId">
                <a class="dropdown-item sort-item py-2" href="#" data-sort="latest">Latest</a>
                <a class="dropdown-item sort-item py-2" href="#" data-sort="price_low">Price: Low to High</a>
                <a class="dropdown-item sort-item py-2" href="#" data-sort="price_high">Price: High to Low</a>
            </div>
        </div>
    </div>
</div>
