<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminDashboardRepository;
use App\Repositories\ProductRepository;

class DashboardController extends Controller
{
    protected $orderRepo;
    protected $productRepo;

    public function __construct(AdminDashboardRepository $orderRepo, ProductRepository $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        // sales stats & orders stats
        $salesStats = $this->orderRepo->getWeeklySalesStats();
        $ordersStats = $this->orderRepo->getWeeklyOrdersStats();

        $thisWeekSalesSum    = $salesStats['thisWeekSalesSum'];
        $thisWeekSalesCount  = $salesStats['thisWeekSalesCount'];
        $lastWeekSalesSum    = $salesStats['lastWeekSalesSum'];
        $lastWeekSalesCount  = $salesStats['lastWeekSalesCount'];
        $salesPercentage     = $salesStats['salesPercentage'];

        $ordersPercentage    = $ordersStats['thisWeekOrdersCountPercentage'];
        $thisWeekOrdersCount = $ordersStats['thisWeekOrdersCount'];
        $lastWeekOrdersCount = $ordersStats['lastWeekOrdersCount'];

        // orders pending count
        $pendingOrders = $this->orderRepo->getPendingOrdersCount();

        // yearly chart
        $chartData = $this->orderRepo->getYearlyChartData(now()->year);

        // out of stock products
        $outOfStockProducts = $this->productRepo->getOutOfStockProducts();
        // recent orders
        $orders             = $this->orderRepo->getRecentOrders(5);

        return view('dashboard.index', array_merge(
            compact(
                'thisWeekSalesSum',
                'thisWeekSalesCount',
                'lastWeekSalesSum',
                'lastWeekSalesCount',
                'salesPercentage',
                'ordersPercentage',
                'thisWeekOrdersCount',
                'pendingOrders',
                'outOfStockProducts',
                'orders'
            ),
            $chartData
        ));
    }
}