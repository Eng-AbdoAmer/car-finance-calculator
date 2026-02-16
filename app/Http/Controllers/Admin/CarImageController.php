<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarImageController extends Controller
{
    
     public function index(Request $request)
    {
        $carId = $request->get('car_id');
        $imageType = $request->get('image_type');
        
        $images = CarImage::with(['car' => function($query) {
                $query->with(['brand', 'model']);
            }])
            ->when($carId, function ($query) use ($carId) {
                return $query->where('car_id', $carId);
            })
            ->when($imageType, function ($query) use ($imageType) {
                return $query->where('image_type', $imageType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // تحميل السيارات مع العلاقات
        $cars = Car::with(['brand', 'model'])
            ->orderBy('model_year', 'desc')
            ->get();

        return view('admin.car-images.index', compact('images', 'cars', 'carId'));
    }

    public function create()
    {
        // تحميل السيارات مع العلاقات بشكل صحيح
        $cars = Car::with(['brand', 'model'])
            ->orderBy('model_year', 'desc')
            ->get();

        return view('admin.car-images.create', compact('cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_type' => 'required|in:exterior,interior,engine,document',
            'description' => 'nullable|string|max:500',
        ]);

        $car = Car::with('brand')->findOrFail($request->car_id);
        
        // حساب الترتيب التالي
        $order = $car->images()->max('order') ?? 0;

        // حفظ الصورة
        $path = $request->file('image_path')->store('cars/' . $car->id, 'public');

        // إنشاء سجل الصورة
        $image = CarImage::create([
            'car_id' => $request->car_id,
            'image_path' => $path,
            'image_type' => $request->image_type,
            'is_main' => $car->images()->count() === 0, // إذا كانت أول صورة تكون رئيسية
            'order' => $order + 1,
            'description' => $request->description,
        ]);

        if ($request->has('add_more')) {
            return redirect()->route('admin.car-images.create')
                ->with('success', 'تمت إضافة الصورة بنجاح. يمكنك إضافة المزيد.')
                ->withInput(['car_id' => $request->car_id]);
        }

        return redirect()->route('admin.car-images.index', ['car_id' => $request->car_id])
            ->with('success', 'تمت إضافة الصورة بنجاح.');
    }
    public function show(CarImage $carImage)
    {
        $carImage->load(['car' => function($query) {
            $query->with(['brand', 'model']);
        }]);
        
        return view('admin.car-images.show', compact('carImage'));
    }

    public function edit(CarImage $carImage)
    {
        $carImage->load(['car' => function($query) {
            $query->with(['brand', 'model']);
        }]);
        
        return view('admin.car-images.edit', compact('carImage'));
    }

    public function update(Request $request, CarImage $carImage)
    {
        $request->validate([
            'image_type' => 'required|in:exterior,interior,engine,document',
            'description' => 'nullable|string|max:500',
            'order' => 'required|integer|min:1',
        ]);

        $carImage->update($request->only(['image_type', 'description', 'order']));

        return redirect()->route('admin.car-images.show', $carImage->id)
            ->with('success', 'تم تحديث بيانات الصورة بنجاح.');
    }

    public function destroy(CarImage $carImage)
    {
        $carId = $carImage->car_id;
        
        try {
            // حذف الملف من التخزين
            Storage::disk('public')->delete($carImage->image_path);
            
            // حذف السجل من قاعدة البيانات
            $carImage->delete();
            
            // إذا كانت الصورة المحذوفة هي الرئيسية، نعين صورة أخرى كرئيسية
            if ($carImage->is_main) {
                $newMain = CarImage::where('car_id', $carId)->first();
                if ($newMain) {
                    $newMain->update(['is_main' => true]);
                }
            }
            
            return redirect()->route('admin.car-images.index', ['car_id' => $carId])
                ->with('success', 'تم حذف الصورة بنجاح.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage());
        }
    }

    public function setMain(CarImage $carImage)
    {
        // إلغاء الصورة الرئيسية الحالية
        CarImage::where('car_id', $carImage->car_id)
                ->where('is_main', true)
                ->update(['is_main' => false]);

        // تعيين الصورة الجديدة كرئيسية
        $carImage->update(['is_main' => true]);

        return redirect()->back()
            ->with('success', 'تم تعيين الصورة كرئيسية بنجاح.');
    }
}