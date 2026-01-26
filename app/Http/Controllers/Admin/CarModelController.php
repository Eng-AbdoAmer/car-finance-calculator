<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarModelController extends Controller
{
    /**
     * عرض قائمة سنوات الموديلات
     */
    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي
        $query = CarModel::query();
        
        // البحث بالسنة
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('model_year', 'LIKE', "%{$search}%");
        }
        
        // التصفية حسب الفترة
        if ($request->filled('period')) {
            $currentYear = Carbon::now()->year;
            switch ($request->period) {
                case 'recent':
                    $query->where('model_year', '>=', $currentYear - 5);
                    break;
                case 'old':
                    $query->where('model_year', '<', $currentYear - 10);
                    break;
                case 'current_year':
                    $query->where('model_year', $currentYear);
                    break;
                case 'next_year':
                    $query->where('model_year', $currentYear + 1);
                    break;
            }
        }
        
        // الفرز من الأحدث إلى الأقدم
        $query->orderBy('model_year', 'desc');
        
        $models = $query->paginate(20);
        
        // إحصائيات السنوات
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        
        $stats = [
            'total' => CarModel::count(),
            'current_year' => CarModel::where('model_year', $currentYear)->count(),
            'next_year' => CarModel::where('model_year', $nextYear)->count(),
            'recent' => CarModel::where('model_year', '>=', $currentYear - 5)->count(),
            'latest_year' => CarModel::max('model_year'),
            'oldest_year' => CarModel::min('model_year'),
        ];
        
        return view('admin.car-models.index', compact('models', 'stats', 'currentYear', 'nextYear'));
    }

    /**
     * عرض نموذج إنشاء سنة موديل جديدة
     */
    public function create()
    {
        // الحصول على آخر سنة مضافة
        $latestYear = CarModel::max('model_year');
        $currentYear = Carbon::now()->year;
        
        // اقتراح السنة التالية تلقائياً
        $suggestedYear = $latestYear ? $latestYear + 1 : $currentYear;
        
        // إذا كانت السنة المقترحة أقل من السنة الحالية، استخدم السنة الحالية
        if ($suggestedYear < $currentYear) {
            $suggestedYear = $currentYear;
        }
        
        return view('admin.car-models.create', compact('suggestedYear', 'currentYear'));
    }

    /**
     * حفظ سنة الموديل الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_year' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (Carbon::now()->year + 10),
                'unique:car_models,model_year',
            ],
        ]);

        CarModel::create([
            'model_year' => $validated['model_year'],
        ]);

        return redirect()->route('admin.car-models.index')
            ->with('success', 'تم إضافة سنة الموديل بنجاح.');
    }

    /**
     * عرض نموذج تعديل سنة الموديل
     */
    public function edit($id)
    {
        $model = CarModel::findOrFail($id);
        $currentYear = Carbon::now()->year;
        
        return view('admin.car-models.edit', compact('model', 'currentYear'));
    }

    /**
     * تحديث سنة الموديل
     */
    public function update(Request $request, $id)
    {
        $model = CarModel::findOrFail($id);
        
        $validated = $request->validate([
            'model_year' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (Carbon::now()->year + 10),
                Rule::unique('car_models')->ignore($model->id),
            ],
        ]);
        
        $model->update([
            'model_year' => $validated['model_year'],
        ]);

        return redirect()->route('admin.car-models.index')
            ->with('success', 'تم تحديث سنة الموديل بنجاح.');
    }

    /**
     * حذف سنة الموديل
     */
    public function destroy($id)
    {
        $model = CarModel::findOrFail($id);
        
        // التحقق مما إذا كان هناك طلبات تمويل مرتبطة بهذه السنة
        if ($model->financingRequests()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف سنة الموديل لأنها مرتبطة بطلبات تمويل.');
        }
        
        $model->delete();

        return redirect()->route('admin.car-models.index')
            ->with('success', 'تم حذف سنة الموديل بنجاح.');
    }

    /**
     * إضافة السنة الحالية تلقائياً
     */
    public function addCurrentYear()
    {
        $currentYear = Carbon::now()->year;
        
        // التحقق من وجود السنة الحالية
        $existing = CarModel::where('model_year', $currentYear)->first();
        
        if ($existing) {
            return redirect()->route('admin.car-models.index')
                ->with('error', 'سنة الموديل الحالية موجودة مسبقاً.');
        }
        
        CarModel::create(['model_year' => $currentYear]);
        
        return redirect()->route('admin.car-models.index')
            ->with('success', "تم إضافة سنة الموديل الحالية ({$currentYear}) تلقائياً.");
    }

    /**
     * إضافة السنة القادمة تلقائياً
     */
    public function addNextYear()
    {
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        
        // التحقق من وجود السنة القادمة
        $existing = CarModel::where('model_year', $nextYear)->first();
        
        if ($existing) {
            return redirect()->route('admin.car-models.index')
                ->with('error', 'سنة الموديل القادمة موجودة مسبقاً.');
        }
        
        CarModel::create(['model_year' => $nextYear]);
        
        return redirect()->route('admin.car-models.index')
            ->with('success', "تم إضافة سنة الموديل القادمة ({$nextYear}) تلقائياً.");
    }

    /**
     * إنشاء مجموعة سنوات دفعة واحدة
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'start_year' => 'required|integer|min:1900|max:' . (Carbon::now()->year + 10),
            'end_year' => 'required|integer|min:1900|max:' . (Carbon::now()->year + 10) . '|gte:start_year',
        ]);
        
        $startYear = $validated['start_year'];
        $endYear = $validated['end_year'];
        $added = 0;
        $skipped = 0;
        
        DB::beginTransaction();
        
        try {
            for ($year = $startYear; $year <= $endYear; $year++) {
                // التحقق من عدم وجود السنة مسبقاً
                $existing = CarModel::where('model_year', $year)->first();
                
                if (!$existing) {
                    CarModel::create(['model_year' => $year]);
                    $added++;
                } else {
                    $skipped++;
                }
            }
            
            DB::commit();
            
            $message = "تم إضافة {$added} سنة موديل جديدة.";
            if ($skipped > 0) {
                $message .= " تم تخطي {$skipped} سنة موجودة مسبقاً.";
            }
            
            return redirect()->route('admin.car-models.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إضافة السنوات: ' . $e->getMessage());
        }
    }

    /**
     * استيراد سنوات من ملف
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        
        $file = fopen($filePath, 'r');
        $imported = 0;
        $skipped = 0;
        
        DB::beginTransaction();
        
        try {
            // تخطي السطر الأول إذا كان يحتوي على عناوين
            fgetcsv($file);
            
            while (($data = fgetcsv($file)) !== FALSE) {
                $year = trim($data[0] ?? '');
                
                if (empty($year) || !is_numeric($year)) {
                    $skipped++;
                    continue;
                }
                
                $year = (int) $year;
                
                // التحقق من النطاق الزمني
                if ($year < 1900 || $year > (Carbon::now()->year + 10)) {
                    $skipped++;
                    continue;
                }
                
                // التحقق إذا كانت السنة موجودة مسبقاً
                $existing = CarModel::where('model_year', $year)->first();
                
                if (!$existing) {
                    CarModel::create(['model_year' => $year]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.car-models.index')
                ->with('success', "تم استيراد {$imported} سنة موديل، تم تخطي {$skipped} سنة مكررة أو غير صالحة.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * تصدير السنوات إلى ملف
     */
    public function export()
    {
        $models = CarModel::orderBy('model_year')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="car-models-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($models) {
            $file = fopen('php://output', 'w');
            
            // كتابة العناوين
            fputcsv($file, ['ID', 'Model Year', 'Created At']);
            
            // كتابة البيانات
            foreach ($models as $model) {
                fputcsv($file, [
                    $model->id,
                    $model->model_year,
                    $model->created_at->format('Y-m-d'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}