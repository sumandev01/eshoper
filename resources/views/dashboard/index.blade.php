@extends('dashboard.layouts.app')
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
        </h3>
        
        <div class="d-flex align-items-center">
            <!-- Quick Action Buttons -->
            <a href="{{ route('admin.product.add') }}" class="btn btn-sm btn-gradient-primary me-2 text-white">
                <i class="mdi mdi-plus-circle-outline"></i> Add Product
            </a>
            <a href="{{ route('admin.order.index', ['status' => 'pending']) }}" class="btn btn-sm btn-gradient-warning me-2 text-dark">
                <i class="mdi mdi-clock-outline"></i> Pending Orders
            </a>
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-sm btn-gradient-info me-3 text-white">
                <i class="mdi mdi-ticket-percent"></i> Manage Coupons
            </a>

            <!-- Date Filter Form -->
            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center" id="dateFilterForm">
                <select name="date_filter" class="form-select form-select-sm border-0 shadow-sm me-2" style="min-width: 150px;" onchange="handleDateFilterChange(this.value)">
                    <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $dateFilter == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="last_7_days" {{ $dateFilter == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $dateFilter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <div id="customDateRange" class="d-flex align-items-center {{ $dateFilter == 'custom' ? '' : 'd-none' }}">
                    <input type="date" name="start_date" class="form-control form-control-sm me-2" value="{{ request('start_date', $startDate ? $startDate->format('Y-m-d') : '') }}">
                    <span class="me-2">-</span>
                    <input type="date" name="end_date" class="form-control form-control-sm me-2" value="{{ request('end_date', $endDate ? $endDate->format('Y-m-d') : '') }}">
                    <button type="submit" class="btn btn-sm btn-gradient-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <x-dashboard.card 
            color="danger" 
            title="Weekly Sales" 
            icon="mdi-chart-line" 
            value="{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($thisWeekSalesSum ?? 0) }}" 
            :percentage="$salesPercentage" 
            increasedText="Increased by last week" 
            decreasedText="Decreased by last week" 
            neutralText="No sales from last week" 
        />
        
        <x-dashboard.card 
            color="info" 
            title="Weekly Orders" 
            icon="mdi-bookmark-outline" 
            value="{{ $thisWeekOrdersCount ?? 0 }}" 
            :percentage="$ordersPercentage" 
            increasedText="Increased by last week" 
            decreasedText="Decreased by last week" 
            neutralText="No orders from last week" 
        />

        <x-dashboard.card 
            color="success" 
            title="Total Orders" 
            icon="mdi-diamond" 
            value="{{ formatBDT($orderCountsByStatus['Total Orders'] ?? 0) }}" 
            cardId="orderStatsCard"
            titleId="orderStatsTitle"
            valueId="orderStatsValue"
        >
            <x-slot name="dropdown">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 2px 5px; font-size: 12px; border-color: rgba(255,255,255,0.5);">
                        <i class="mdi mdi-filter"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="orderStatusDropdown">
                        <li><a class="dropdown-item text-dark" href="#" onclick="updateOrderCard('Total Orders', '{{ $orderCountsByStatus['Total Orders'] ?? 0 }}', 'success'); return false;">Total Orders</a></li>
                        @foreach(\App\Enums\OrderStatusEnums::cases() as $status)
                            <li>
                                <a class="dropdown-item text-dark" href="#" onclick="updateOrderCard('{{ $status->label() }}', '{{ $orderCountsByStatus[$status->value] ?? 0 }}', '{{ $status->color() }}'); return false;">
                                    {{ $status->label() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-slot>
        </x-dashboard.card>
        
        <x-dashboard.card 
            color="primary" 
            title="Total Users" 
            icon="mdi-account-multiple" 
            value="{{ $userCountsByRole['Total Users'] ?? 0 }}" 
            cardId="userStatsCard"
            titleId="userStatsTitle"
            valueId="userStatsValue"
        >
            <x-slot name="dropdown">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="userRoleDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 2px 5px; font-size: 12px; border-color: rgba(255,255,255,0.5);">
                        <i class="mdi mdi-filter"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userRoleDropdown">
                        @foreach($userCountsByRole as $roleName => $count)
                            <li>
                                <a class="dropdown-item text-dark" href="#" onclick="updateUserCard('{{ $roleName == 'Total Users' ? 'Total Users' : $roleName . 's' }}', '{{ $count }}', '{{ $roleName == 'Admin' ? 'danger' : ($roleName == 'Total Users' ? 'primary' : 'info') }}'); return false;">
                                    {{ $roleName }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-slot>
        </x-dashboard.card>
    </div>

    <style>
        /* Force dropdown items to remain dark text on hover to avoid invisibility inside text-white cards */
        .dropdown-menu .dropdown-item:hover, .dropdown-menu .dropdown-item:focus {
            color: #000 !important;
            background-color: #f8f9fa !important;
        }
    </style>

    <script>
        function handleDateFilterChange(value) {
            if (value === 'custom') {
                document.getElementById('customDateRange').classList.remove('d-none');
            } else {
                document.getElementById('customDateRange').classList.add('d-none');
                document.getElementById('dateFilterForm').submit();
            }
        }

        function updateOrderCard(title, value, color) {
            document.getElementById('orderStatsTitle').innerText = title + ' Orders';
            document.getElementById('orderStatsValue').innerText = value;
            
            let card = document.getElementById('orderStatsCard');
            // Remove existing bg-gradient classes
            card.className = card.className.replace(/bg-gradient-\w+/g, '');
            // Add new color
            card.classList.add('bg-gradient-' + color);
        }

        function updateUserCard(title, value, color) {
            document.getElementById('userStatsTitle').innerText = title;
            document.getElementById('userStatsValue').innerText = value;
            
            let card = document.getElementById('userStatsCard');
            // Remove existing bg-gradient classes
            card.className = card.className.replace(/bg-gradient-\w+/g, '');
            // Add new color
            card.classList.add('bg-gradient-' + color);
        }
    </script>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="title mb-0">Sales Overview</h4>
                </div>
                <div class="card-body px-3">
                    <div id="chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm bg-white">
                <div class="card-header py-3">
                    <h4 class="title mb-0">Out of Stock</h4>
                </div>
                <div class="card-body position-relative p-4" style="height: 450px;">

                    @if ($outOfStockProducts->isEmpty())
                        <!-- no data out of stock product -->
                        <div class="d-flex align-items-center justify-content-center h-100 w-100">
                            <h5 class="text-muted mb-0">No out of stock product</h5>
                        </div>
                    @else
                        <!-- out of stock product -->
                        <div id="productCarousel" class="carousel slide h-100" data-bs-ride="carousel"
                            data-bs-interval="3000">
                            <div class="carousel-inner text-center py-2 h-100">
                                @php $isFirst = true; @endphp

                                @foreach ($outOfStockProducts as $product)
                                    @if ($product->inventories->count() > 0)
                                        @foreach ($product->inventories as $inventory)
                                            <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                                @php $isFirst = false; @endphp
                                                @if ($inventory->media_id !== null)
                                                    <img src="{{ Storage::url($inventory->media?->src) }}"
                                                        class="img-fluid"
                                                        style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                                        alt="{{ $product->name }}">
                                                @else
                                                    <img src="{{ $product->media ? Storage::url($product->media?->src) : asset('default-image.png') }}"
                                                        class="img-fluid"
                                                        style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                                        alt="{{ $product->name }}">
                                                @endif

                                                <div class="text-center mt-4">
                                                    <h5 class="text-secondary fw-normal mb-2" style="font-size: 1rem;">
                                                        <a href="{{ route('admin.product.view', $product->id) }}"
                                                            class="text-decoration-none">{{ Str::limit($product->name, 30) }}</a>
                                                    </h5>
                                                    <span class="badge bg-warning text-dark mb-2">Variant Out of
                                                        Stock</span><br>
                                                    <div>
                                                        Size: <strong>{{ $inventory->size?->name ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        Color: <span
                                                            style="background: {{ $inventory->color?->color_code ?? 'N/A' }}; width: 20px; height: 20px; display: inline-block; border-radius: 50%; margin-left: 5px;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if ($product->stock == 0 && $product->inventories->count() == 0)
                                        <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                            @php $isFirst = false; @endphp

                                            <img src="{{ $product->media ? Storage::url($product->media?->src) : asset('default-image.png') }}"
                                                class="img-fluid"
                                                style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                                alt="{{ $product->name }}">

                                            <div class="text-center mt-4">
                                                <h5 class="text-secondary fw-normal mb-2" style="font-size: 1rem;">
                                                    <a href="{{ route('admin.product.view', $product->id) }}"
                                                        class="text-decoration-none">{{ Str::limit($product->name, 30) }}</a>
                                                </h5>

                                                <span class="badge bg-danger mb-2">Main Product Out of Stock</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- prev and next button -->
                            <button class="carousel-control-prev bg-light text-dark rounded-circle opacity-100 shadow-sm"
                                type="button" data-bs-target="#productCarousel" data-bs-slide="prev"
                                style="width: 40px; height: 40px; top: 40%; transform: translateY(-50%); left: 10px;">
                                <i class="mdi mdi-chevron-left fs-5"></i>
                            </button>

                            <button class="carousel-control-next bg-light text-dark rounded-circle opacity-100 shadow-sm"
                                type="button" data-bs-target="#productCarousel" data-bs-slide="next"
                                style="width: 40px; height: 40px; top: 40%; transform: translateY(-50%); right: 10px;">
                                <i class="mdi mdi-chevron-right fs-5"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="title mb-0">Order Status</h4>
                </div>
                <div class="card-body px-3">
                    <div id="statusPieChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="title mb-0">Top Selling Products</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Total Sold</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingProducts ?? [] as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->media ? Storage::url($product->media?->src) : asset('default-image.png') }}"
                                                    class="rounded me-3"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                <a href="{{ route('admin.product.view', $product->id) }}"
                                                    class="text-dark text-decoration-none fw-bold"
                                                    style="margin-left: 10px;">{{ Str::limit($product->name, 40) }}</a>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle"><span
                                                class="badge bg-success rounded-pill">{{ $product->total_sold }}
                                                Items</span></td>
                                        <td class="text-end align-middle">
                                            {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($product->discount_price > 0 ? $product->discount_price : $product->price) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No sales data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recent Order</h4>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-primary btn-sm">View all</a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Payment Status</th>
                                    <th class="text-center">Shipping Status</th>
                                    <th class="text-end">Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders ?? [] as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td class="text-end">
                                            {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($order->grand_total) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-{{ $order?->payment_status?->color() }}">
                                                {{ ucfirst($order?->payment_status?->value) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-{{ $order?->order_status?->color() }}">
                                                {{ ucfirst($order?->order_status?->value) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $order->created_at->format('d-M-Y') }}</td>
                                        <td class="text-end"><a href="{{ route('admin.order.view', $order->id) }}"
                                                class="btn btn-primary btn-sm">View</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <!-- Top Customers -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="card-title mb-0">Top Customers</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers ?? [] as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </div>
                                                <span>{{ Str::limit($customer->name, 20) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="badge bg-info">{{ $customer->total_orders }}</span></td>
                                        <td class="text-end fw-bold">{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($customer->total_spent) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No top customers found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales by Category -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="card-title mb-0">Sales by Category</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesByCategory ?? [] as $category)
                                    <tr>
                                        <td>
                                            <span class="text-dark fw-bold"><i class="mdi mdi-tag-outline me-1 text-primary"></i> {{ Str::limit($category->name, 30) }}</span>
                                        </td>
                                        <td class="text-end"><span class="badge bg-success">{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($category->total_sales) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">No sales data by category found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Low Stock Products -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white border-left-danger" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-header py-3">
                    <h4 class="card-title text-danger mb-0"><i class="mdi mdi-alert-circle-outline"></i> Low Stock Alert</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Current Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts ?? [] as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.product.view', $product->id) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($product->name, 35) }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            @if($product->stock < 10)
                                                <span class="badge bg-danger">{{ $product->stock }} Left</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Variant Low</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-success">All products are well stocked!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discounted Products -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="card-title mb-0"><i class="mdi mdi-sale text-warning"></i> Discounted Products</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discountedProducts ?? [] as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.product.view', $product->id) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($product->name, 35) }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-warning text-dark">{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($product->discount) }} Off</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">No discounted products found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Active Coupons -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="card-title mb-0"><i class="mdi mdi-ticket-percent text-success"></i> Active Coupons</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-end">Expires In</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeCoupons ?? [] as $coupon)
                                    <tr>
                                        <td>
                                            <span class="badge bg-gradient-info">{{ $coupon->code }}</span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            @if($coupon->type == 'percent' || $coupon->type == 'percentage')
                                                {{ $coupon->amount }}%
                                            @else
                                                {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($coupon->amount) }}
                                            @endif
                                        </td>
                                        <td class="text-end text-danger" style="font-size: 0.85rem;">
                                            {{ \Carbon\Carbon::parse($coupon->expire_date)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No active coupons available</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="col-lg-6 grid-margin">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header py-3">
                    <h4 class="card-title mb-0"><i class="mdi mdi-star text-warning"></i> Recent Reviews</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer & Product</th>
                                    <th class="text-end">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReviews ?? [] as $review)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong style="font-size: 0.9rem;">{{ $review->user->name ?? 'Guest' }}</strong>
                                                <small class="text-muted">{{ Str::limit($review->product->name ?? 'Product', 30) }}</small>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="mdi mdi-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 14px;"></i>
                                            @endfor
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">No recent reviews found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('dashboard/assets/js/apexcharts.js') }}"></script>
    <script>
        $(document).ready(function() {
            let siteCurrency = "{{ $siteSettings->currency_symbol ?? (null ?? '৳') }}";
            var options = {
                series: [{
                        name: 'Orders',
                        data: {!! json_encode($ordersData) !!}
                    },
                    {
                        name: 'Deliveries',
                        data: {!! json_encode($deliveriesData) !!}
                    },
                    {
                        name: 'Amount',
                        data: {!! json_encode($amountData) !!}
                    },
                    {
                        name: 'Returns',
                        data: {!! json_encode($returnsData) !!}
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 350,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 5,
                        borderRadiusApplication: 'end',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                },
                yaxis: [{
                        seriesName: 'Orders',
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#008FFB'
                        },
                        labels: {
                            style: {
                                colors: '#008FFB'
                            }
                        },
                        title: {
                            text: "",
                            style: {
                                color: '#008FFB'
                            }
                        }
                    },
                    {
                        seriesName: 'Deliveries',
                        show: false,
                        opposite: false,
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#00E396'
                        },
                        labels: {
                            style: {
                                colors: '#00E396'
                            }
                        },
                        title: {
                            text: "Deliveries",
                            style: {
                                color: '#00E396'
                            }
                        }
                    },
                    {
                        seriesName: 'Amount',
                        opposite: true,
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#00E396'
                        },
                        labels: {
                            style: {
                                colors: '#00E396'
                            },
                            formatter: function(val) {
                                return `${siteCurrency}` + val.toLocaleString();
                            }
                        },
                        title: {
                            text: "Sales Amount",
                            style: {
                                color: '#00E396'
                            }
                        }
                    },
                    {
                        seriesName: 'Returns',
                        opposite: false,
                        show: false,
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true,
                            color: '#FEB019'
                        },
                        labels: {
                            style: {
                                colors: '#FEB019'
                            }
                        },
                        title: {
                            text: "Returned Items",
                            style: {
                                color: '#FEB019'
                            }
                        }
                    }
                ],
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val, opts) {
                            let seriesName = opts.w.config.series[opts.seriesIndex].name;
                            if (seriesName === 'Amount') {
                                return `${siteCurrency} ${val.toLocaleString()}`;
                            } else if (seriesName === 'Orders') {
                                return `${val} Orders`;
                            } else if (seriesName === 'Returns') {
                                return `${val} Items`;
                            }
                            return val;
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector('#chart'), options);
            chart.render();

            // Order Status Pie Chart
            var pieOptions = {
                series: [
                    {{ $orderStatusStats['pending'] ?? 0 }},
                    {{ $orderStatusStats['processing'] ?? 0 }},
                    {{ $orderStatusStats['confirmed'] ?? 0 }},
                    {{ $orderStatusStats['shipped'] ?? 0 }},
                    {{ $orderStatusStats['delivered'] ?? 0 }},
                    {{ $orderStatusStats['canceled'] ?? 0 }}
                ],
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: ['Pending', 'Processing', 'Confirmed', 'Shipped', 'Delivered', 'Canceled'],
                colors: ['#f39c12', '#3498db', '#9b59b6', '#34495e', '#2ecc71', '#e74c3c'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + "%"
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%'
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };
            var pieChart = new ApexCharts(document.querySelector("#statusPieChart"), pieOptions);
            pieChart.render();
        });
    </script>
@endpush
