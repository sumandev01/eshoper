<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. this week sales order price
        $thisWeekSalesSum = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->sum('grand_total');

        // 2. this week sales order count
        $thisWeekSalesCount = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        // 3. last week sales order price
        $lastWeekSalesSum = Order::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->sum('grand_total');

        // 4. last week sales order count
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

        return view('dashboard.index', compact(
            'thisWeekSalesSum',
            'thisWeekSalesCount',
            'lastWeekSalesSum',
            'lastWeekSalesCount',
            'salesPercentage',
            'ordersPercentage'
        ));
    }
}
