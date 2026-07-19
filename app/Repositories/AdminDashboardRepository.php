<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardRepository
{
    public function getSalesStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfWeek();
        $endDate = $endDate ?? now()->endOfWeek();
        $cacheKey = "sales_stats_" . md5($startDate . $endDate);

        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $thisPeriodSalesSum = Order::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
            $thisPeriodSalesCount = Order::whereBetween('created_at', [$startDate, $endDate])->count();

            // Calculate previous period of exact same length
            $diffInDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate));
            $lastPeriodStart = \Carbon\Carbon::parse($startDate)->subDays($diffInDays + 1);
            $lastPeriodEnd = \Carbon\Carbon::parse($startDate)->subSeconds(1);

            $lastPeriodSalesSum = Order::whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])->sum('grand_total');
            $lastPeriodSalesCount = Order::whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])->count();

            $salesPercentage = $lastPeriodSalesSum > 0 ? (($thisPeriodSalesSum - $lastPeriodSalesSum) / $lastPeriodSalesSum * 100) : ($thisPeriodSalesSum > 0 ? 100 : 0);
            $ordersPercentage = $lastPeriodSalesCount > 0 ? (($thisPeriodSalesCount - $lastPeriodSalesCount) / $lastPeriodSalesCount * 100) : ($thisPeriodSalesCount > 0 ? 100 : 0);

            return [
                'thisWeekSalesSum'   => $thisPeriodSalesSum,
                'thisWeekSalesCount' => $thisPeriodSalesCount,
                'lastWeekSalesSum'   => $lastPeriodSalesSum,
                'lastWeekSalesCount' => $lastPeriodSalesCount,
                'salesPercentage'    => round($salesPercentage, 2),
                'ordersPercentage'   => round($ordersPercentage, 2)
            ];
        });
    }

    public function getOrdersStats($startDate = null, $endDate = null): array
    {
        $startDate = $startDate ?? now()->startOfWeek();
        $endDate = $endDate ?? now()->endOfWeek();
        $cacheKey = "orders_stats_" . md5($startDate . $endDate);

        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $thisPeriodOrdersCount = Order::whereBetween('created_at', [$startDate, $endDate])->count();

            $diffInDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate));
            $lastPeriodStart = \Carbon\Carbon::parse($startDate)->subDays($diffInDays + 1);
            $lastPeriodEnd = \Carbon\Carbon::parse($startDate)->subSeconds(1);

            $lastPeriodOrdersCount = Order::whereBetween('created_at', [$lastPeriodStart, $lastPeriodEnd])->count();

            $percentage = $lastPeriodOrdersCount > 0 ? (($thisPeriodOrdersCount - $lastPeriodOrdersCount) / $lastPeriodOrdersCount * 100) : ($thisPeriodOrdersCount > 0 ? 100 : 0);

            return [
                'thisWeekOrdersCount'           => $thisPeriodOrdersCount,
                'lastWeekOrdersCount'           => $lastPeriodOrdersCount,
                'thisWeekOrdersCountPercentage' => round($percentage, 2)
            ];
        });
    }

    public function getPendingOrdersCount($startDate = null, $endDate = null): int
    {
        $cacheKey = "pending_orders_" . md5($startDate . $endDate);
        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $query = Order::where('order_status', 'pending');
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query->count();
        });
    }

    public function getRecentOrders(int $limit = 5, $startDate = null, $endDate = null)
    {
        $cacheKey = "recent_orders_" . $limit . "_" . md5($startDate . $endDate);
        return Cache::remember($cacheKey, 300, function () use ($limit, $startDate, $endDate) {
            $query = Order::latest('id');
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            return $query->take($limit)->get();
        });
    }

    public function getYearlyChartData(int $year): array
    {
        $cacheKey = "yearly_chart_" . $year;
        return Cache::remember($cacheKey, 300, function () use ($year) {
            $monthlyData = Order::selectRaw("
                MONTH(created_at) as month,
                COUNT(id) as total_orders,
                SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as total_deliveries,
                SUM(CASE WHEN order_status = 'returned' THEN 1 ELSE 0 END) as total_returns,
                SUM(grand_total) as total_amount
            ")
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

            $ordersData = [];
            $deliveriesData = [];
            $amountData = [];
            $returnsData = [];

            for ($month = 1; $month <= 12; $month++) {
                $data = $monthlyData->get($month);

                $ordersData[]     = $data ? $data->total_orders : 0;
                $deliveriesData[] = $data ? $data->total_deliveries : 0;
                $amountData[]     = $data ? (float) $data->total_amount : 0.0;
                $returnsData[]    = $data ? $data->total_returns : 0;
            }

            return compact('ordersData', 'deliveriesData', 'amountData', 'returnsData');
        });
    }

    public function getOrderStatusStats($startDate = null, $endDate = null): array
    {
        $cacheKey = "order_status_stats_" . md5($startDate . $endDate);
        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $query = Order::select('order_status', DB::raw('count(*) as total'));
            
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $statuses = $query->groupBy('order_status')
                ->pluck('total', 'order_status')
                ->toArray();

            $statusLabels = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'canceled'];
            $data = [];
            foreach ($statusLabels as $label) {
                $data[$label] = $statuses[$label] ?? 0;
            }

            return $data;
        });
    }

    public function getTopSellingProducts(int $limit = 5, $startDate = null, $endDate = null)
    {
        $cacheKey = "top_selling_" . $limit . "_" . md5($startDate . $endDate);
        return Cache::remember($cacheKey, 300, function () use ($limit, $startDate, $endDate) {
            $query = \App\Models\Product::select('products.*', DB::raw('SUM(order_products.quantity) as total_sold'))
                ->join('order_products', 'products.id', '=', 'order_products.product_id')
                ->join('orders', 'order_products.order_id', '=', 'orders.id')
                ->whereNotIn('orders.order_status', ['canceled', 'returned']);
                
            if ($startDate && $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            }

            return $query->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getOrderCountsByStatus($startDate = null, $endDate = null): array
    {
        $cacheKey = "order_counts_by_status_" . md5($startDate . $endDate);
        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            $query = Order::select('order_status', DB::raw('count(*) as total'));
            
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $statuses = $query->groupBy('order_status')
                ->pluck('total', 'order_status')
                ->toArray();
                
            $statuses['Total Orders'] = array_sum($statuses);
            
            return $statuses;
        });
    }

    public function getUserCountsByRole(): array
    {
        $cacheKey = "user_counts_by_role";
        return Cache::remember($cacheKey, 300, function () {
            $roles = \Spatie\Permission\Models\Role::withCount('users')->get();
            
            $counts = [];
            foreach($roles as $role) {
                $counts[ucfirst($role->name)] = $role->users_count;
            }
            $counts['Total Users'] = \App\Models\User::count();
            
            return $counts;
        });
    }

    public function getLowStockProducts(int $limit = 10, int $threshold = 10)
    {
        $cacheKey = "low_stock_products_{$limit}_{$threshold}";
        return Cache::remember($cacheKey, 300, function () use ($limit, $threshold) {
            return \App\Models\Product::withListingDefaults()
                ->where(function($query) use ($threshold) {
                    $query->where('stock', '<', $threshold)
                          ->orWhereHas('inventories', function($q) use ($threshold) {
                              $q->where('stock', '<', $threshold);
                          });
                })
                ->take($limit)
                ->get();
        });
    }

    public function getDiscountedProducts(int $limit = 10)
    {
        $cacheKey = "discounted_products_{$limit}";
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return \App\Models\Product::withListingDefaults()
                ->where(function($query) {
                    $query->where('discount', '>', 0)
                          ->orWhereHas('inventories', function($q) {
                              $q->where(function($subQ) {
                                  $subQ->where('use_main_discount', 0)->where('discount', '>', 0);
                              })->orWhere(function($subQ) {
                                  $subQ->where('use_main_discount', 1);
                              });
                          });
                })
                ->take($limit)
                ->get();
        });
    }

    public function getActiveCoupons(int $limit = 10)
    {
        $cacheKey = "active_coupons_{$limit}";
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            if (class_exists(\App\Models\Coupon::class)) {
                return \App\Models\Coupon::where('expire_date', '>=', now())
                    ->where('status', 1)
                    ->take($limit)
                    ->get();
            }
            return collect([]);
        });
    }

    public function getTopCustomers(int $limit = 5)
    {
        $cacheKey = "top_customers_{$limit}";
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return \App\Models\User::select('users.*', DB::raw('SUM(orders.grand_total) as total_spent'), DB::raw('COUNT(orders.id) as total_orders'))
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->whereNotIn('orders.order_status', ['canceled', 'returned'])
                ->groupBy('users.id')
                ->orderByDesc('total_spent')
                ->take($limit)
                ->get();
        });
    }

    public function getSalesByCategory(int $limit = 5)
    {
        $cacheKey = "sales_by_category_{$limit}";
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return DB::table('categories')
                ->select('categories.name', DB::raw('SUM(order_products.price * order_products.quantity) as total_sales'))
                ->join('product_details', 'categories.id', '=', 'product_details.category_id')
                ->join('order_products', 'product_details.product_id', '=', 'order_products.product_id')
                ->join('orders', 'order_products.order_id', '=', 'orders.id')
                ->whereNotIn('orders.order_status', ['canceled', 'returned'])
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total_sales')
                ->take($limit)
                ->get();
        });
    }

    public function getRecentReviews(int $limit = 5)
    {
        $cacheKey = "recent_reviews_{$limit}";
        return Cache::remember($cacheKey, 300, function () use ($limit) {
            if (class_exists(\App\Models\ProductReview::class)) {
                return \App\Models\ProductReview::with('user', 'product')->latest()->take($limit)->get();
            } elseif (class_exists(\App\Models\Comment::class)) { // Fallback if it's named Comment
                return \App\Models\Comment::with('user', 'product')->latest()->take($limit)->get();
            }
            return collect([]);
        });
    }
}
