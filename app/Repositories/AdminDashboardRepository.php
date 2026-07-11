<?php

namespace App\Repositories;

use App\Models\Order;

class AdminDashboardRepository
{
    public function getWeeklySalesStats()
    {
        $thisWeekSalesSum = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->sum('grand_total');

        // this week sales order count
        $thisWeekSalesCount = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        // last week sales order price
        $lastWeekSalesSum = Order::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->sum('grand_total');

        // last week sales order count
        $lastWeekSalesCount = Order::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $salesPercentage = $lastWeekSalesSum > 0 ? ($thisWeekSalesSum / $lastWeekSalesSum * 100) : ($thisWeekSalesSum > 0 ? 100 : 0);
        $ordersPercentage = $lastWeekSalesCount > 0 ? ($thisWeekSalesCount / $lastWeekSalesCount * 100) : ($thisWeekSalesCount > 0 ? 100 : 0);

        return [
            'thisWeekSalesSum'   => $thisWeekSalesSum,
            'thisWeekSalesCount' => $thisWeekSalesCount,
            'lastWeekSalesSum'   => $lastWeekSalesSum,
            'lastWeekSalesCount' => $lastWeekSalesCount,
            'salesPercentage'    => round($salesPercentage, 2),
            'ordersPercentage'   => round($ordersPercentage, 2)
        ];
    }


    public function getWeeklyOrdersStats(): array
    {
        $now = now();
        $thisWeekRange = [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
        $lastWeekRange = [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()];

        $thisWeekOrdersCount = Order::whereBetween('created_at', $thisWeekRange)->count();
        $lastWeekOrdersCount = Order::whereBetween('created_at', $lastWeekRange)->count();

        // last week sales order count
        $thisWeekOrdersCountPercentage = $lastWeekOrdersCount > 0 ? ($thisWeekOrdersCount / $lastWeekOrdersCount * 100) : ($thisWeekOrdersCount > 0 ? 100 : 0);

        return [
            'thisWeekOrdersCount'           => $thisWeekOrdersCount,
            'lastWeekOrdersCount'           => $lastWeekOrdersCount,
            'thisWeekOrdersCountPercentage' => round($thisWeekOrdersCountPercentage, 2)
        ];
    }

    public function getPendingOrdersCount(): int
    {
        return Order::where('order_status', 'pending')->count();
    }

    public function getRecentOrders(int $limit = 5)
    {
        return Order::latest('id')->take($limit)->get();
    }

    /**
     * Get yearly chart data
     */
    public function getYearlyChartData(int $year): array
    {
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
    }

    /**
     * Get order status statistics for a Pie Chart
     */
    public function getOrderStatusStats(): array
    {
        $statuses = Order::select('order_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status')
            ->toArray();

        // Ensure default keys exist
        $statusLabels = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'canceled'];
        $data = [];
        foreach ($statusLabels as $label) {
            $data[$label] = $statuses[$label] ?? 0;
        }

        return $data;
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $limit = 5)
    {
        return \App\Models\Product::select('products.*', \Illuminate\Support\Facades\DB::raw('SUM(order_products.quantity) as total_sold'))
            ->join('order_products', 'products.id', '=', 'order_products.product_id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->whereNotIn('orders.order_status', ['canceled', 'returned'])
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take($limit)
            ->get();
    }
}
