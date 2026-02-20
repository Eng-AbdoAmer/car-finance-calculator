<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\CarTrim;
use App\Models\CarCategory;
use App\Models\CarModel;
use App\Models\CarStatus;
use App\Models\TransmissionType;
use App\Models\FuelType;
use App\Models\DriveType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarController extends Controller
{
    //الحمدلله الذي بنعمته تتم الصالحات، والصلاة والسلام على أشرف الأنبياء والمرسلين، نبينا محمد وعلى آله وصحبه أجمعين.
public function index(Request $request)
{
    // تحرير الحجوزات المنتهية
    $reservedCars = Car::where('availability', 'reserved')->get();
    foreach ($reservedCars as $car) {
        if (!cache()->has("car_reserved_{$car->id}")) {
            $car->update([
                'availability' => 'available',
                'reserved_by' => null,
            ]);
        }
    }

    $search = $request->get('search');
    $brandId = $request->get('brand_id');
    $statusId = $request->get('status_id');
    $condition = $request->get('condition');
    $availability = $request->get('availability');

    $cars = Car::with(['brand', 'type', 'status', 'mainImage'])
        ->when($search, function ($q) use ($search) {
            return $q->where(function ($q2) use ($search) {
                $q2->where('code', 'like', "%{$search}%")
                    ->orWhere('chassis_number', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn($q3) => $q3->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('type', fn($q3) => $q3->where('name', 'like', "%{$search}%"));
            });
        })
        ->when($brandId, fn($q) => $q->where('car_brand_id', $brandId))
        ->when($statusId, fn($q) => $q->where('car_status_id', $statusId))
        ->when($condition, fn($q) => $q->where('condition', $condition))
        ->when($availability, 
            function ($q) use ($availability) {
                return $q->where('availability', $availability); // إذا اختار المستخدم قيمة محددة (available, reserved, sold)
            },
            function ($q) {
                return $q->where('availability', '!=', 'sold'); // إذا لم يختر شيئاً، استبعد المباعة
            }
        )
        ->orderBy('id', 'desc')
        ->paginate(15);

    $brands = CarBrand::all();
    $statuses = CarStatus::orderBy('order')->get();
    $conditions = ['new' => 'جديدة', 'used' => 'مستعملة', 'salvage' => 'تشليح', 'refurbished' => 'مجددة'];
    $availabilities = [
        'available' => 'متاحة للبيع',
        'reserved'  => 'محجوزة',
        'sold'      => 'مباعة'
    ];

    $stats = [
        'total'     => Car::count(),
        'available' => Car::where('availability', 'available')->count(),
        'sold'      => Car::where('availability', 'sold')->count(),
        'reserved'  => Car::where('availability', 'reserved')->count(),
    ];

    return view('admin.cars.index', compact(
        'cars', 'search', 'brands', 'statuses', 'conditions', 'availabilities',
        'brandId', 'statusId', 'condition', 'availability', 'stats'
    ));
}

    public function create()
    {
        $years = CarModel::whereNotNull('model_year')  
                ->distinct()
                ->orderBy('model_year', 'desc')
                ->pluck('model_year')
                ->toArray();
        $brands = CarBrand::all();
        $types  = CarType::all();
        $trims  = CarTrim::all();
        $categories = CarCategory::all();
        $statuses   = CarStatus::orderBy('order')->get();
        $transmissions = TransmissionType::all();
        $fuelTypes     = FuelType::all();
        $driveTypes    = DriveType::all();
        $conditions    = ['new' => 'جديدة', 'used' => 'مستعملة', 'salvage' => 'تشليح', 'refurbished' => 'مجددة'];

        $defaultCode = Car::generateCode();

        return view('admin.cars.create', compact(
            'years', 'brands', 'types', 'trims', 'categories', 'statuses',
            'transmissions', 'fuelTypes', 'driveTypes', 'conditions', 'defaultCode'
        ));
    }

    public function store(StoreCarRequest $request)
    {
        $validated = $request->validated();

        // إضافة حقول إضافية
        $validated['code'] = $validated['code'] ?? Car::generateCode();
        $validated['view_count'] = 0;
        $validated['inquiry_count'] = 0;

        $car = Car::create($validated);

        // رفع الصور
        $this->uploadImages($request, $car);

        // تسجيل تاريخ الأسعار
        $car->priceHistory()->create([
            'price_type' => 'purchase',
            'old_price'  => 0,
            'new_price'  => $car->purchase_price,
            'reason'     => 'سعر الشراء عند الإضافة',
            'changed_by' => Auth::id(),
        ]);

        $car->priceHistory()->create([
            'price_type' => 'selling',
            'old_price'  => 0,
            'new_price'  => $car->selling_price,
            'reason'     => 'سعر البيع عند الإضافة',
            'changed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.cars.show', $car->id)
            ->with('success', 'تمت إضافة السيارة بنجاح.');
    }

    public function show($id)
    {
        $car = Car::with([
            'brand', 'type', 'trim', 'category', 'status',
            'transmission', 'fuelType', 'driveType',
            'images', 'maintenanceRecords',
            'priceHistory' => fn($q) => $q->latest()->limit(10),
            'soldBy', 'reservedBy'   // إظهار المستخدمين المسؤولين
        ])->findOrFail($id);

        $car->increment('view_count');

        return view('admin.cars.show', compact('car'));
    }

    public function edit($id)
    {

        $car = Car::with([
            'images', 'brand', 'type', 'trim', 'category', 'status',
            'transmission', 'fuelType', 'driveType'
            ])->findOrFail($id);
            $brands = CarBrand::orderBy('name')->get();
            $types = CarType::orderBy('name')->get();
            $trims = CarTrim::orderBy('name')->get();
            $categories = CarCategory::orderBy('name')->get();
            $statuses = CarStatus::orderBy('order')->get();
            $transmissions = TransmissionType::orderBy('name')->get();
            $fuelTypes = FuelType::orderBy('name')->get();
            $driveTypes = DriveType::orderBy('name')->get();
            $conditions = ['new' => 'جديدة', 'used' => 'مستعملة', 'salvage' => 'تشليح', 'refurbished' => 'مجددة'];
            
        
        return view('admin.cars.edit', compact(
            'car', 'brands', 'types', 'trims', 'categories', 'statuses',
            'transmissions', 'fuelTypes', 'driveTypes', 'conditions'
        ));
    }

// public function update(Request $request, Car $car)
// {
//     dd(Auth::user()); // تأكد من ظهور بيانات المستخدم
//     // باقي الكود
// }

public function update(Request $request, $id)
{
    $car = Car::findOrFail($id);
    $oldSellingPrice = $car->selling_price;

    // التحقق من البيانات (إذا كنت تستخدم Form Request)
    // $validated = $request->validated(); 

    // إذا كنت تستخدم Request عادي، قم بالتحقق يدوياً
    $validated = $request->all(); // تأكد من تطبيق قواعد التحقق

    // معالجة التواريخ
    $validated['purchase_date'] = $request->filled('purchase_date') ? $request->purchase_date : null;
    $validated['entry_date']    = $request->filled('entry_date') ? $request->entry_date : null;

    // معالجة checkboxes
    $checkboxes = ['is_featured', 'is_negotiable', 'is_financeable', 'has_ownership', 'has_insurance', 'has_registration', 'has_maintenance_record'];
    foreach ($checkboxes as $checkbox) {
        $validated[$checkbox] = $request->has($checkbox);
    }

    // تحديث السيارة
    $car->update($validated);

    // تسجيل تغيير السعر
    if ($oldSellingPrice != $car->selling_price) {
        $car->priceHistory()->create([
            'price_type' => 'selling',
            'old_price'  => $oldSellingPrice,
            'new_price'  => $car->selling_price,
            'reason'     => $request->input('price_change_reason', 'تحديث السعر'),
            'changed_by' => Auth::id(),
        ]);
    }

    // رفع الصور الجديدة
    $this->uploadImages($request, $car);

    return redirect()->route('admin.cars.show', $car->id)
        ->with('success', 'تم تحديث بيانات السيارة بنجاح.');
}
    // ============ دوال البيع والحجز ============

    public function markAsSold($id)
    {
        $car = Car::findOrFail($id);
        $statuses = CarStatus::orderBy('order')->get();
        return view('admin.cars.sell', compact('car', 'statuses'));
    }

    public function processSale(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $request->validate([
            'sold_price'     => 'required|numeric|min:0',
            'sale_date'      => 'required|date',
            'buyer_name'     => 'required|string|max:255',
            'buyer_phone'    => 'required|string|max:20',
            'buyer_email'    => 'nullable|email|max:255',
            'buyer_address'  => 'nullable|string|max:500',
            'notes'          => 'nullable|string',
            'car_status_id'  => 'required|exists:car_statuses,id',
        ]);

        $car->update([
            'availability'   => 'sold',
            'sold_price'     => $request->sold_price,
            'sale_date'      => $request->sale_date,
            'car_status_id'  => $request->car_status_id,
            'sold_by'        => Auth::id(),   // تسجيل المستخدم الذي قام بالبيع
        ]);

        // تسجيل في تاريخ الأسعار
        $car->priceHistory()->create([
            'price_type' => 'sold',
            'old_price'  => $car->selling_price,
            'new_price'  => $request->sold_price,
            'reason'     => 'تم بيع السيارة',
            'changed_by' => Auth::id(),
        ]);

        // يمكنك هنا إنشاء سجل للمشتري في جدول منفصل إذا أردت

        return redirect()->route('admin.cars.show', $car->id)
            ->with('success', 'تم تسجيل بيع السيارة بنجاح.');
    }

    // public function markAsReserved($id)
    // {
    //     $car = Car::findOrFail($id);
    //     $car->update([
    //         'availability' => 'reserved',
    //         'reserved_by'  => Auth::id(),
    //     ]);

    //     return redirect()->back()->with('success', 'تم حجز السيارة بنجاح.');
    // }

    // public function markAsAvailable($id)
    // {
    //     $car = Car::findOrFail($id);
    //     $car->update([
    //         'availability' => 'available',
    //         'reserved_by'  => null,   // إلغاء الحجز
    //     ]);

    //     return redirect()->back()->with('success', 'تم إتاحة السيارة مرة أخرى.');
    // }

    // ============ دوال مساعدة للصور ============

    private function uploadImages($request, $car)
    {
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            if ($mainImage->isValid()) {
                // حذف القديمة إن وجدت
                $oldMain = $car->images()->where('is_main', true)->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->image_path);
                    $oldMain->delete();
                }

                $path = $mainImage->store("cars/{$car->id}", 'public');
                $car->images()->create([
                    'image_path' => $path,
                    'image_type' => 'exterior',
                    'is_main'    => true,
                    'order'      => 1,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            $order = $car->images()->max('order') ?? 1;
            foreach ($request->file('images') as $image) {
                if (!$image->isValid()) continue;

                $path = $image->store("cars/{$car->id}", 'public');
                $car->images()->create([
                    'image_path' => $path,
                    'image_type' => 'exterior',
                    'is_main'    => false,
                    'order'      => ++$order,
                ]);
            }
        }
    }

    public function setMainImage($carId, $imageId)
    {
        $car = Car::findOrFail($carId);
        $image = $car->images()->findOrFail($imageId);
        $car->images()->update(['is_main' => false]);
        $image->update(['is_main' => true]);

        return redirect()->route('admin.cars.show', $car->id)
            ->with('success', 'تم تعيين الصورة كرئيسية.');
    }

    public function deleteImage($carId, $imageId)
    {
        $car = Car::findOrFail($carId);
        $image = $car->images()->findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->route('admin.cars.show', $car->id)
            ->with('success', 'تم حذف الصورة.');
    }

    public function destroy($id)
{
    $car = Car::findOrFail($id);
    
    // حذف ملفات الصور من التخزين
    foreach ($car->images as $image) {
        Storage::disk('public')->delete($image->image_path);
    }
    
    // حذف السيارة (سيتم حذف سجلات الصور تلقائياً بسبب cascade)
    $car->delete();

    return redirect()->route('admin.cars.index')
        ->with('success', 'تم حذف السيارة وجميع الصور المرتبطة بها بنجاح.');
}


public function markAsReserved($id)
{
    $car = Car::findOrFail($id);
    
    $expiresAt =  now()->addHours(48);
    cache()->put("car_reserved_{$car->id}", $expiresAt, $expiresAt);
    
    $car->update([
        'availability' => 'reserved',
        'reserved_by'  => Auth::id(),
    ]);

    if (request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'تم حجز السيارة لمدة 48 ساعة',
            'expires_at' => $expiresAt->timestamp * 1000 //  ثانية
        ]);
    }

    return redirect()->back()->with('success', 'تم حجز السيارة لمدة دقيقة.');
}

public function markAsAvailable($id)
{
    $car = Car::findOrFail($id);
    
    $car->update([
        'availability' => 'available',
        'reserved_by'  => null,
    ]);
    cache()->forget("car_reserved_{$car->id}");

    if (request()->wantsJson()) {
        return response()->json(['success' => true]);
    }

    return redirect()->back()->with('success', 'تم إتاحة السيارة مرة أخرى.');
}

public function autoRelease($id)
{
    $car = Car::findOrFail($id);
    
    if ($car->availability === 'reserved') {
        $car->update([
            'availability' => 'available',
            'reserved_by'  => null,
        ]);
        cache()->forget("car_reserved_{$car->id}");
        
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false, 'message' => 'السيارة لم تعد محجوزة']);
}

public function getStats()
{
    $stats = [
        'total'     => Car::count(),
        'available' => Car::where('availability', 'available')->count(),
        'sold'      => Car::where('availability', 'sold')->count(),
        'reserved'  => Car::where('availability', 'reserved')->count(),
    ];
    return response()->json($stats);
}
public function print($id)
{
    $car = Car::with([
        'brand', 'type', 'trim', 'category', 'status',
        'transmission', 'fuelType', 'driveType',
        'mainImage' // العلاقة الخاصة بالصورة الرئيسية (إذا كانت معرفة)
    ])->findOrFail($id);

    return view('admin.cars.print', compact('car'));
}
}