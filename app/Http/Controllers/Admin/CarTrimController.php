<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\CarTrim;
use Illuminate\Http\Request;

class CarTrimController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $brandId = $request->get('brand_id'); 
        $typeId = $request->get('type_id');

        // استعلام للفئات المترقمة
        $trimsQuery = CarTrim::with(['brand', 'type'])
            ->withCount('cars')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($brandId, function ($query) use ($brandId) {
                return $query->where('car_brand_id', $brandId);
            })
            ->when($typeId, function ($query) use ($typeId) {
                return $query->where('car_type_id', $typeId);
            })
            ->orderBy('car_brand_id')
            ->orderBy('car_type_id')
            ->orderBy('name');

        // الحصول على الفئات للمعاينة (بدون ترقيم)
        $allTrims = $trimsQuery->get();

        // الحصول على الفئات للترقيم
        $trimsPaginated = $trimsQuery->paginate(20);

        // تجميع الفئات للعرض المنظم في Accordion
        $groupedTrims = $allTrims->groupBy(function ($trim) {
            return $trim->brand->name ?? 'غير معروف';
        })->map(function ($brandTrims) {
            return $brandTrims->groupBy(function ($trim) {
                return $trim->type->name ?? 'غير معروف';
            });
        });

        $brands = CarBrand::orderBy('name')->get();
        $types = CarType::when($brandId, function ($query) use ($brandId) {
                return $query->where('car_brand_id', $brandId);
            })
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => CarTrim::count(),
            'brands' => CarBrand::count(),
            'types' => CarType::count(),
            'with_cars' => CarTrim::has('cars')->count(),
        ];

        return view('admin.car-trims.index', compact(
            'trimsPaginated',
            'groupedTrims',
            'search',
            'brands',
            'types',
            'brandId',
            'typeId',
            'stats'
        ));
    }

    public function create(Request $request)
    {
        $brandId = $request->get('brand_id');
        $typeId = $request->get('type_id');
        
        $brands = CarBrand::orderBy('name')->get();
        
        // جلب الأنواع فقط إذا تم اختيار براند
        $types = $brandId ? CarType::where('car_brand_id', $brandId)->orderBy('name')->get() : collect();
        
        return view('admin.car-trims.create', compact('brands', 'types', 'brandId', 'typeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|unique:car_trims|max:50',
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_type_id' => 'required|exists:car_types,id',
        ]);

        CarTrim::create([
            'name' => $request->name,
            'code' => $request->code,
            'car_brand_id' => $request->car_brand_id,
            'car_type_id' => $request->car_type_id,
        ]);

        return redirect()->route('admin.car-trims.index')
            ->with('success', 'تمت إضافة الفئة بنجاح.');
    }

    // دالة AJAX لجلب الأنواع بناءً على البراند
    public function getTypesByBrand($brandId)
    {
        $types = CarType::where('car_brand_id', $brandId)
            ->orderBy('name')
            ->get()
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                ];
            });

        return response()->json($types);
    }

    public function show($id)
    {
        $trim = CarTrim::with(['brand', 'type', 'cars'])->findOrFail($id);
        return view('admin.car-trims.show', compact('trim'));
    }

    public function edit($id)
    {
        $trim = CarTrim::with(['brand', 'type'])->findOrFail($id);
        $brands = CarBrand::orderBy('name')->get();
        $types = CarType::where('car_brand_id', $trim->car_brand_id)->orderBy('name')->get();
        
        return view('admin.car-trims.edit', compact('trim', 'brands', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|unique:car_trims,code,' . $id . '|max:50',
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_type_id' => 'required|exists:car_types,id',
        ]);

        $trim = CarTrim::findOrFail($id);
        $trim->update([
            'name' => $request->name,
            'code' => $request->code,
            'car_brand_id' => $request->car_brand_id,
            'car_type_id' => $request->car_type_id,
        ]);

        return redirect()->route('admin.car-trims.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    public function destroy($id)
    {
        $trim = CarTrim::withCount('cars')->findOrFail($id);
        
        if ($trim->cars_count > 0) {
            return redirect()->route('admin.car-trims.index')
                ->with('error', 'لا يمكن حذف الفئة لأن لها سيارات مرتبطة.');
        }

        $trim->delete();

        return redirect()->route('admin.car-trims.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}