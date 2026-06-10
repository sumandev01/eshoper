@extends('dashboard.layouts.app')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dashboard/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Weekly Sales <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $siteSettings?->currency_symbol }} {{ formatBDT($thisWeekSalesSum ?? 0) }}</h2>
                    <h6 class="card-text">
                        @if ($salesPercentage > 0)
                            Increased by last week {{ $salesPercentage }}%
                        @elseif($salesPercentage < 0)
                            Decreased by last week {{ abs($salesPercentage) }}%
                        @else
                            No sales from last week 0%
                        @endif
                    </h6>

                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dashboard/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Weekly Orders
                        <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $thisWeekOrdersCount ?? 0 }}</h2>
                    <h6 class="card-text">
                        @if ($ordersPercentage > 0)
                            Increased by last week {{ $ordersPercentage ?? 0 }}%
                        @elseif($ordersPercentage < 0)
                            Decreased by last week {{ abs($ordersPercentage) }}%
                        @else
                            No orders from last week 0%
                        @endif
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('dashboard/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pending Orders <i class="mdi mdi-diamond mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ formatBDT($pendingOrders ?? 0) }}</h2>
                </div>
            </div>
        </div>
    </div>
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
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner text-center py-2">
                            @php $isFirst = true; @endphp

                            @forelse($outOfStockProducts as $product)
                                @if ($product->inventories->count() > 0)
                                    @foreach ($product->inventories as $inventory)
                                        <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                            @php $isFirst = false; @endphp
                                            @if ($inventory->media_id !== null)
                                                <img src="{{ Storage::url($inventory->media->src) }}" class="img-fluid"
                                                    style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ $product->media ? Storage::url($product->media->src) : asset('default-image.png') }}"
                                                    class="img-fluid"
                                                    style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                                    alt="{{ $product->name }}">
                                            @endif

                                            <div class="text-center mt-4">
                                                <h5 class="text-secondary fw-normal mb-2" style="font-size: 1rem;">
                                                    <a href="{{ route('product.view', $product->id) }}"
                                                        class="text-decoration-none">{{ Str::limit($product->name, 30) }}</a>
                                                </h5>
                                                <span class="badge bg-warning text-dark mb-2">Variant Out of
                                                    Stock</span><br>

                                                Color: <strong>{{ $inventory->color->color_code ?? 'N/A' }}</strong> <br>
                                                Size: <strong>{{ $inventory->size->name ?? 'N/A' }}</strong>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if ($product->stock == 0 && $product->inventories->count() == 0)
                                    <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                                        @php $isFirst = false; @endphp

                                        <img src="{{ $product->media ? Storage::url($product->media->src) : asset('default-image.png') }}"
                                            class="img-fluid"
                                            style="max-height: 250px; object-fit: contain; aspect-ratio: 4 / 4;"
                                            alt="{{ $product->name }}">

                                        <div class="text-center mt-4">
                                            <h5 class="text-secondary fw-normal mb-2" style="font-size: 1rem;">
                                                <a href="{{ route('product.view', $product->id) }}"
                                                    class="text-decoration-none">{{ Str::limit($product->name, 30) }}</a>
                                            </h5>

                                            <span class="badge bg-danger mb-2">Main Product Out of Stock</span>
                                        </div>
                                    </div>
                                @endif

                            @empty
                                <div>
                                    <p>No out of stock product</p>
                                </div>
                            @endforelse
                        </div>

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

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recent Order</h4>
                    <a href="{{ route('order.index') }}" class="btn btn-primary btn-sm">View all</a>
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
                                            {{ $siteSettings->currency_symbol }}{{ formatBDT($order->grand_total) }}</td>
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
                                        <td class="text-end"><a href="{{ route('order.view', $order->id) }}"
                                                class="btn btn-primary btn-sm">View</a></td>
                                    </tr>
                                @endforeach
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
            let siteCurrency = "{{ $siteSettings?->currency_symbol ?? '৳' }}";
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
        });
    </script>
@endpush
