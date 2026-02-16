<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarFinancingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CarFinancingController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('name')->get();
        $brands = CarBrand::orderBy('name')->get();
        $models = CarModel::orderBy('model_year', 'desc')->get();
        
        return view('financing.index', compact('banks', 'brands', 'models'));
    }

    public function calculate(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'bank_id' => 'required|exists:banks,id',
            'murabaha_rate' => 'required|numeric|min:0|max:100',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'last_payment_rate' => 'required|numeric|min:0|max:100',
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_model_id' => 'required|exists:car_models,id',
            'car_price' => 'required|numeric|min:10000|max:10000000',
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
            'duration_years' => 'required|integer|min:1|max:5',
        ]);

        // الحسابات الأساسية
        $carPrice = floatval($validated['car_price']);
        $downPaymentPercentage = floatval($validated['down_payment_percentage']);
        $downPayment = $carPrice * ($downPaymentPercentage / 100);
        $financingAmount = $carPrice - $downPayment;
        $durationMonths = intval($validated['duration_years']) * 12;

        // حساب الدفعة الأخيرة (قيمة)
        $lastPaymentRate = floatval($validated['last_payment_rate']);
        $lastPayment = $carPrice * ($lastPaymentRate / 100);

        // حساب إجمالي التأمين
        $insuranceRate = floatval($validated['insurance_rate']);
        $insuranceTotal = $carPrice * ($insuranceRate / 100) * intval($validated['duration_years']);

        // حساب إجمالي المرابحة
        $murabahaRate = floatval($validated['murabaha_rate']);
        $murabahaTotal = $financingAmount * ($murabahaRate / 100) * intval($validated['duration_years']);

        // حساب القسط الشهري
        $totalWithoutLast = $financingAmount + $murabahaTotal + $insuranceTotal - $lastPayment;
        $monthlyInstallment = $totalWithoutLast / $durationMonths;

        // إجمالي المبلغ الممول
        $totalAmount = $downPayment + ($monthlyInstallment * $durationMonths) + $lastPayment;

        // قيمة السيارة بعد الدفعة الأولي
        $carValueAfterDown = $financingAmount;

        // الفائدة مع التأمين
        $interestWithInsurance = $murabahaTotal + $insuranceTotal;

        // حفظ الطلب
        $financingRequest = CarFinancingRequest::create([
            'phone' => $validated['phone'],
            'bank_id' => $validated['bank_id'],
            'car_brand_id' => $validated['car_brand_id'],
            'car_model_id' => $validated['car_model_id'],
            'car_price' => $carPrice,
            'down_payment_percentage' => $downPaymentPercentage,
            'down_payment' => $downPayment,
            'financing_amount' => $financingAmount,
            'duration_months' => $durationMonths,
            'last_payment_rate' => $lastPaymentRate,
            'last_payment' => $lastPayment,
            'insurance_rate' => $insuranceRate,
            'insurance_total' => $insuranceTotal,
            'murabaha_rate' => $murabahaRate,
            'murabaha_total' => $murabahaTotal,
            'monthly_installment' => $monthlyInstallment,
            'interest_with_insurance' => $interestWithInsurance,
            'car_value_after_down' => $carValueAfterDown,
            'total_amount' => $totalAmount,
             'status' => 'pending',
            'user_id' => Auth::id(),
        ]);
        
        // عرض النتائج
        return view('financing.result', compact('financingRequest'));
    }


      public function history(Request $request)
    {
        $query = CarFinancingRequest::with(['bank', 'brand', 'model', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // فلترة حسب تاريخ البدء
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        // فلترة حسب تاريخ النهاية
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $requests = $query->paginate(10);
        
        return view('financing.history', compact('requests'));
    }

    /**
     * عرض تفاصيل طلب معين
     */
   public function show($id)
    {
        $request = CarFinancingRequest::with(['bank', 'brand', 'model', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('financing.show', compact('request'));
    }

    /**
     * تحديث الحالة
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,sold,not_sold,follow_up,cancelled',
        ]);

        $financingRequest = CarFinancingRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        $financingRequest->update([
            'status' => $validated['status'],
        ]);

        // نصوص الحالة للعرض
        $statusText = [
            'pending' => 'قيد الانتظار',
            'sold' => 'تم البيع',
            'not_sold' => 'لم يتم البيع',
            'follow_up' => 'متابعة',
            'cancelled' => 'ملغي'
        ];

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحالة بنجاح',
            'data' => [
                'id' => $financingRequest->id,
                'status' => $financingRequest->status,
                'status_text' => $statusText[$financingRequest->status] ?? $financingRequest->status,
            ]
        ]);
    }

    /**
     * الحصول على بيانات الحالة
     */
   public function getStatus($id)
    {
        $financingRequest = CarFinancingRequest::where('user_id', Auth::id())
            ->with(['bank', 'brand', 'model'])
            ->findOrFail($id);
        
        $statusText = [
            'pending' => 'قيد الانتظار',
            'sold' => 'تم البيع',
            'not_sold' => 'لم يتم البيع',
            'follow_up' => 'متابعة',
            'cancelled' => 'ملغي'
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $financingRequest->id,
                'status' => $financingRequest->status,
                'status_text' => $statusText[$financingRequest->status] ?? $financingRequest->status,
                'bank_name' => $financingRequest->bank->name ?? 'غير محدد',
                'car_brand' => $financingRequest->brand->name ?? 'غير محدد',
                'car_model' => $financingRequest->model->model_year ?? 'غير محدد'
            ]
        ]);
    }

    /**
     * حذف طلب
     */
      public function destroy($id)
    {
        $financingRequest = CarFinancingRequest::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $financingRequest->delete();
        
        return redirect()->route('financing.history')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
    /**
     * إرسال النتائج عبر واتساب
     */
    public function sendWhatsAppMessage($id)
    {
        $financingRequest = CarFinancingRequest::with(['bank', 'brand', 'model'])->findOrFail($id);
        
        // إعداد نص الرسالة
        $message = $this->prepareWhatsAppMessage($financingRequest);
        
        // تشفير النص لرابط واتساب
        $encodedMessage = urlencode($message);
        
        // إنشاء رابط واتساب
        $phone = $financingRequest->phone;
        // إزالة أي رموز غير رقمية من رقم الهاتف
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // التأكد من بدء الرقم بـ 966 (للسعودية)
        if (substr($cleanPhone, 0, 3) !== '966' && strlen($cleanPhone) == 9) {
            $cleanPhone = '966' . $cleanPhone;
        } elseif (substr($cleanPhone, 0, 4) == '+966') {
            $cleanPhone = substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 1) == '0') {
            $cleanPhone = '966' . substr($cleanPhone, 1);
        }
        
        $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
        
        // تحديث حالة الإرسال (إذا كان لديك حقل في قاعدة البيانات)
        // $financingRequest->update(['whatsapp_sent' => true]);
        
        return redirect()->away($whatsappUrl);
    }
    
    /**
     * إعداد نص رسالة واتساب
     */
    // private function prepareWhatsAppMessage($request)
    // {
    //    // $message ="*شركة العربه الفريده للسيارات*\n\n";
    //     $message = "📊 *شركة العربه الفريده للسيارات*\n\n";
    //     $message .= "🕒 تاريخ الحساب: " . date('Y-m-d H:i') . "\n";
    //      $message .= "🏦 البنك: " . ($request->bank->name ?? 'غير محدد') . "\n\n"; 
        
    //     $message .= "🚗 *معلومات السيارة:*\n";
    //     $message .= "العلامة التجارية: " . ($request->brand->name ?? 'غير محدد') . "\n";
    //     $message .= "الموديل: " . ($request->model->model_year ?? 'غير محدد') . "\n";
    //     //$message .= "سعر السيارة: " . number_format($request->car_price, 0) . " ر.س\n\n";
        
        
    //     // $message .= "مبلغ التمويل: " . number_format($request->financing_amount, 0) . " ر.س\n";
        
    //     // $message .= "📈 *التكاليف:*\n";
    //     // $message .= "إجمالي المرابحة: " . number_format($request->murabaha_total, 0) . " ر.س\n";
    //     // $message .= "إجمالي التأمين: " . number_format($request->insurance_total, 0) . " ر.س\n\n";
    //     //$message .= "💳 *معلومات التمويل:*\n";
    //      $message .= "*------هذه النتائج تقريبية------*\n";
    //     // $message .= "💰 *النتائج النهائية:*\n";
    //     $message .= "الدفعة الأولى: " . number_format($request->down_payment, 0) . " ر.س\n";
    //     $message .= "القسط الشهري (شامل التأمين): *" . number_format($request->monthly_installment, 0) . " ر.س*\n";
    //     $message .= "الدفعة الأخيرة: " . number_format($request->last_payment, 0) . " ر.س\n";
    //     $message .= "مدة التمويل: " . $request->duration_months . " شهر\n\n";
    //     $message .= "الإجمالي الكلي: " . number_format($request->total_amount, 0) . " ر.س\n\n";
        
    //     // $message .= "📊 *ملخص النسب:*\n";
    //     // $message .= "نسبة المرابحة: " . $request->murabaha_rate . "%\n";
    //     // $message .= "نسبة التأمين: " . $request->insurance_rate . "%\n";
    //     // $message .= "نسبة الدفعة الأولى: " . $request->down_payment_percentage . "%\n";
    //     // $message .= "نسبة الدفعة الأخيرة: " . $request->last_payment_rate . "%\n\n";
        
    //     //$message .= "🔗 *رابط النتائج الكاملة:*\n";
    //     //$message .= route('financing.result.view', ['id' => $request->id]) . "\n\n";
        
    //    // $message .= "📞 للاستفسار: 0501234567\n";
    //     $message .= "شكراً لاستخدامكم حاسبة التمويل من بنك الراجحي";
        
    //     return $message;
    // }
//البنوك النتعددة
private function prepareWhatsAppMessage($request)
{
    $message = "📊 *شركة العربة الفريدة للسيارات*\n";
    $message .= "🏦 البنك: " . ($request->bank->name ?? 'غير محدد') . "\n";
    $message .= "─────────────────────\n";
    
    $message .= "\n🚗 *معلومات السيارة:*\n";
    $message .= "🚙 نوع السيارة: " . ($request->brand->name ?? 'غير محدد') . "\n";
    $message .= "📅 الموديل: " . ($request->model->model_year ?? 'غير محدد') . "\n";
    
    $message .= "\n⚠️ *هذه النتائج تقريبية*\n";
    $message .= "─────────────────────\n";
    
    $message .= "💰 الدفعة الأولى: " . number_format($request->down_payment, 0) . " ر.س\n";
    $message .= "💳 القسط الشهري: *" . number_format($request->monthly_installment, 0) . " ر.س*\n";
    $message .= "🔚 الدفعة الأخيرة: " . number_format($request->last_payment, 0) . " ر.س\n";
    $message .= "⏳ مدة التمويل: " . $request->duration_months . " شهر\n";
    $message .= "🏷️ الإجمالي الكلي: " . number_format($request->total_amount, 0) . " ر.س\n";
    
    $message .= "\n─────────────────────\n";
    $message .= "📍 *موقعنا على الخريطة:*\n";
    $message .= "https://maps.app.goo.gl/2MiE1RG5YpMzymEd6";
    
    return $message;
}
      public function getModels($brandId)
    {
        $models = CarModel::where('car_brand_id', $brandId)
            ->orderBy('model_year', 'desc')
            ->get();
        
        return response()->json($models);
    }

    /**
     * الحصول على أسعار البنك
     */
    public function getBankRates($bankId)
    {
        $bank = Bank::find($bankId);
        
        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'البنك غير موجود'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'murabaha_rate' => $bank->murabaha_rate,
                'insurance_rate' => $bank->insurance_rate,
                'last_payment_rate' => $bank->last_payment_rate,
            ]
        ]);
    }

    /**
     * عرض النتائج عبر ID
     */
    public function showResult($id)
    {
        $financingRequest = CarFinancingRequest::with(['bank', 'brand', 'model'])
            ->findOrFail($id);
        
        return view('financing.result', compact('financingRequest'));
    }
}