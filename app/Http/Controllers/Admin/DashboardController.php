<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CashSale;
use App\Models\CarFinancingRequest;
use App\Models\FinanceCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        // الإحصائيات العامة
        $stats = $this->getDashboardStats();
        
        // أحدث المبيعات النقدية
        $recent_sales = CashSale::with(['carBrand', 'carModel', 'user'])
            ->latest()
            ->take(5)
            ->get();
        
        // أحدث طلبات التمويل
        $recent_financing = CarFinancingRequest::with(['user', 'brand', 'model', 'bank'])
            ->latest()
            ->take(5)
            ->get();
        
        // التوقعات الشهرية
        $monthly_forecast = $this->getMonthlyForecast();
        
        return view('admin.dashboard.index', compact(
            'stats',
            'recent_sales',
            'recent_financing',
            'monthly_forecast'
        ));
    }
    
    /**
     * الحصول على إحصائيات لوحة التحكم
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // إجمالي المبيعات النقدية
        $total_sales = CashSale::count();
        $today_sales = CashSale::whereDate('created_at', $today)->count();
        $month_sales = CashSale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $last_month_sales = CashSale::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        
        // إجمالي طلبات التمويل
        $total_financing = CarFinancingRequest::count();
        $today_financing = CarFinancingRequest::whereDate('created_at', $today)->count();
        $month_financing = CarFinancingRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        
        // إجمالي الإيرادات
        $total_revenue = CashSale::sum('car_price') + CarFinancingRequest::sum('car_price');
        $month_revenue = CashSale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('car_price') 
                        + CarFinancingRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('car_price');
        
        // الطلبات قيد الانتظار
        $pending_requests = CarFinancingRequest::where('status', 'pending')->count()
                          + CashSale::where('payment_status', 'pending')->count();
        
        // معدل التحويل (تقريبي)
        $conversion_rate = $total_sales > 0 ? round(($total_sales / ($total_sales + $total_financing)) * 100, 1) : 0;
        
        // متوسط قيمة البيع
        $avg_sale_value = $total_sales > 0 ? round(CashSale::avg('car_price'), 2) : 0;
        
        // المستخدمين النشطين (في الشهر الأخير)
        $active_users = User::whereHas('financeCalculations', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        })->count();
        
        // النمو الشهري
        $monthly_growth = $last_month_sales > 0 ? 
            round((($month_sales - $last_month_sales) / $last_month_sales) * 100, 1) : 0;
        
        return [
            'total_sales' => $total_sales,
            'today_sales' => $today_sales,
            'month_sales' => $month_sales,
            'total_financing' => $total_financing,
            'today_financing' => $today_financing,
            'month_financing' => $month_financing,
            'total_revenue' => $total_revenue,
            'month_revenue' => $month_revenue,
            'pending_requests' => $pending_requests,
            'conversion_rate' => $conversion_rate,
            'avg_sale_value' => $avg_sale_value,
            'active_users' => $active_users,
            'monthly_growth' => $monthly_growth,
        ];
    }
    
    /**
     * الحصول على توقعات الشهر الحالي
     */
    private function getMonthlyForecast()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // إحصائيات الشهر الحالي حتى الآن
        $monthToDate = CashSale::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(car_price) as total'),
                DB::raw('AVG(car_price) as average'),
                DB::raw('DAY(created_at) as day')
            )
            ->groupBy('day')
            ->get();
        
        // التوقع للشهر كاملاً
        $daysInMonth = Carbon::now()->daysInMonth;
        $currentDay = Carbon::now()->day;
        
        if ($currentDay > 0) {
            $averageDaily = $monthToDate->sum('total') / $currentDay;
            $forecast = $averageDaily * $daysInMonth;
        } else {
            $forecast = 0;
        }
        
        return [
            'month_to_date' => $monthToDate->sum('total'),
            'forecast' => round($forecast, 2),
            'achievement_rate' => $currentDay > 0 ? round(($currentDay / $daysInMonth) * 100, 1) : 0,
        ];
    }
    
    /**
     * الحصول على إحصائيات مفصلة حسب الفترة
     */
    public function getStats(Request $request)
    {
        $period = $request->input('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
        }
        
        $stats = [
            'cash_sales' => [
                'count' => CashSale::whereBetween('created_at', [$startDate, $endDate])->count(),
                'amount' => CashSale::whereBetween('created_at', [$startDate, $endDate])->sum('car_price'),
                'avg_amount' => CashSale::whereBetween('created_at', [$startDate, $endDate])->avg('car_price'),
            ],
            'financing_requests' => [
                'count' => CarFinancingRequest::whereBetween('created_at', [$startDate, $endDate])->count(),
                'amount' => CarFinancingRequest::whereBetween('created_at', [$startDate, $endDate])->sum('car_price'),
                'approved' => CarFinancingRequest::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'sold')->count(),
            ],
            'top_brands' => $this->getTopBrands($startDate, $endDate),
            'payment_status' => $this->getPaymentStatusStats($startDate, $endDate),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * الحصول على أكثر الماركات مبيعاً
     */
    private function getTopBrands($startDate, $endDate)
    {
        return CashSale::whereBetween('created_at', [$startDate, $endDate])
            ->join('car_brands', 'cash_sales.car_brand_id', '=', 'car_brands.id')
            ->select('car_brands.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(car_price) as total'))
            ->groupBy('car_brands.id', 'car_brands.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
    }
    
    /**
     * إحصائيات حالة الدفع
     */
    private function getPaymentStatusStats($startDate, $endDate)
    {
        return CashSale::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->get();
    }
    
    /**
     * المخططات البيانية
     */
    public function getChartsData(Request $request)
    {
        $type = $request->input('type', 'monthly');
        
        if ($type === 'monthly') {
            $data = $this->getMonthlySalesData();
        } elseif ($type === 'brands') {
            $data = $this->getBrandsDistributionData();
        } else {
            $data = $this->getPaymentStatusData();
        }
        
        return response()->json($data);
    }
    
    /**
     * بيانات المبيعات الشهرية للرسم البياني
     */
    private function getMonthlySalesData()
    {
        $months = [];
        $cashSales = [];
        $financingRequests = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('F');
            $months[] = $monthName;
            
            // المبيعات النقدية
            $cashSales[] = CashSale::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('car_price');
            
            // طلبات التمويل
            $financingRequests[] = CarFinancingRequest::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'المبيعات النقدية (ر.س)',
                    'data' => $cashSales,
                    'backgroundColor' => 'rgba(67, 97, 238, 0.2)',
                    'borderColor' => 'rgba(67, 97, 238, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'طلبات التمويل',
                    'data' => $financingRequests,
                    'backgroundColor' => 'rgba(6, 214, 160, 0.2)',
                    'borderColor' => 'rgba(6, 214, 160, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }
    
    // باقي الدوال كما كانت...
}