<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    /**
     * عرض قائمة أنواع السيارات
     */
    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي مع العلاقات
        $query = CarType::with('brand')
            ->withCount('trims');
        
        // البحث بالاسم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // البحث بالبراند
        if ($request->filled('brand_id')) {
            $query->where('car_brand_id', $request->brand_id);
        }
        
        // الفرز
        $query->orderBy('created_at', 'desc');
        
        $types = $query->paginate(20);
        
        // إحصائيات الأنواع
        $stats = [
            'total' => CarType::count(),
            'with_trims' => CarType::has('trims')->count(),
        ];
        
        // جميع البراندات للفلتر
        $brands = CarBrand::orderBy('name')->get();
        
        return view('admin.car-types.index', compact('types', 'stats', 'brands'));
    }

    /**
     * عرض نموذج إنشاء نوع جديد
     */
    public function create()
    {
        $brands = CarBrand::orderBy('name')->get();
        return view('admin.car-types.create', compact('brands'));
    }

    /**
     * حفظ النوع الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'car_brand_id' => 'required|exists:car_brands,id',
        ]);

        CarType::create($validated);

        return redirect()->route('admin.car-types.index')
            ->with('success', 'تم إنشاء النوع بنجاح.');
    }

    /**
     * عرض تفاصيل النوع
     */
    public function show($id)
    {
        $type = CarType::with(['brand', 'trims'])
            ->withCount('trims')
            ->findOrFail($id);
        
        return view('admin.car-types.show', compact('type'));
    }

    /**
     * عرض نموذج تعديل النوع
     */
    public function edit($id)
    {
        $type = CarType::findOrFail($id);
        $brands = CarBrand::orderBy('name')->get();
        return view('admin.car-types.edit', compact('type', 'brands'));
    }

    /**
     * تحديث بيانات النوع
     */
    public function update(Request $request, $id)
    {
        $type = CarType::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'car_brand_id' => 'required|exists:car_brands,id',
        ]);
        
        $type->update($validated);

        return redirect()->route('admin.car-types.index')
            ->with('success', 'تم تحديث بيانات النوع بنجاح.');
    }

    /**
     * حذف النوع
     */
    public function destroy($id)
    {
        $type = CarType::findOrFail($id);
        
        // التحقق مما إذا كان هناك طرازات مرتبطة بهذا النوع
        if ($type->trims()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف هذا النوع لأنه يحتوي على طرازات مرتبطة به.');
        }
        
        $type->delete();

        return redirect()->route('admin.car-types.index')
            ->with('success', 'تم حذف النوع بنجاح.');
    }
}