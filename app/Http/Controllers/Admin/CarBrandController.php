<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarBrandController extends Controller
{
    /**
     * عرض قائمة البراندات
     */
    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي
        $query = CarBrand::query();
        
        // البحث بالاسم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }
        
        // الفرز
        $query->orderBy('name');
        
        $brands = $query->paginate(20);
        
        // إحصائيات البراندات
        $stats = [
            'total' => CarBrand::count(),
        ];
        
        return view('admin.car-brands.index', compact('brands', 'stats'));
    }

    /**
     * عرض نموذج إنشاء براند جديد
     */
    public function create()
    {
        return view('admin.car-brands.create');
    }

    /**
     * حفظ البراند الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:car_brands,name',
        ]);

        CarBrand::create($validated);

        return redirect()->route('admin.car-brands.index')
            ->with('success', 'تم إنشاء البراند بنجاح.');
    }

    /**
     * عرض تفاصيل البراند
     */
    public function show($id)
    {
        $brand = CarBrand::findOrFail($id);
        
        return view('admin.car-brands.show', compact('brand'));
    }

    /**
     * عرض نموذج تعديل البراند
     */
    public function edit($id)
    {
        $brand = CarBrand::findOrFail($id);
        return view('admin.car-brands.edit', compact('brand'));
    }

    /**
     * تحديث بيانات البراند
     */
    public function update(Request $request, $id)
    {
        $brand = CarBrand::findOrFail($id);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_brands')->ignore($brand->id),
            ],
        ]);
        
        $brand->update($validated);

        return redirect()->route('admin.car-brands.index')
            ->with('success', 'تم تحديث بيانات البراند بنجاح.');
    }

    /**
     * حذف البراند
     */
    public function destroy($id)
    {
        $brand = CarBrand::findOrFail($id);
        
        // التحقق مما إذا كان هناك موديلات مرتبطة بهذا البراند
        // if ($brand->models()->count() > 0) {
        //     return redirect()->back()
        //         ->with('error', 'لا يمكن حذف هذا البراند لأنه يحتوي على موديلات مرتبطة به.');
        // }
        
        $brand->delete();

        return redirect()->route('admin.car-brands.index')
            ->with('success', 'تم حذف البراند بنجاح.');
    }
}