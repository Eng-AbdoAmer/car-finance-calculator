<?php

namespace App\Http\Controllers;

use App\Models\CashSale;
use App\Models\CarModel;
use App\Models\CarBrand;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashSaleController extends Controller
{
    // عرض جميع مبيعات الكاش
    public function index(Request $request)
    {
        $query = CashSale::with(['carBrand', 'carModel', 'bank', 'user'])
            ->where('user_id', Auth::id())
            ->latest();

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // فلترة حسب المصدر
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // فلترة حسب تاريخ البدء
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // فلترة حسب تاريخ النهاية
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // فلترة حسب البنك
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        // فلترة حسب ماركة السيارة
        if ($request->filled('car_brand_id')) {
            $query->where('car_brand_id', $request->car_brand_id);
        }

        // فلترة حسب الموديل
        if ($request->filled('car_model_id')) {
            $query->where('car_model_id', $request->car_model_id);
        }

        $cashSales = $query->paginate(15);
        $banks = Bank::all();
        $carBrands = CarBrand::orderBy('name')->get();
        $carModels = CarModel::orderBy('model_year', 'DESC')->get();
        
        // إحصائيات
        $stats = [
            'total' => CashSale::where('user_id', Auth::id())->count(),
            'total_amount' => CashSale::where('user_id', Auth::id())->sum('car_price'),
            'total_paid' => CashSale::where('user_id', Auth::id())->sum('paid_amount'),
            'total_remaining' => CashSale::where('user_id', Auth::id())->sum('remaining_amount'),
        ];

        return view('cash-sales.index', compact('cashSales', 'banks', 'carBrands', 'carModels', 'stats'));
    }

    // إنشاء عملية بيع جديدة
    public function create()
    {
        $carBrands = CarBrand::orderBy('name')->get();
        // $carModels = CarModel::orderBy('model_year', 'DESC')->get();
         $models = CarModel::orderBy('model_year', 'desc')->get();
        $banks = Bank::all();

        return view('cash-sales.create', compact('carBrands', 'models', 'banks'));
    }

    // حفظ عملية البيع
public function store(Request $request)
{
    // dd($request->all()); // تأكد من البيانات القادمة
    
    $validated = $request->validate([
        'phone' => 'required|string|max:20',
        'car_brand_id' => 'required|exists:car_brands,id',
        'car_model_id' => 'required|exists:car_models,id',
        'car_color' => 'required|string|max:50',
        'car_category' => 'required|string|max:50',
        'car_price' => 'required|numeric|min:0',
        'paid_amount' => 'required|numeric|min:0|lte:car_price',
        'bank_id' => 'nullable|exists:banks,id',
        'source' => 'required|in:in_stock,external_purchase',
        'notes' => 'nullable|string|max:1000'
    ]);

    // حساب المبلغ المتبقي
    $remaining = max(0, $validated['car_price'] - $validated['paid_amount']);

    // تحديد حالة الدفع
    $paymentStatus = 'pending';
    if ($validated['paid_amount'] >= $validated['car_price']) {
        $paymentStatus = 'fully_paid';
    } elseif ($validated['paid_amount'] > 0) {
        $paymentStatus = 'partial_paid';
    }

    // **الحل: معالجة قيمة bank_id إذا كانت فارغة**
    $bankId = $validated['bank_id'] ?? null;
    
    // إذا كانت فارغة (''، null، 0) نضع null
    if (empty($bankId) || $bankId == '' || $bankId == 0) {
        $bankId = null;
    }

    // لأغراض التصحيح - عرض البيانات قبل الحفظ
    $dataToSave = [
        'user_id' => Auth::id(),
        'phone' => $validated['phone'],
        'car_brand_id' => $validated['car_brand_id'],
        'car_model_id' => $validated['car_model_id'],
        'bank_id' => $bankId, // **استخدم القيمة المعدلة**
        'car_color' => $validated['car_color'],
        'car_category' => $validated['car_category'],
        'car_price' => $validated['car_price'],
        'paid_amount' => $validated['paid_amount'],
        'remaining_amount' => $remaining,
        'source' => $validated['source'],
        'payment_status' => $paymentStatus,
        'notes' => $validated['notes'],
        'payments' => json_encode([])
    ];
    
    // **للتأكد من البيانات قبل الحفظ**
    \Log::info('بيانات الحفظ:', $dataToSave);

    DB::beginTransaction();
    try {
        $cashSale = CashSale::create($dataToSave);
        
        DB::commit();
        
        return redirect()->route('cash-sales.show', $cashSale->id)
            ->with('success', 'تم إضافة عملية البيع بنجاح');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('خطأ في حفظ عملية البيع:', [
            'error' => $e->getMessage(),
            'data' => $dataToSave,
            'request' => $request->all()
        ]);
        
        return redirect()->back()
            ->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()])
            ->withInput();
    }
}
    // عرض تفاصيل عملية بيع
    public function show($id)
    {
        $cashSale = CashSale::with(['carBrand', 'carModel', 'bank', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // نصوص الحالة للعرض
        $statusText = $this->getStatusText();
        
        return view('cash-sales.show', compact('cashSale', 'statusText'));
    }

    // تحرير عملية بيع
    public function edit($id)
    {
        $cashSale = CashSale::with(['carBrand', 'carModel', 'bank'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        $carBrands = CarBrand::orderBy('name')->get();
        $models = CarModel::orderBy('model_year', 'desc')->get();
        $banks = Bank::all();
        $categories = ['فاخرة', 'اقتصادية', 'SUV', 'رياضية', 'عائلية', 'تجارية'];
        $statuses = $this->getStatusText();

        return view('cash-sales.edit', compact('cashSale', 'carBrands', 'models', 'banks', 'categories', 'statuses'));
    }

    // تحديث عملية بيع
    public function update(Request $request, $id)
    {
        $cashSale = CashSale::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'car_color' => 'required|string|max:50',
            'car_category' => 'required|string|max:50',
            'car_price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|lte:car_price',
            'bank_id' => 'nullable|exists:banks,id',
            'source' => 'required|in:in_stock,external_purchase',
            'payment_status' => 'required|in:pending,partial_paid,fully_paid,delivered,cancelled,refunded,on_hold',
            'notes' => 'nullable|string|max:1000'
        ]);

        // إعادة حساب المبلغ المتبقي
        $remaining = max(0, $validated['car_price'] - $validated['paid_amount']);

        DB::beginTransaction();
        try {
            $cashSale->update([
                'car_brand_id' => $validated['car_brand_id'],
                'car_model_id' => $validated['car_model_id'],
                'car_color' => $validated['car_color'],
                'car_category' => $validated['car_category'],
                'car_price' => $validated['car_price'],
                'paid_amount' => $validated['paid_amount'],
                'remaining_amount' => $remaining,
                'bank_id' => $validated['bank_id'],
                'source' => $validated['source'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes']
            ]);

            DB::commit();
            
            return redirect()->route('cash-sales.show', $cashSale->id)
                ->with('success', 'تم تحديث عملية البيع بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // حذف عملية بيع
    public function destroy($id)
    {
        $cashSale = CashSale::where('user_id', Auth::id())
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $cashSale->delete();
            DB::commit();
            
            return redirect()->route('cash-sales.index')
                ->with('success', 'تم حذف عملية البيع بنجاح');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()]);
        }
    }

    // إضافة دفعة جديدة
// في App\Http\Controllers\CashSaleController.php

// إصلاح دالة addPayment
public function addPayment(Request $request, $id)
{
    $cashSale = CashSale::where('user_id', Auth::id())
        ->findOrFail($id);

    // التحقق من أن العملية قابلة للدفع
    if ($cashSale->remaining_amount <= 0) {
        return redirect()->back()
            ->withErrors(['error' => 'لا يوجد مبلغ متبقي للدفع!']);
    }

    if (in_array($cashSale->payment_status, ['cancelled', 'refunded'])) {
        return redirect()->back()
            ->withErrors(['error' => 'لا يمكن إضافة دفعة لعملية ملغية أو مرتجعة!']);
    }

    $validated = $request->validate([
        'payment_amount' => [
            'required',
            'numeric',
            'min:1',
            function ($attribute, $value, $fail) use ($cashSale) {
                if ($value > $cashSale->remaining_amount) {
                    $fail('المبلغ المدفوع لا يمكن أن يكون أكبر من المبلغ المتبقي (' . number_format($cashSale->remaining_amount, 2) . ')');
                }
            }
        ],
        'payment_date' => 'required|date|before_or_equal:today',
        'payment_method' => 'required|in:نقدي,تحويل بنكي,شيك',
        'payment_notes' => 'nullable|string|max:500'
    ]);

    DB::beginTransaction();
    try {
        // استخدم الدالة الجديدة من الـ Model
        $result = $cashSale->addPayment(
            $validated['payment_amount'],
            $validated['payment_date'],
            $validated['payment_method'],
            $validated['payment_notes']
        );

        if (!$result) {
            throw new \Exception('فشل حفظ الدفعة');
        }

        DB::commit();

        // إعادة تحميل البيانات من قاعدة البيانات
        $cashSale->refresh();

        return redirect()->route('cash-sales.show', $cashSale->id)
            ->with('success', 'تم إضافة الدفعة بنجاح! المبلغ المتبقي: ' . number_format($cashSale->remaining_amount, 2) . ' ر.س');

    } catch (\Exception $e) {
        DB::rollBack();
        
        // عرض الخطأ للتصحيح
        \Log::error('Error adding payment: ' . $e->getMessage());
        
        return redirect()->back()
            ->withErrors(['error' => 'حدث خطأ أثناء إضافة الدفعة: ' . $e->getMessage()])
            ->withInput();
    }
}
    // حذف دفعة
    public function deletePayment(Request $request, $id, $paymentId)
    {
        $cashSale = CashSale::where('user_id', Auth::id())
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $payments = $cashSale->payments;
            
            // البحث عن الدفعة
            $paymentIndex = null;
            foreach ($payments as $index => $payment) {
                if ($payment['id'] == $paymentId) {
                    $paymentIndex = $index;
                    break;
                }
            }

            if ($paymentIndex === null) {
                throw new \Exception('الدفعة غير موجودة');
            }

            $paymentAmount = $payments[$paymentIndex]['amount'];
            
            // حذف الدفعة
            array_splice($payments, $paymentIndex, 1);
            
            // تحديث المبالغ
            $cashSale->paid_amount -= $paymentAmount;
            $cashSale->remaining_amount += $paymentAmount;
            
            // تحديث الحالة
            $cashSale->updatePaymentStatus();
            
            // تحديث سجل الدفعات
            $cashSale->payments = $payments;
            $cashSale->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم حذف الدفعة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage()]);
        }
    }
    // تحديث حالة الدفع
    public function updateStatus(Request $request, $id)
    {
        $cashSale = CashSale::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,partial_paid,fully_paid,delivered,cancelled,refunded,on_hold'
        ]);

        $cashSale->update([
            'payment_status' => $request->status,
            'is_active' => !in_array($request->status, ['cancelled', 'refunded'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحالة بنجاح',
            'status_text' => $this->getStatusText()[$request->status]
        ]);
    }

    // الحصول على نصوص الحالة
    private function getStatusText()
    {
        return [
            'pending' => 'قيد الانتظار',
            'partial_paid' => 'مدفوع جزئياً',
            'fully_paid' => 'مدفوع بالكامل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            'refunded' => 'تم الاسترجاع',
            'on_hold' => 'معلق'
        ];
    }

    // AJAX: جلب الموديلات حسب الماركة
    public function getModelsByBrand($brandId)
    {
        $models = CarModel::where('car_brand_id', $brandId)->orderBy('model_year', 'DESC')->get();
        return response()->json($models);
    }
    

    
}