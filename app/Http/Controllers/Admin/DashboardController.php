<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminDashboardRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $orderRepo;
    protected $productRepo;

    public function __construct(AdminDashboardRepository $orderRepo, ProductRepository $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function index(Request $request)
    {
        $dateFilter = $request->get('date_filter', 'this_week');
        $startDate = null;
        $endDate = null;
        $filterLabel = 'This Week';

        $now = now();
        switch ($dateFilter) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $filterLabel = 'Today';
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $filterLabel = 'Yesterday';
                break;
            case 'last_7_days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $filterLabel = 'Last 7 Days';
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $filterLabel = 'This Month';
                break;
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                $filterLabel = 'Last Month';
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $filterLabel = 'This Year';
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $filterLabel = $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
                }
                break;
            default: // this_week
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $filterLabel = 'This Week';
                break;
        }

        // sales stats & orders stats
        $salesStats = $this->orderRepo->getSalesStats($startDate, $endDate);
        $ordersStats = $this->orderRepo->getOrdersStats($startDate, $endDate);

        $thisWeekSalesSum    = $salesStats['thisWeekSalesSum'];
        $thisWeekSalesCount  = $salesStats['thisWeekSalesCount'];
        $lastWeekSalesSum    = $salesStats['lastWeekSalesSum'];
        $lastWeekSalesCount  = $salesStats['lastWeekSalesCount'];
        $salesPercentage     = $salesStats['salesPercentage'];

        $ordersPercentage    = $ordersStats['thisWeekOrdersCountPercentage'];
        $thisWeekOrdersCount = $ordersStats['thisWeekOrdersCount'];
        $lastWeekOrdersCount = $ordersStats['lastWeekOrdersCount'];

        // orders pending count
        $pendingOrders = $this->orderRepo->getPendingOrdersCount($startDate, $endDate);

        // yearly chart
        $chartData = $this->orderRepo->getYearlyChartData(now()->year);

        // out of stock products (not filtered by date)
        $outOfStockProducts = $this->productRepo->getOutOfStockProducts();
        
        // top selling products
        $topSellingProducts = $this->orderRepo->getTopSellingProducts(5, $startDate, $endDate);
        
        // order status stats for pie chart
        $orderStatusStats = $this->orderRepo->getOrderStatusStats($startDate, $endDate);
        
        // recent orders
        $orders             = $this->orderRepo->getRecentOrders(5, $startDate, $endDate);

        // new dynamic stats
        $orderCountsByStatus = $this->orderRepo->getOrderCountsByStatus($startDate, $endDate);
        $userCountsByRole = $this->orderRepo->getUserCountsByRole();

        // 7 new advanced features
        $lowStockProducts = $this->orderRepo->getLowStockProducts(10, 10);
        $discountedProducts = $this->orderRepo->getDiscountedProducts(10);
        $activeCoupons = $this->orderRepo->getActiveCoupons(10);
        $topCustomers = $this->orderRepo->getTopCustomers(5);
        $salesByCategory = $this->orderRepo->getSalesByCategory(5);
        $recentReviews = $this->orderRepo->getRecentReviews(5);

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
                'topSellingProducts',
                'orderStatusStats',
                'orders',
                'orderCountsByStatus',
                'userCountsByRole',
                'lowStockProducts',
                'discountedProducts',
                'activeCoupons',
                'topCustomers',
                'salesByCategory',
                'recentReviews',
                'dateFilter',
                'filterLabel',
                'startDate',
                'endDate'
            ),
            $chartData
        ));
    }
}