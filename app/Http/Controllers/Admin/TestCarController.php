<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarTrim;
use App\Models\CarCategory;
use App\Models\CarStatus;
use App\Models\CarType;
use App\Models\TransmissionType;
use App\Models\FuelType;
use App\Models\DriveType;
use App\Models\CarPriceHistory;
use App\Models\CarImage;
use App\Models\CarMaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TestCarController extends Controller
{
    public function index(Request $request)
    {
        // البحث والتصفية
        $search = $request->get('search');
        $brandId = $request->get('brand_id');
        $statusId = $request->get('status_id');
        $condition = $request->get('condition');
        
        $cars = Car::with(['brand', 'model', 'status', 'mainImage'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('chassis_number', 'like', "%{$search}%")
                      ->orWhere('plate_number', 'like', "%{$search}%")
                      ->orWhere('color', 'like', "%{$search}%")
                      ->orWhereHas('brand', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('model', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($brandId, function ($query) use ($brandId) {
                return $query->where('car_brand_id', $brandId);
            })
            ->when($statusId, function ($query) use ($statusId) {
                return $query->where('car_status_id', $statusId);
            })
            ->when($condition, function ($query) use ($condition) {
                return $query->where('condition', $condition);
            })
            ->when($request->get('min_price'), function ($query) use ($request) {
                return $query->where('selling_price', '>=', $request->min_price);
            })
            ->when($request->get('max_price'), function ($query) use ($request) {
                return $query->where('selling_price', '<=', $request->max_price);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // بيانات التصفية
        $brands = CarBrand::orderBy('name')->get();
        $statuses = CarStatus::orderBy('order')->get();
        $conditions = [
            'new' => 'جديدة',
            'used' => 'مستعملة',
            'salvage' => 'تشليح',
            'refurbished' => 'مجددة',
        ];

        // إحصائيات
        $stats = [
            'total' => Car::count(),
            'available' => Car::where('availability', 'available')->count(),
            'sold' => Car::where('availability', 'sold')->count(),
            'reserved' => Car::where('availability', 'reserved')->count(),
            'new' => Car::where('condition', 'new')->count(),
            'used' => Car::where('condition', 'used')->count(),
        ];

        return view('admin.cars.index', compact(
            'cars', 'search', 'brands', 'statuses', 'conditions', 'stats',
            'brandId', 'statusId', 'condition'
        ));
    }

    public function create()
    {
        $brands = CarBrand::orderBy('name')->get();
        $types = collect();
        $trims = collect();
        $categories = CarCategory::orderBy('name')->get();
        $statuses = CarStatus::orderBy('order')->get();
        $transmissions = TransmissionType::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();
        
        $conditions = [
            'new' => 'جديدة',
            'used' => 'مستعملة',
            'salvage' => 'تشليح',
            'refurbished' => 'مجددة',
        ];

        // توليد كود تلقائي
        $defaultCode = Car::generateCode();

        return view('admin.cars.create', compact(
            'brands', 'types', 'trims', 'categories', 'statuses',
            'transmissions', 'fuelTypes', 'driveTypes', 'conditions',
            'defaultCode'
        ));
    }

    public function getTypesByBrand($brandId)
    {
        try {
            $types = CarType::where('car_brand_id', $brandId)->orderBy('name')->get();
            return response()->json($types);
        } catch (\Exception $e) {
            Log::error('Error in getTypesByBrand: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getTrimsByType($brandId, $typeId)
    {
        try {
            $trims = CarTrim::where('car_brand_id', $brandId)
                ->where('car_type_id', $typeId)
                ->orderBy('name')
                ->get();
            return response()->json($trims);
        } catch (\Exception $e) {
            Log::error('Error in getTrimsByType: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

public function store(Request $request)
{
    Log::info('================= START CAR STORE =================');
    Log::info('Request Data:', $request->except(['_token']));
    
    // التحقق من أن الطلب هو AJAX
    $isAjax = $request->ajax() || $request->wantsJson();
    
    try {
        // القواعد الأساسية
        $validator = Validator::make($request->all(), [
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_type_id' => 'required|exists:car_types,id',
            'color' => 'required|string|max:50',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'condition' => 'required|in:new,used,salvage,refurbished',
            'mileage' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'required|string|min:10',
            'entry_date' => 'required|date',
            'car_status_id' => 'required|exists:car_statuses,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
        ], [
            'car_brand_id.required' => 'العلامة التجارية مطلوبة',
            'car_type_id.required' => 'نوع السيارة (الموديل) مطلوب',
            'model_year.required' => 'سنة الموديل مطلوبة',
            'color.required' => 'لون السيارة مطلوب',
            'condition.required' => 'حالة السيارة مطلوبة',
            'mileage.required' => 'الممشي مطلوب',
            'purchase_price.required' => 'سعر الشراء مطلوب',
            'selling_price.required' => 'سعر البيع مطلوب',
            'description.required' => 'وصف السيارة مطلوب',
            'entry_date.required' => 'تاريخ الدخول للمعرض مطلوب',
            'car_status_id.required' => 'حالة السيارة مطلوبة',
            'transmission_type_id.required' => 'ناقل الحركة مطلوب',
            'fuel_type_id.required' => 'نوع الوقود مطلوب',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            
            // إذا كان الطلب AJAX، نرجع JSON
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى تصحيح الأخطاء',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'يرجى تصحيح الأخطاء: ' . implode(', ', $validator->errors()->all()));
        }

        $validated = $validator->validated();
        Log::info('Validation passed. Validated data:', $validated);
        
        // **التحويل المهم: car_type_id إلى car_model_id**
        $validated['car_model_id'] = $validated['car_type_id'];
        unset($validated['car_type_id']);
        
        // تحويل القيم المنطقية
        $validated['is_negotiable'] = $request->has('is_negotiable') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_financeable'] = $request->has('is_financeable') ? 1 : 0;
        $validated['has_ownership'] = $request->has('has_ownership') ? 1 : 0;
        $validated['has_insurance'] = $request->has('has_insurance') ? 1 : 0;
        $validated['has_registration'] = $request->has('has_registration') ? 1 : 0;
        $validated['has_maintenance_record'] = $request->has('has_maintenance_record') ? 1 : 0;
        
        // توليد الكود التلقائي
        $validated['code'] = Car::generateCode();
        Log::info('Generated code: ' . $validated['code']);
        
        // إضافة القيم الإضافية
        $validated['availability'] = 'available';
        $validated['view_count'] = 0;
        $validated['inquiry_count'] = 0;
        
        // التعامل مع الحقول الاختيارية
        $optionalFields = [
            'car_trim_id', 'car_category_id', 'drive_type_id', 'minimum_price', 'cost_price',
            'engine_capacity', 'horse_power', 'cylinders', 'doors', 'seats', 'manufacturing_year',
            'country_of_origin', 'import_from', 'purchase_date', 'inspection_date', 'registration_date',
            'chassis_number', 'plate_number', 'notes'
        ];
        
        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $validated[$field] = $request->$field;
            } else {
                $validated[$field] = null;
            }
        }
        
        // التعامل مع المميزات والمواصفات
        if ($request->filled('features')) {
            $featuresText = $request->features;
            $featuresArray = array_filter(array_map('trim', explode("\n", $featuresText)));
            $validated['features'] = json_encode($featuresArray, JSON_UNESCAPED_UNICODE);
        } else {
            $validated['features'] = null;
        }
        
        if ($request->filled('specifications')) {
            $specsText = trim($request->specifications);
            try {
                // محاولة تحليل كـ JSON
                $specsJson = json_decode($specsText, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated['specifications'] = json_encode($specsJson, JSON_UNESCAPED_UNICODE);
                } else {
                    // إذا لم يكن JSON صالحاً، احفظه كمصفوفة
                    $specsArray = array_filter(array_map('trim', explode("\n", $specsText)));
                    $validated['specifications'] = json_encode($specsArray, JSON_UNESCAPED_UNICODE);
                }
            } catch (\Exception $e) {
                $validated['specifications'] = null;
            }
        } else {
            $validated['specifications'] = null;
        }
        
        Log::info('Final data before DB transaction:', $validated);
        
        // بداية المعاملة
        DB::beginTransaction();
        
        try {
            Log::info('Creating car in database...');
            
            // إزالة الحقول غير الموجودة في قاعدة البيانات
            unset($validated['image_type']);
            unset($validated['image_description']);
            
            $car = Car::create($validated);
            Log::info('Car created successfully. ID: ' . $car->id);
            
            // معالجة الصور الأساسية
            if ($request->hasFile('main_image')) {
                Log::info('Processing main image...');
                $path = $request->file('main_image')->store('cars/' . $car->id, 'public');
                $car->images()->create([
                    'image_path' => $path,
                    'image_type' => 'exterior',
                    'is_main' => true,
                    'order' => 1,
                ]);
                Log::info('Main image saved: ' . $path);
            }
            
            // معالجة الصور المؤقتة (السحب والإفلات)
            if ($request->filled('uploaded_images')) {
                Log::info('Processing uploaded images...');
                $uploadedImages = json_decode($request->uploaded_images, true);
                
                if (is_array($uploadedImages) && count($uploadedImages) > 0) {
                    $order = $car->images()->count() + 1;
                    $isFirstImage = $car->images()->count() === 0;
                    $imageType = $request->get('image_type', 'exterior');
                    $imageDescription = $request->get('image_description', '');
                    
                    foreach ($uploadedImages as $index => $imageData) {
                        if (isset($imageData['path']) && Storage::disk('public')->exists($imageData['path'])) {
                            $tempPath = $imageData['path'];
                            $fileName = basename($tempPath);
                            $newPath = 'cars/' . $car->id . '/' . $fileName;
                            
                            Log::info("Processing image: {$tempPath} -> {$newPath}");
                            
                            // نسخ الملف من temp إلى cars/id
                            if (Storage::disk('public')->exists($tempPath)) {
                                Storage::disk('public')->copy($tempPath, $newPath);
                                
                                // حذف الملف المؤقت
                                Storage::disk('public')->delete($tempPath);
                                
                                // إنشاء سجل الصورة
                                $car->images()->create([
                                    'image_path' => $newPath,
                                    'image_type' => $imageType,
                                    'is_main' => $isFirstImage && $index === 0,
                                    'order' => $order,
                                    'description' => $imageDescription,
                                ]);
                                
                                $order++;
                                Log::info('Uploaded image saved: ' . $newPath);
                            } else {
                                Log::warning('Temp image not found: ' . $tempPath);
                            }
                        }
                    }
                }
            }
            
            // إنشاء سجل الأسعار
            $car->priceHistory()->create([
                'price_type' => 'purchase',
                'old_price' => null,
                'new_price' => $car->purchase_price,
                'reason' => 'سعر الشراء الأولي',
                'changed_by' => Auth::id(),
            ]);
            
            $car->priceHistory()->create([
                'price_type' => 'selling',
                'old_price' => null,
                'new_price' => $car->selling_price,
                'reason' => 'سعر البيع الأولي',
                'changed_by' => Auth::id(),
            ]);
            
            DB::commit();
            Log::info('Transaction committed successfully');
            
            Log::info('================= CAR CREATED SUCCESSFULLY =================');
            
            // إذا كان الطلب AJAX، نرجع JSON مع redirect
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'تمت إضافة السيارة بنجاح.',
                    'redirect' => route('admin.cars.show', $car->id)
                ]);
            }
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تمت إضافة السيارة بنجاح.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database transaction failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // إذا كان الطلب AJAX، نرجع JSON
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ السيارة: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
        
    } catch (\Exception $e) {
        Log::error('Error in store method: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // إذا كان الطلب AJAX، نرجع JSON
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ السيارة: ' . $e->getMessage()
            ], 500);
        }
        
        return back()
            ->withInput()
            ->with('error', 'حدث خطأ أثناء حفظ السيارة: ' . $e->getMessage());
    }
}

    public function uploadTempImage(Request $request)
    {
        try {
            Log::info('========= START TEMP IMAGE UPLOAD =========');
            
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            
            if ($validator->fails()) {
                Log::error('Image validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في الصورة: ' . implode(', ', $validator->errors()->all())
                ], 400);
            }
            
            // تأكد من وجود مجلد temp في public storage
            $tempPath = 'temp';
            if (!Storage::disk('public')->exists($tempPath)) {
                Storage::disk('public')->makeDirectory($tempPath);
                Log::info('Created temp directory');
            }
            
            // حفظ الصورة في التخزين المؤقت
            $path = $request->file('image')->store($tempPath, 'public');
            
            // إنشاء URL للصورة
            $url = asset('storage/' . $path);
            
            Log::info('Temp image uploaded successfully', [
                'path' => $path,
                'url' => $url
            ]);
            
            Log::info('========= END TEMP IMAGE UPLOAD =========');
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'mime_type' => $request->file('image')->getMimeType(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading temp image: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTempImage(Request $request)
    {
        try {
            $path = $request->get('path');
            Log::info('Deleting temp image:', ['path' => $path]);
            
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('Temp image deleted successfully');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة المؤقتة'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting temp image: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة'
            ], 500);
        }
    }

    public function show($id)
    {
        $car = Car::with([
            'brand', 
            'model', 
            'trim', 
            'category', 
            'status',
            'transmissionType', 
            'fuelType', 
            'driveType',
            'images', 
            'priceHistory' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            },
            'maintenanceRecords' => function ($query) {
                $query->orderBy('maintenance_date', 'desc');
            }
        ])->findOrFail($id);

        // زيادة عدد المشاهدات
        $car->increment('view_count');

        return view('admin.cars.show', compact('car'));
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
    
        $brands = CarBrand::all();
        $models = CarType::where('car_brand_id', $car->car_brand_id)->get();
        $trims = CarTrim::where('car_type_id', $car->car_model_id)->get();
        $categories = CarCategory::all();
        $statuses = CarStatus::orderBy('order')->get();
        $transmissions = TransmissionType::all();
        $fuelTypes = FuelType::all();
        $driveTypes = DriveType::all();
        $conditions = [
            'new' => 'جديدة',
            'used' => 'مستعملة',
            'salvage' => 'تشليح',
            'refurbished' => 'مجددة',
        ];

        // تحويل JSON إلى نص للمميزات والمواصفات
        $car->features_text = '';
        if ($car->features) {
            $featuresArray = json_decode($car->features, true);
            if (is_array($featuresArray)) {
                $car->features_text = implode("\n", $featuresArray);
            }
        }
        
        $car->specifications_text = '';
        if ($car->specifications) {
            $specsArray = json_decode($car->specifications, true);
            if (is_array($specsArray)) {
                // إذا كانت المصفوفة تحتوي على مفاتيح وقيم
                if (array_keys($specsArray) !== range(0, count($specsArray) - 1)) {
                    $car->specifications_text = json_encode($specsArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    $car->specifications_text = implode("\n", $specsArray);
                }
            }
        }

        return view('admin.cars.edit', compact(
            'car', 'brands', 'models', 'trims', 'categories', 'statuses',
            'transmissions', 'fuelTypes', 'driveTypes', 'conditions'
        ));
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        
        Log::info('================= START CAR UPDATE =================');
        Log::info('Car ID: ' . $id);
        Log::info('Request Data:', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'code' => [
                    'required',
                    'max:50',
                    Rule::unique('cars')->ignore($id)
                ],
                'car_brand_id' => 'required|exists:car_brands,id',
                'car_model_id' => 'required|exists:car_types,id',
                'car_trim_id' => 'nullable|exists:car_trims,id',
                'car_category_id' => 'nullable|exists:car_categories,id',
                'car_status_id' => 'required|exists:car_statuses,id',
                'transmission_type_id' => 'required|exists:transmission_types,id',
                'fuel_type_id' => 'required|exists:fuel_types,id',
                'drive_type_id' => 'nullable|exists:drive_types,id',
                'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'manufacturing_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'color' => 'required|string|max:50',
                'condition' => 'required|in:new,used,salvage,refurbished',
                'mileage' => 'required|integer|min:0',
                'engine_capacity' => 'nullable|integer|min:0',
                'horse_power' => 'nullable|integer|min:0',
                'cylinders' => 'nullable|integer|min:0',
                'doors' => 'nullable|integer|min:2|max:10',
                'seats' => 'nullable|integer|min:2|max:20',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'minimum_price' => 'nullable|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'chassis_number' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('cars')->ignore($id)
                ],
                'plate_number' => [
                    'nullable',
                    'string',
                    'max:20',
                    Rule::unique('cars')->ignore($id)
                ],
                'description' => 'required|string|min:10',
                'notes' => 'nullable|string',
                'entry_date' => 'required|date',
                'purchase_date' => 'nullable|date',
                'inspection_date' => 'nullable|date',
                'registration_date' => 'nullable|date',
                'country_of_origin' => 'nullable|string|max:100',
                'import_from' => 'nullable|string|max:100',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'features' => 'nullable|string',
                'specifications' => 'nullable|string',
                'price_change_reason' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();
            
            Log::info('Validation Passed - Validated Data:', $validated);
            
            // تحديث الأسعار في السجل إذا تغيرت
            if ($car->selling_price != $validated['selling_price']) {
                $reason = $validated['price_change_reason'] ?? 'تحديث السعر';
                $car->priceHistory()->create([
                    'price_type' => 'selling',
                    'old_price' => $car->selling_price,
                    'new_price' => $validated['selling_price'],
                    'reason' => $reason,
                    'changed_by' => Auth::id(),
                ]);
                Log::info('Price updated from ' . $car->selling_price . ' to ' . $validated['selling_price'] . ' - Reason: ' . $reason);
            }
            
            // تحديث القيم المنطقية
            $validated['is_negotiable'] = $request->has('is_negotiable') ? 1 : 0;
            $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
            $validated['is_financeable'] = $request->has('is_financeable') ? 1 : 0;
            $validated['has_ownership'] = $request->has('has_ownership') ? 1 : 0;
            $validated['has_insurance'] = $request->has('has_insurance') ? 1 : 0;
            $validated['has_registration'] = $request->has('has_registration') ? 1 : 0;
            $validated['has_maintenance_record'] = $request->has('has_maintenance_record') ? 1 : 0;
            
            // تحويل المميزات من نص إلى JSON
            if (!empty($validated['features'])) {
                $featuresText = $validated['features'];
                $featuresArray = array_filter(array_map('trim', explode("\n", $featuresText)));
                $validated['features'] = json_encode($featuresArray, JSON_UNESCAPED_UNICODE);
            } else {
                $validated['features'] = null;
            }
            
            // تحويل المواصفات من نص إلى JSON
            if (!empty($validated['specifications'])) {
                $specsText = $validated['specifications'];
                try {
                    // محاولة تحليل كـ JSON
                    $specsJson = json_decode($specsText, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $validated['specifications'] = json_encode($specsJson, JSON_UNESCAPED_UNICODE);
                    } else {
                        // إذا لم يكن JSON صالحاً، احفظه كمصفوفة
                        $specsArray = array_filter(array_map('trim', explode("\n", $specsText)));
                        $validated['specifications'] = json_encode($specsArray, JSON_UNESCAPED_UNICODE);
                    }
                } catch (\Exception $e) {
                    $validated['specifications'] = null;
                }
            } else {
                $validated['specifications'] = null;
            }
            
            Log::info('Final data before update:', $validated);
            
            // تحديث السيارة
            $car->update($validated);
            Log::info('Car updated successfully');
            
            // تحديث الصورة الرئيسية
            if ($request->hasFile('main_image')) {
                Log::info('Main image file detected');
                
                // حذف الصورة الرئيسية القديمة
                $oldMainImage = $car->images()->where('is_main', true)->first();
                if ($oldMainImage) {
                    try {
                        Storage::disk('public')->delete($oldMainImage->image_path);
                        $oldMainImage->delete();
                        Log::info('Old main image deleted');
                    } catch (\Exception $e) {
                        Log::warning('Could not delete old main image: ' . $e->getMessage());
                    }
                }
                
                // حفظ الصورة الجديدة
                $path = $request->file('main_image')->store('cars/' . $car->id, 'public');
                $car->images()->create([
                    'image_path' => $path,
                    'image_type' => 'exterior',
                    'is_main' => true,
                    'order' => 1,
                ]);
                Log::info('New main image saved at: ' . $path);
            }
            
            Log::info('================= END CAR UPDATE =================');
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم تحديث بيانات السيارة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error updating car: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث السيارة: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);
            Log::info('Deleting car ID: ' . $id);
            
            // حذف الصور
            foreach ($car->images as $image) {
                try {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                } catch (\Exception $e) {
                    Log::warning('Could not delete image: ' . $e->getMessage());
                }
            }
            
            // حذف سجلات الأسعار
            $car->priceHistory()->delete();
            
            // حذف سجلات الصيانة
            $car->maintenanceRecords()->delete();
            
            // حذف السيارة
            $car->delete();
            
            Log::info('Car deleted successfully');
            
            return redirect()->route('admin.cars.index')
                ->with('success', 'تم حذف السيارة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting car: ' . $e->getMessage());
            return redirect()->route('admin.cars.index')
                ->with('error', 'حدث خطأ أثناء حذف السيارة: ' . $e->getMessage());
        }
    }

    public function markAsSold($id)
    {
        try {
            $car = Car::findOrFail($id);
            $statuses = CarStatus::all();
            
            return view('admin.cars.sell', compact('car', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('Error loading sell page: ' . $e->getMessage());
            return redirect()->route('admin.cars.show', $id)
                ->with('error', 'حدث خطأ أثناء تحميل صفحة البيع');
        }
    }

    public function processSale(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'sold_price' => 'required|numeric|min:0',
                'sale_date' => 'required|date',
                'buyer_name' => 'required|string|max:255',
                'buyer_phone' => 'required|string|max:20',
                'buyer_email' => 'nullable|email|max:255',
                'buyer_address' => 'nullable|string',
                'notes' => 'nullable|string',
                'car_status_id' => 'required|exists:car_statuses,id',
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            
            // تحديث حالة السيارة
            $car->update([
                'availability' => 'sold',
                'sold_price' => $validated['sold_price'],
                'sale_date' => $validated['sale_date'],
                'car_status_id' => $validated['car_status_id'],
            ]);
            
            // تسجيل سعر البيع في السجل
            $car->priceHistory()->create([
                'price_type' => 'sold',
                'old_price' => $car->selling_price,
                'new_price' => $validated['sold_price'],
                'reason' => 'تم بيع السيارة إلى ' . $validated['buyer_name'],
                'changed_by' => Auth::id(),
            ]);
            
            Log::info('Car sold: ID ' . $id . ' to ' . $validated['buyer_name'] . ' for ' . $validated['sold_price']);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم تسجيل بيع السيارة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error processing sale: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تسجيل البيع: ' . $e->getMessage());
        }
    }

    public function updatePrice(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'new_price' => 'required|numeric|min:0',
                'reason' => 'required|string|max:255',
                'price_type' => 'required|in:purchase,selling,minimum,cost',
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            $priceType = $validated['price_type'];
            $oldPrice = $car->{$priceType . '_price'};
            
            // تحديث السعر
            $car->update([$priceType . '_price' => $validated['new_price']]);
            
            // تسجيل التغيير في السجل
            $car->priceHistory()->create([
                'price_type' => $priceType,
                'old_price' => $oldPrice,
                'new_price' => $validated['new_price'],
                'reason' => $validated['reason'],
                'changed_by' => Auth::id(),
            ]);
            
            Log::info('Price updated for car ID ' . $id . ': ' . $priceType . ' price changed from ' . $oldPrice . ' to ' . $validated['new_price']);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم تحديث سعر السيارة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error updating price: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث السعر: ' . $e->getMessage());
        }
    }

    public function uploadImages(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'image_type' => 'required|in:exterior,interior,engine,document',
                'description' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            $order = $car->images()->max('order') ?? 0;
            $uploadedCount = 0;
            
            foreach ($request->file('images') as $image) {
                $order++;
                $path = $image->store('cars/' . $car->id, 'public');
                
                $car->images()->create([
                    'image_path' => $path,
                    'image_type' => $validated['image_type'],
                    'is_main' => false,
                    'order' => $order,
                    'description' => $validated['description'],
                ]);
                
                $uploadedCount++;
                Log::info('Image uploaded for car ID ' . $id . ': ' . $path);
            }
            
            Log::info($uploadedCount . ' images uploaded for car ID ' . $id);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم رفع ' . $uploadedCount . ' صورة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error uploading images: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage());
        }
    }

    public function setMainImage(Request $request, $carId, $imageId)
    {
        try {
            $car = Car::findOrFail($carId);
            $image = $car->images()->findOrFail($imageId);
            
            // إلغاء الصورة الرئيسية الحالية
            $car->images()->update(['is_main' => false]);
            
            // تعيين الصورة الجديدة كرئيسية
            $image->update(['is_main' => true]);
            
            Log::info('Main image set for car ID ' . $carId . ': image ID ' . $imageId);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم تعيين الصورة كرئيسية بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error setting main image: ' . $e->getMessage());
            return redirect()->route('admin.cars.show', $carId)
                ->with('error', 'حدث خطأ أثناء تعيين الصورة كرئيسية');
        }
    }

    public function deleteImage($carId, $imageId)
    {
        try {
            $car = Car::findOrFail($carId);
            $image = $car->images()->findOrFail($imageId);
            
            // حذف ملف الصورة
            Storage::disk('public')->delete($image->image_path);
            
            // حذف سجل الصورة
            $image->delete();
            
            Log::info('Image deleted: car ID ' . $carId . ', image ID ' . $imageId);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم حذف الصورة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return redirect()->route('admin.cars.show', $carId)
                ->with('error', 'حدث خطأ أثناء حذف الصورة');
        }
    }

    public function changeAvailability(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'availability' => 'required|in:available,reserved,sold,under_processing',
                'reason' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            $oldAvailability = $car->availability;
            
            $car->update(['availability' => $validated['availability']]);
            
            Log::info('Availability changed for car ID ' . $id . ': from ' . $oldAvailability . ' to ' . $validated['availability']);
            
            return redirect()->route('admin.cars.show', $car->id)
                ->with('success', 'تم تغيير حالة السيارة بنجاح.');
                
        } catch (\Exception $e) {
            Log::error('Error changing availability: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تغيير حالة السيارة: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $cars = Car::with(['brand', 'model', 'status'])
                ->when($request->get('brand_id'), function ($query) use ($request) {
                    return $query->where('car_brand_id', $request->brand_id);
                })
                ->when($request->get('status_id'), function ($query) use ($request) {
                    return $query->where('car_status_id', $request->status_id);
                })
                ->when($request->get('condition'), function ($query) use ($request) {
                    return $query->where('condition', $request->condition);
                })
                ->when($request->get('availability'), function ($query) use ($request) {
                    return $query->where('availability', $request->availability);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            return back()
                ->with('info', 'ميزة التصدير قيد التطوير');
                
        } catch (\Exception $e) {
            Log::error('Error exporting cars: ' . $e->getMessage());
            return back()
                ->with('error', 'حدث خطأ أثناء التصدير: ' . $e->getMessage());
        }
    }

    public function getCarDetails($id)
    {
        try {
            $car = Car::with([
                'brand', 
                'model', 
                'trim', 
                'category', 
                'status',
                'transmissionType', 
                'fuelType', 
                'driveType',
                'mainImage'
            ])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'car' => $car,
                'full_name' => $car->full_name,
                'condition_name' => $car->condition_name,
                'availability_name' => $car->availability_name,
                'profit' => $car->profit,
                'profit_percentage' => $car->profit_percentage,
                'days_in_stock' => $car->days_in_stock,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting car details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Car not found'
            ], 404);
        }
    }

    public function duplicate($id)
    {
        try {
            $originalCar = Car::findOrFail($id);
            
            // إنشاء نسخة جديدة من السيارة
            $newCar = $originalCar->replicate();
            $newCar->code = Car::generateCode();
            $newCar->chassis_number = null;
            $newCar->plate_number = null;
            $newCar->availability = 'available';
            $newCar->sold_price = null;
            $newCar->sale_date = null;
            $newCar->view_count = 0;
            $newCar->inquiry_count = 0;
            $newCar->created_at = now();
            $newCar->updated_at = now();
            $newCar->save();
            
            Log::info('Car duplicated: ID ' . $id . ' to new ID ' . $newCar->id);
            
            return redirect()->route('admin.cars.edit', $newCar->id)
                ->with('success', 'تم نسخ السيارة بنجاح. يمكنك الآن تعديل التفاصيل الجديدة.');
                
        } catch (\Exception $e) {
            Log::error('Error duplicating car: ' . $e->getMessage());
            return redirect()->route('admin.cars.show', $id)
                ->with('error', 'حدث خطأ أثناء نسخ السيارة: ' . $e->getMessage());
        }
    }
}