<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // this week sales order price
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

        // Calculate the percentage based on last week sales and this week sales
        $salesPercentage = 0;
        if ($lastWeekSalesSum > 0) {
            $salesPercentage = $thisWeekSalesSum / $lastWeekSalesSum * 100;
        } elseif ($thisWeekSalesSum > 0) {
            $salesPercentage = 100;
        } else {
            $salesPercentage = 0;
        }
        $salesPercentage = round($salesPercentage, 2);

        $ordersPercentage = 0;
        if ($lastWeekSalesCount > 0) {
            $ordersPercentage = $thisWeekSalesCount / $lastWeekSalesCount * 100;
        } elseif ($thisWeekSalesCount > 0) {
            $ordersPercentage = 100;
        } else {
            $ordersPercentage = 0;
        }
        $ordersPercentage = round($ordersPercentage, 2);

        // panding orders
        $pandingOrders = Order::where('order_status', 'pending')->count();

        // orders data
        $ordersData = [];
        $deliveriesData = [];
        $amountData = [];
        $returnsData = [];

        $currentYear = now()->year;

        for ($month = 1; $month <= 12; $month++) {
            // get start date and end date
            $startDate = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::create($currentYear, $month, 1)->endOfMonth();
            // get order count
            $ordersCount = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            // get delivery count
            $deliveriesCount = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('order_status', 'delivered')
                ->count();
            // get total amount
            $totalAmount = Order::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
            // get returns count
            $returnsCount = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('order_status', 'returned')
                ->count();
            // push data
            $ordersData[] = $ordersCount;
            $deliveriesData[] = $deliveriesCount;
            $amountData[] = (float) $totalAmount;
            $returnsData[] = $returnsCount;
        }

        $outOfStockProducts = Product::with(['inventories' => function ($query) {
            $query->where('stock', 0)->with(['color', 'size', 'media']);
        }])
            ->where(function ($query) {
                $query->where('stock', 0)
                    ->orWhereHas('inventories', function ($subQuery) {
                        $subQuery->where('stock', 0);
                    });
            })
            ->get();

        $orders = Order::latest('id')->take(5)->get();


        return view('dashboard.index', compact(
            'thisWeekSalesSum',
            'thisWeekSalesCount',
            'lastWeekSalesSum',
            'lastWeekSalesCount',
            'salesPercentage',
            'ordersPercentage',
            'pandingOrders',
            'ordersData',
            'deliveriesData',
            'amountData',
            'returnsData',
            'outOfStockProducts',
            'orders',
        ));
    }
}
