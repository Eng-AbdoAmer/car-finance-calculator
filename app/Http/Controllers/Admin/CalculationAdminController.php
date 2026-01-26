<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashSale;
use App\Models\CarFinancingRequest;
use App\Models\FinanceCalculation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculationAdminController extends Controller
{
    /**
     * عرض جميع الحسابات (شاملة للثلاثة أنواع)
     */
    public function index(Request $request)
    {
        // التحقق من المستخدم المحدد
        $selectedUser = null;
        if ($request->filled('user_id')) {
            $selectedUser = User::find($request->user_id);
        }
        
        // بناء استعلام المبيعات النقدية
        $cashSalesQuery = CashSale::with(['user', 'carBrand', 'carModel', 'bank'])
            ->when($request->filled('phone'), function($query) use ($request) {
                $query->where('phone', 'LIKE', "%{$request->phone}%");
            })
            ->when($request->filled('user_id'), function($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('cash_status') && $request->cash_status !== 'all', function($query) use ($request) {
                $query->where('payment_status', $request->cash_status);
            });
        
        $cashSales = $cashSalesQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'cash_sales_page');
        
        // بناء استعلام طلبات التمويل (البنوك الأخرى)
        $financingQuery = CarFinancingRequest::with(['user', 'brand', 'model', 'bank'])
            ->when($request->filled('phone'), function($query) use ($request) {
                $query->where('phone', 'LIKE', "%{$request->phone}%");
            })
            ->when($request->filled('user_id'), function($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('financing_status') && $request->financing_status !== 'all', function($query) use ($request) {
                $query->where('status', $request->financing_status);
            });
        
        $financingRequests = $financingQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'financing_requests_page');
        
        // بناء استعلام حسابات الراجحي
        $calculationQuery = FinanceCalculation::with(['user', 'carModel'])
            ->when($request->filled('phone'), function($query) use ($request) {
                $query->where('phone', 'LIKE', "%{$request->phone}%");
            })
            ->when($request->filled('user_id'), function($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('calculation_status') && $request->calculation_status !== 'all', function($query) use ($request) {
                $query->where('status', $request->calculation_status);
            });
        
        $financeCalculations = $calculationQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'finance_calculations_page');
        
        // إحصائيات عامة
        $stats = [
            'total_cash_sales' => CashSale::count(),
            'total_financing' => CarFinancingRequest::count(),
            'total_calculations' => FinanceCalculation::count(),
            'total_amount' => CashSale::sum('car_price') + 
                             CarFinancingRequest::sum('car_price') + 
                             FinanceCalculation::sum('car_price'),
        ];
        
        // قائمة المستخدمين للفلتر
        $users = User::whereHas('cashSales')
            ->orWhereHas('carFinancingRequests')
            ->orWhereHas('financeCalculations')
            ->orderBy('name')
            ->get();
        
        return view('admin.calculations.index', compact(
            'cashSales',
            'financingRequests',
            'financeCalculations',
            'stats',
            'users',
            'selectedUser'
        ));
    }

    /**
     * الحصول على جميع الحسابات لمستخدم محدد
     */
    public function getUserCalculations($userId)
    {
        $user = User::with([
            'cashSales' => function($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'carFinancingRequests' => function($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'financeCalculations' => function($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            }
        ])->findOrFail($userId);
        
        return view('admin.calculations.user', compact('user'));
    }

    /**
     * تصدير جميع الحسابات
     */
    public function export(Request $request)
    {
        $type = $request->type;
        
        switch ($type) {
            case 'cash_sales':
                $data = CashSale::with(['user', 'carBrand', 'carModel', 'bank'])
                    ->when($request->filled('phone'), function($query) use ($request) {
                        $query->where('phone', 'LIKE', "%{$request->phone}%");
                    })
                    ->when($request->filled('status') && $request->status !== 'all', function($query) use ($request) {
                        $query->where('payment_status', $request->status);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                $filename = 'cash-sales-export-' . date('Y-m-d') . '.csv';
                break;
                
            case 'financing':
                $data = CarFinancingRequest::with(['user', 'brand', 'model', 'bank'])
                    ->when($request->filled('phone'), function($query) use ($request) {
                        $query->where('phone', 'LIKE', "%{$request->phone}%");
                    })
                    ->when($request->filled('status') && $request->status !== 'all', function($query) use ($request) {
                        $query->where('status', $request->status);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                $filename = 'financing-requests-export-' . date('Y-m-d') . '.csv';
                break;
                
            case 'calculations':
                $data = FinanceCalculation::with(['user', 'carModel'])
                    ->when($request->filled('phone'), function($query) use ($request) {
                        $query->where('phone', 'LIKE', "%{$request->phone}%");
                    })
                    ->when($request->filled('status') && $request->status !== 'all', function($query) use ($request) {
                        $query->where('status', $request->status);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                $filename = 'finance-calculations-export-' . date('Y-m-d') . '.csv';
                break;
                
            default:
                return redirect()->back()->with('error', 'نوع التصدير غير صالح.');
        }
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // كتابة العناوين حسب النوع
            switch ($type) {
                case 'cash_sales':
                    fputcsv($file, ['ID', 'العميل', 'الهاتف', 'الماركة', 'الموديل', 'السعر', 'المدفوع', 'المتبقي', 'الحالة', 'التاريخ']);
                    foreach ($data as $sale) {
                        fputcsv($file, [
                            $sale->id,
                            $sale->user->name ?? 'غير محدد',
                            $sale->phone,
                            $sale->carBrand->name ?? 'غير محدد',
                            $sale->carModel->model_year ?? 'غير محدد',
                            $sale->car_price,
                            $sale->paid_amount,
                            $sale->remaining_amount,
                            $sale->payment_status,
                            $sale->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;
                    
                case 'financing':
                    fputcsv($file, ['ID', 'العميل', 'الهاتف', 'البنك', 'الماركة', 'الموديل', 'السعر', 'الدفعة', 'القسط الشهري', 'الحالة', 'التاريخ']);
                    foreach ($data as $request) {
                        fputcsv($file, [
                            $request->id,
                            $request->user->name ?? 'غير محدد',
                            $request->phone,
                            $request->bank->name ?? 'غير محدد',
                            $request->brand->name ?? 'غير محدد',
                            $request->model->model_year ?? 'غير محدد',
                            $request->car_price,
                            $request->down_payment,
                            $request->monthly_installment,
                            $request->status,
                            $request->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;
                    
                case 'calculations':
                    fputcsv($file, ['ID', 'العميل', 'الهاتف', 'الموديل', 'السعر', 'الدفعة', 'القسط الشهري', 'الحالة', 'التاريخ']);
                    foreach ($data as $calculation) {
                        fputcsv($file, [
                            $calculation->id,
                            $calculation->user->name ?? 'غير محدد',
                            $calculation->phone,
                            $calculation->carModel->model_year ?? 'غير محدد',
                            $calculation->car_price,
                            $calculation->down_payment_amount,
                            $calculation->monthly_installment_with_insurance,
                            $calculation->status,
                            $calculation->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * الحصول على إحصائيات الحسابات
     */
    public function getStats()
    {
        $stats = [
            'cash_sales' => [
                'total' => CashSale::count(),
                'pending' => CashSale::where('payment_status', 'pending')->count(),
                'partial_paid' => CashSale::where('payment_status', 'partial_paid')->count(),
                'fully_paid' => CashSale::where('payment_status', 'fully_paid')->count(),
                'cancelled' => CashSale::where('payment_status', 'cancelled')->count(),
                'total_amount' => CashSale::sum('car_price'),
            ],
            'financing' => [
                'total' => CarFinancingRequest::count(),
                'pending' => CarFinancingRequest::where('status', 'pending')->count(),
                'sold' => CarFinancingRequest::where('status', 'sold')->count(),
                'not_sold' => CarFinancingRequest::where('status', 'not_sold')->count(),
                'follow_up' => CarFinancingRequest::where('status', 'follow_up')->count(),
                'total_amount' => CarFinancingRequest::sum('car_price'),
            ],
            'calculations' => [
                'total' => FinanceCalculation::count(),
                'pending' => FinanceCalculation::where('status', 'pending')->count(),
                'sold' => FinanceCalculation::where('status', 'sold')->count(),
                'not_sold' => FinanceCalculation::where('status', 'not_sold')->count(),
                'follow_up' => FinanceCalculation::where('status', 'follow_up')->count(),
                'total_amount' => FinanceCalculation::sum('car_price'),
            ],
            'total_all' => [
                'count' => CashSale::count() + CarFinancingRequest::count() + FinanceCalculation::count(),
                'amount' => CashSale::sum('car_price') + 
                           CarFinancingRequest::sum('car_price') + 
                           FinanceCalculation::sum('car_price'),
            ],
        ];
        
        return response()->json($stats);
    }
}