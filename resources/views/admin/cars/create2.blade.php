@extends('layouts.admin')

@section('title', 'إضافة سيارة جديدة')

@section('page-title', 'إضافة سيارة جديدة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item active">إضافة سيارة جديدة</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-plus me-2"></i>إضافة سيارة جديدة
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data"
                        id="carForm">
                        @csrf

                        <!-- الكود التلقائي -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <h6 class="mb-1">الكود التلقائي</h6>
                                    <p class="mb-0">الكود: <strong id="carCode">{{ $defaultCode }}</strong></p>
                                    <input type="hidden" name="code" value="{{ $defaultCode }}">
                                    <small class="text-muted">يتم توليد الكود تلقائياً ولا يمكن تعديله</small>
                                </div>
                            </div>
                        </div>

                        <!-- رقـم الهيكل ورقم اللوحة -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-barcode me-2"></i>المعلومات الفريدة</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="chassis_number">رقم الهيكل (VIN)</label>
                                            <input type="text" name="chassis_number" id="chassis_number"
                                                class="form-control @error('chassis_number') is-invalid @enderror"
                                                value="{{ old('chassis_number') }}" placeholder="أدخل رقم الهيكل (17 خانة)">
                                            @error('chassis_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">رقم فريد للهيكل (اختياري)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="plate_number">رقم اللوحة</label>
                                            <input type="text" name="plate_number" id="plate_number"
                                                class="form-control @error('plate_number') is-invalid @enderror"
                                                value="{{ old('plate_number') }}" placeholder="أدخل رقم اللوحة">
                                            @error('plate_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">رقم لوحة السيارة (اختياري)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="car_brand_id">العلامة التجارية <span
                                                            class="text-danger">*</span></label>
                                                    <select name="car_brand_id" id="car_brand_id"
                                                        class="form-control select2 @error('car_brand_id') is-invalid @enderror"
                                                        required data-placeholder="اختر العلامة التجارية">
                                                        <option value=""></option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ old('car_brand_id') == $brand->id ? 'selected' : '' }}>
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('car_brand_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="car_type_id">النوع (الموديل) <span
                                                            class="text-danger">*</span></label>
                                                    <select name="car_type_id" id="car_type_id"
                                                        class="form-control select2 @error('car_type_id') is-invalid @enderror"
                                                        required disabled data-placeholder="اختر النوع">
                                                        <option value=""></option>
                                                    </select>
                                                    <div id="typeLoading" class="d-none text-muted mt-1">
                                                        <i class="fas fa-spinner fa-spin"></i> جاري التحميل...
                                                    </div>
                                                    @error('car_type_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="car_trim_id">الفئة/الترم</label>
                                                    <select name="car_trim_id" id="car_trim_id"
                                                        class="form-control select2 @error('car_trim_id') is-invalid @enderror"
                                                        disabled data-placeholder="اختر الفئة">
                                                        <option value=""></option>
                                                    </select>
                                                    <div id="trimLoading" class="d-none text-muted mt-1">
                                                        <i class="fas fa-spinner fa-spin"></i> جاري التحميل...
                                                    </div>
                                                    @error('car_trim_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="car_category_id">تصنيف السيارة</label>
                                                    <select name="car_category_id" id="car_category_id"
                                                        class="form-control select2 @error('car_category_id') is-invalid @enderror"
                                                        data-placeholder="اختر التصنيف">
                                                        <option value=""></option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('car_category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('car_category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="model_year">سنة الموديل <span
                                                            class="text-danger">*</span></label>
                                                    <select name="model_year" id="model_year"
                                                        class="form-control @error('model_year') is-invalid @enderror"
                                                        required>
                                                        <option value="">اختر سنة الموديل</option>
                                                        @for ($year = date('Y') + 1; $year >= 1990; $year--)
                                                            <option value="{{ $year }}"
                                                                {{ old('model_year', date('Y')) == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('model_year')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="manufacturing_year">سنة التصنيع</label>
                                                    <select name="manufacturing_year" id="manufacturing_year"
                                                        class="form-control @error('manufacturing_year') is-invalid @enderror">
                                                        <option value="">اختر سنة التصنيع</option>
                                                        @for ($year = date('Y') + 1; $year >= 1990; $year--)
                                                            <option value="{{ $year }}"
                                                                {{ old('manufacturing_year') == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('manufacturing_year')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="color">اللون <span class="text-danger">*</span></label>
                                                    <input type="text" name="color" id="color"
                                                        class="form-control @error('color') is-invalid @enderror"
                                                        value="{{ old('color') }}" required
                                                        placeholder="أدخل لون السيارة">
                                                    @error('color')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="condition">الحالة <span
                                                            class="text-danger">*</span></label>
                                                    <select name="condition" id="condition"
                                                        class="form-control @error('condition') is-invalid @enderror"
                                                        required>
                                                        <option value="">اختر الحالة</option>
                                                        @foreach ($conditions as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ old('condition') == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('condition')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="mileage">الممشي (كم) <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" name="mileage" id="mileage"
                                                            class="form-control @error('mileage') is-invalid @enderror"
                                                            value="{{ old('mileage', 0) }}" min="0" required>
                                                        <span class="input-group-text">كم</span>
                                                    </div>
                                                    @error('mileage')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="horse_power">القوة الحصانية</label>
                                                    <div class="input-group">
                                                        <input type="number" name="horse_power" id="horse_power"
                                                            class="form-control @error('horse_power') is-invalid @enderror"
                                                            value="{{ old('horse_power') }}" min="0">
                                                        <span class="input-group-text">حصان</span>
                                                    </div>
                                                    @error('horse_power')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المواصفات الفنية -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>المواصفات الفنية</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="transmission_type_id">ناقل الحركة <span
                                                            class="text-danger">*</span></label>
                                                    <select name="transmission_type_id" id="transmission_type_id"
                                                        class="form-control select2 @error('transmission_type_id') is-invalid @enderror"
                                                        required data-placeholder="اختر ناقل الحركة">
                                                        <option value=""></option>
                                                        @foreach ($transmissions as $transmission)
                                                            <option value="{{ $transmission->id }}"
                                                                {{ old('transmission_type_id') == $transmission->id ? 'selected' : '' }}>
                                                                {{ $transmission->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('transmission_type_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="fuel_type_id">نوع الوقود <span
                                                            class="text-danger">*</span></label>
                                                    <select name="fuel_type_id" id="fuel_type_id"
                                                        class="form-control select2 @error('fuel_type_id') is-invalid @enderror"
                                                        required data-placeholder="اختر نوع الوقود">
                                                        <option value=""></option>
                                                        @foreach ($fuelTypes as $fuelType)
                                                            <option value="{{ $fuelType->id }}"
                                                                {{ old('fuel_type_id') == $fuelType->id ? 'selected' : '' }}>
                                                                {{ $fuelType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('fuel_type_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="car_status_id">حالة السيارة <span
                                                            class="text-danger">*</span></label>
                                                    <select name="car_status_id" id="car_status_id"
                                                        class="form-control select2 @error('car_status_id') is-invalid @enderror"
                                                        required data-placeholder="اختر حالة السيارة">
                                                        <option value=""></option>
                                                        @foreach ($statuses as $status)
                                                            <option value="{{ $status->id }}"
                                                                {{ old('car_status_id') == $status->id ? 'selected' : '' }}>
                                                                {{ $status->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('car_status_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="drive_type_id">نوع الدفع</label>
                                                    <select name="drive_type_id" id="drive_type_id"
                                                        class="form-control select2 @error('drive_type_id') is-invalid @enderror"
                                                        data-placeholder="اختر نوع الدفع">
                                                        <option value=""></option>
                                                        @foreach ($driveTypes as $driveType)
                                                            <option value="{{ $driveType->id }}"
                                                                {{ old('drive_type_id') == $driveType->id ? 'selected' : '' }}>
                                                                {{ $driveType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('drive_type_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="doors">عدد الأبواب</label>
                                                    <select name="doors" id="doors"
                                                        class="form-control @error('doors') is-invalid @enderror">
                                                        <option value="">اختر عدد الأبواب</option>
                                                        @for ($i = 2; $i <= 10; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('doors', 4) == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('doors')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="seats">عدد المقاعد</label>
                                                    <select name="seats" id="seats"
                                                        class="form-control @error('seats') is-invalid @enderror">
                                                        <option value="">اختر عدد المقاعد</option>
                                                        @for ($i = 2; $i <= 20; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('seats', 5) == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('seats')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="cylinders">عدد الاسطوانات</label>
                                                    <select name="cylinders" id="cylinders"
                                                        class="form-control @error('cylinders') is-invalid @enderror">
                                                        <option value="">اختر عدد الاسطوانات</option>
                                                        @for ($i = 3; $i <= 12; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('cylinders') == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('cylinders')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="engine_capacity">سعة المحرك (سي سي)</label>
                                                    <div class="input-group">
                                                        <input type="number" name="engine_capacity" id="engine_capacity"
                                                            class="form-control @error('engine_capacity') is-invalid @enderror"
                                                            value="{{ old('engine_capacity') }}" min="0"
                                                            placeholder="مثال: 2000">
                                                        <span class="input-group-text">سي سي</span>
                                                    </div>
                                                    @error('engine_capacity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الأسعار -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>الأسعار</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="purchase_price">سعر الشراء <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="purchase_price" id="purchase_price"
                                                    class="form-control @error('purchase_price') is-invalid @enderror"
                                                    value="{{ old('purchase_price') }}" min="0" required>
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                            @error('purchase_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="selling_price">سعر البيع <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="selling_price" id="selling_price"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    value="{{ old('selling_price') }}" min="0" required>
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                            @error('selling_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="minimum_price">أقل سعر (اختياري)</label>
                                            <div class="input-group">
                                                <input type="number" name="minimum_price" id="minimum_price"
                                                    class="form-control @error('minimum_price') is-invalid @enderror"
                                                    value="{{ old('minimum_price') }}" min="0">
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                            @error('minimum_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="cost_price">سعر التكلفة (اختياري)</label>
                                            <div class="input-group">
                                                <input type="number" name="cost_price" id="cost_price"
                                                    class="form-control @error('cost_price') is-invalid @enderror"
                                                    value="{{ old('cost_price') }}" min="0">
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                            @error('cost_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- نظام رفع الصور بالسحب والإفلات -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-images me-2"></i>رفع الصور</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="image_type">نوع الصور</label>
                                            <select name="image_type" id="image_type" class="form-control select2">
                                                <option value="exterior" selected>خارجي</option>
                                                <option value="interior">داخلي</option>
                                                <option value="engine">محرك</option>
                                                <option value="document">مستند</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="image_description">وصف الصور</label>
                                            <input type="text" name="image_description" id="image_description"
                                                class="form-control" placeholder="وصف للصور المرفوعة">
                                        </div>
                                    </div>
                                </div>

                                <!-- منطقة السحب والإفلات -->
                                <div class="mb-3">
                                    <label class="form-label">رفع الصور (اختياري)</label>

                                    <div class="upload-area @error('uploaded_images') border-danger @enderror"
                                        id="dropArea" onclick="document.getElementById('fileInput').click()">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                            <h5>اسحب وأفلت الصور هنا</h5>
                                            <p class="text-muted">أو انقر لاختيار الملفات</p>
                                            <p class="text-muted small">الحد الأقصى لحجم الملف: 2MB - الصيغ المسموحة: JPG,
                                                PNG, GIF, WEBP</p>
                                        </div>
                                        <!-- تم إزالة name="additional_images[]" لمنع إرسال الملفات مرتين -->
                                        <input type="file" id="fileInput" class="d-none" accept="image/*" multiple
                                            onchange="handleFileSelect(event)">
                                        @error('uploaded_images')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- معاينة الصور -->
                                    <div id="previewContainer" class="row mt-3 d-none">
                                        <div class="col-12">
                                            <h6>معاينة الصور المحددة:</h6>
                                            <div id="imagePreviews" class="row"></div>
                                        </div>
                                    </div>

                                    <!-- مخزن للصور المؤقتة -->
                                    <input type="hidden" name="uploaded_images" id="uploadedImages">
                                </div>

                                <!-- الصورة الرئيسية -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="main_image">الصورة الرئيسية (اختياري)</label>
                                            <input type="file" name="main_image" id="main_image"
                                                class="form-control @error('main_image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('main_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">يجب أن تكون الصورة بصيغة jpg, png, gif, webp بحجم لا
                                                يزيد عن 2MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="entry_date">تاريخ الدخول للمعرض <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="entry_date" id="entry_date"
                                                class="form-control @error('entry_date') is-invalid @enderror"
                                                value="{{ old('entry_date', date('Y-m-d')) }}" required>
                                            @error('entry_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="purchase_date">تاريخ الشراء</label>
                                            <input type="date" name="purchase_date" id="purchase_date"
                                                class="form-control @error('purchase_date') is-invalid @enderror"
                                                value="{{ old('purchase_date') }}">
                                            @error('purchase_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="inspection_date">تاريخ الفحص</label>
                                            <input type="date" name="inspection_date" id="inspection_date"
                                                class="form-control @error('inspection_date') is-invalid @enderror"
                                                value="{{ old('inspection_date') }}">
                                            @error('inspection_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="registration_date">تاريخ التسجيل</label>
                                            <input type="date" name="registration_date" id="registration_date"
                                                class="form-control @error('registration_date') is-invalid @enderror"
                                                value="{{ old('registration_date') }}">
                                            @error('registration_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="country_of_origin">بلد المنشأ</label>
                                            <input type="text" name="country_of_origin" id="country_of_origin"
                                                class="form-control @error('country_of_origin') is-invalid @enderror"
                                                value="{{ old('country_of_origin') }}" placeholder="أدخل بلد المنشأ">
                                            @error('country_of_origin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="import_from">بلد الاستيراد</label>
                                            <input type="text" name="import_from" id="import_from"
                                                class="form-control @error('import_from') is-invalid @enderror"
                                                value="{{ old('import_from') }}" placeholder="أدخل بلد الاستيراد">
                                            @error('import_from')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="description">وصف السيارة <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                                rows="4" required placeholder="أدخل وصفاً تفصيلياً للسيارة...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="notes">ملاحظات إضافية</label>
                                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                                                placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="is_featured" id="is_featured"
                                                class="form-check-input" value="1"
                                                {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">سيارة مميزة</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="is_negotiable" id="is_negotiable"
                                                class="form-check-input" value="1"
                                                {{ old('is_negotiable', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_negotiable">السعر قابل للتفاوض</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="is_financeable" id="is_financeable"
                                                class="form-check-input" value="1"
                                                {{ old('is_financeable', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_financeable">قابلة للتمويل</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_ownership" id="has_ownership"
                                                class="form-check-input" value="1"
                                                {{ old('has_ownership') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_ownership">مستند الملكية</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_insurance" id="has_insurance"
                                                class="form-check-input" value="1"
                                                {{ old('has_insurance') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_insurance">التأمين</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_registration" id="has_registration"
                                                class="form-check-input" value="1"
                                                {{ old('has_registration') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_registration">شهادة التسجيل</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_maintenance_record"
                                                id="has_maintenance_record" class="form-check-input" value="1"
                                                {{ old('has_maintenance_record') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_maintenance_record">سجل
                                                الصيانة</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="features">المميزات الإضافية</label>
                                            <textarea name="features" id="features" class="form-control @error('features') is-invalid @enderror"
                                                rows="3" placeholder="أدخل المميزات كل مميزة في سطر...">{{ old('features') }}</textarea>
                                            <small class="text-muted">كل مميزة في سطر جديد (سيتم تحويلها إلى JSON
                                                تلقائياً)</small>
                                            @error('features')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="specifications">المواصفات الإضافية</label>
                                            <textarea name="specifications" id="specifications"
                                                class="form-control @error('specifications') is-invalid @enderror" rows="3"
                                                placeholder="أدخل المواصفات كـ JSON...">{{ old('specifications') }}</textarea>
                                            <small class="text-muted">صيغة JSON مثل: {"طول": "4.5م", "عرض": "1.8م"}</small>
                                            @error('specifications')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-save me-1"></i>حفظ السيارة
                            </button>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .form-switch .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        /* تنسيقات منطقة السحب والإفلات */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 3rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: #4361ee;
            background: #f0f4ff;
        }

        .upload-content i {
            transition: transform 0.3s ease;
        }

        .upload-area:hover .upload-content i {
            transform: translateY(-5px);
        }

        .preview-image {
            position: relative;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .preview-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .preview-info {
            padding: 10px;
            background: white;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #dc3545;
            transform: scale(1.1);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                language: "ar",
                dir: "rtl",
                width: '100%'
            });

            // عند تغيير العلامة التجارية
            $('#car_brand_id').on('change', function() {
                var brandId = $(this).val();

                if (brandId) {
                    // تفعيل وتحميل الأنواع
                    $('#car_type_id').prop('disabled', false).empty().trigger('change');
                    $('#typeLoading').removeClass('d-none');

                    // جلب الأنواع عبر AJAX
                    $.ajax({
                        url: '{{ url('admin/cars/get-types') }}/' + brandId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#car_type_id').empty().append('<option value=""></option>');

                            if (data.length > 0) {
                                $.each(data, function(index, type) {
                                    $('#car_type_id').append(
                                        '<option value="' + type.id + '">' + type
                                        .name + '</option>'
                                    );
                                });

                                // اختيار قيمة قديمة إذا كانت موجودة
                                @if (old('car_type_id'))
                                    $('#car_type_id').val('{{ old('car_type_id') }}').trigger(
                                        'change');
                                @endif
                            } else {
                                $('#car_type_id').append(
                                    '<option value="">لا توجد أنواع لهذه العلامة</option>');
                            }

                            $('#typeLoading').addClass('d-none');
                            $('#car_type_id').trigger('change');

                            // إعادة تعيين الفئات
                            $('#car_trim_id').prop('disabled', true).empty().trigger('change');
                        },
                        error: function(xhr) {
                            $('#typeLoading').addClass('d-none');
                            console.log('Error:', xhr.responseText);
                            $('#car_type_id').append(
                                '<option value="">حدث خطأ في تحميل الأنواع</option>');
                        }
                    });
                } else {
                    // تعطيل وإعادة تعيين الحقول
                    $('#car_type_id').prop('disabled', true).empty().trigger('change');
                    $('#car_trim_id').prop('disabled', true).empty().trigger('change');
                }
            });

            // عند تغيير النوع (الموديل)
            $('#car_type_id').on('change', function() {
                var brandId = $('#car_brand_id').val();
                var typeId = $(this).val();

                if (brandId && typeId) {
                    // تفعيل وتحميل الفئات
                    $('#car_trim_id').prop('disabled', false).empty().trigger('change');
                    $('#trimLoading').removeClass('d-none');

                    // جلب الفئات عبر AJAX
                    $.ajax({
                        url: '{{ url('admin/cars/get-trims') }}/' + brandId + '/' + typeId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#car_trim_id').empty().append('<option value=""></option>');

                            if (data.length > 0) {
                                $.each(data, function(index, trim) {
                                    $('#car_trim_id').append(
                                        '<option value="' + trim.id + '">' + trim
                                        .name + '</option>'
                                    );
                                });

                                // اختيار قيمة قديمة إذا كانت موجودة
                                @if (old('car_trim_id'))
                                    $('#car_trim_id').val('{{ old('car_trim_id') }}').trigger(
                                        'change');
                                @endif
                            } else {
                                $('#car_trim_id').append(
                                    '<option value="">لا توجد فئات لهذا النوع</option>');
                            }

                            $('#trimLoading').addClass('d-none');
                            $('#car_trim_id').trigger('change');
                        },
                        error: function(xhr) {
                            $('#trimLoading').addClass('d-none');
                            console.log('Error:', xhr.responseText);
                            $('#car_trim_id').append(
                                '<option value="">حدث خطأ في تحميل الفئات</option>');
                        }
                    });
                } else {
                    // تعطيل الفئات
                    $('#car_trim_id').prop('disabled', true).empty().trigger('change');
                }
            });

            // تحميل الأنواع إذا كانت هناك قيمة مختارة مسبقاً
            @if (old('car_brand_id'))
                $('#car_brand_id').trigger('change');

                // إذا كان هناك نوع محدد، انتظر قليلاً ثم قم بتحديده
                @if (old('car_type_id'))
                    setTimeout(function() {
                        $('#car_type_id').val('{{ old('car_type_id') }}').trigger('change');
                    }, 500);
                @endif
            @endif
        });

        // منطق نظام رفع الصور بالسحب والإفلات
        // عناصر DOM
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreviews = document.getElementById('imagePreviews');
        const uploadedImagesInput = document.getElementById('uploadedImages');
        const submitBtn = document.getElementById('submitBtn');
        const carForm = document.getElementById('carForm');

        // مصفوفة لتخزين الصور المؤقتة
        let uploadedImages = [];

        // منع السلوك الافتراضي لمنطقة السحب
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // إضافة تأثيرات السحب
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-primary');
            dropArea.style.backgroundColor = '#f0f4ff';
        }

        function unhighlight() {
            dropArea.classList.remove('border-primary');
            dropArea.style.backgroundColor = '#f8f9fa';
        }

        // معالجة الإفلات
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // معالجة اختيار الملفات
        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        async function handleFiles(files) {
            if (files.length === 0) return;

            // إظهار حاوية المعاينة
            previewContainer.classList.remove('d-none');

            // التحقق من حجم الملفات
            let validFiles = true;
            Array.from(files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert(`الملف ${file.name} أكبر من 2MB`);
                    validFiles = false;
                    return;
                }

                if (!file.type.match('image.*')) {
                    alert(`الملف ${file.name} ليس صورة`);
                    validFiles = false;
                    return;
                }
            });

            if (!validFiles) return;

            // رفع الصور إلى السيرفر
            for (let i = 0; i < files.length; i++) {
                await uploadTempImage(files[i]);
            }

            // تحديث الحقل المخفي بالصور المؤقتة
            updateUploadedImagesInput();
        }

        async function uploadTempImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('{{ route('admin.cars.upload-temp-image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // إضافة الصورة إلى المصفوفة
                    uploadedImages.push({
                        path: data.path,
                        url: data.url,
                        name: data.name,
                        size: data.size
                    });

                    // إنشاء معاينة للصورة
                    createPreview(data);
                } else {
                    alert(data.message || 'حدث خطأ أثناء رفع الصورة');
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                alert('حدث خطأ أثناء رفع الصورة');
            }
        }

        function createPreview(imageData) {
            const col = document.createElement('div');
            col.className = 'col-xl-3 col-lg-4 col-md-6';
            col.setAttribute('data-path', imageData.path);

            const previewDiv = document.createElement('div');
            previewDiv.className = 'preview-image';

            const img = document.createElement('img');
            img.src = imageData.url;
            img.alt = imageData.name;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                removeImage(imageData.path, col);
            };

            const infoDiv = document.createElement('div');
            infoDiv.className = 'preview-info';
            infoDiv.innerHTML = `
                <small class="d-block text-truncate">${imageData.name}</small>
                <small class="text-muted">${formatBytes(imageData.size)}</small>
            `;

            previewDiv.appendChild(img);
            previewDiv.appendChild(removeBtn);
            previewDiv.appendChild(infoDiv);
            col.appendChild(previewDiv);
            imagePreviews.appendChild(col);
        }

        async function removeImage(path, element) {
            try {
                const response = await fetch('{{ route('admin.cars.delete-temp-image') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        path: path
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // إزالة الصورة من المصفوفة
                    uploadedImages = uploadedImages.filter(img => img.path !== path);

                    // إزالة العنصر من DOM
                    element.remove();

                    // تحديث الحقل المخفي
                    updateUploadedImagesInput();

                    // إخفاء حاوية المعاينة إذا لم تكن هناك صور
                    if (uploadedImages.length === 0) {
                        previewContainer.classList.add('d-none');
                    }
                } else {
                    alert(data.message || 'حدث خطأ أثناء حذف الصورة');
                }
            } catch (error) {
                console.error('Error deleting image:', error);
                alert('حدث خطأ أثناء حذف الصورة');
            }
        }

        function updateUploadedImagesInput() {
            uploadedImagesInput.value = JSON.stringify(uploadedImages);
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // إرسال النموذج - الإصلاح الرئيسي هنا
        // إرسال النموذج - الإصلاح النهائي هنا
        carForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            console.log('======= FORM SUBMISSION DEBUG =======');

            // التحقق من الحقول المطلوبة
            const requiredFields = [
                'car_brand_id',
                'car_type_id',
                'car_status_id',
                'transmission_type_id',
                'fuel_type_id',
                'model_year',
                'color',
                'condition',
                'mileage',
                'purchase_price',
                'selling_price',
                'description',
                'entry_date'
            ];

            let missingFields = [];
            requiredFields.forEach(field => {
                const fieldElement = carForm.querySelector(`[name="${field}"]`);
                if (fieldElement && (!fieldElement.value || fieldElement.value.trim() === '')) {
                    missingFields.push(field);
                }
            });

            if (missingFields.length > 0) {
                alert('الحقول التالية مطلوبة: ' + missingFields.join(', '));
                return;
            }

            // 1. جمع البيانات الأساسية
            const formData = new FormData(this);

            // 2. إضافة main_image إذا كان موجوداً
            const mainImageInput = document.getElementById('main_image');
            if (mainImageInput.files.length > 0) {
                formData.append('main_image', mainImageInput.files[0]);
            }

            // 3. إضافة uploaded_images كـ JSON
            formData.append('uploaded_images', uploadedImagesInput.value);

            // 4. تحويل المميزات إلى JSON إذا كانت موجودة
            const featuresText = document.getElementById('features').value;
            if (featuresText) {
                const featuresArray = featuresText.split('\n').filter(line => line.trim() !== '');
                formData.set('features', JSON.stringify(featuresArray));
            }

            console.log('FormData ready for submission. Files to upload:');
            console.log('- main_image:', mainImageInput.files.length > 0 ? 'Yes' : 'No');
            console.log('- uploaded_images:', uploadedImages.length, 'images');

            // تعطيل الزر أثناء الرفع
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الحفظ...';

            try {
                const response = await fetch(carForm.action, {
                    method: carForm.method,
                    body: formData,
                    // لا نضيف Accept: application/json لأننا نريد التعامل مع HTML أيضًا
                });

                console.log('Response status:', response.status);
                console.log('Response type:', response.headers.get('content-type'));

                // إذا كان الرد HTML (redirect) - حالة النجاح العادية
                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('text/html')) {
                    console.log('Server returned HTML (likely a redirect)');

                    // نحصل على النص لمعرفة ما إذا كان هناك redirect
                    const responseText = await response.text();

                    // نبحث عن أي توجيه في النص
                    if (responseText.includes('window.location') || responseText.includes('Redirecting')) {
                        // إذا كان هناك توجيه في النص، ننتقل يدويًا
                        window.location.href = '{{ route('admin.cars.index') }}';
                    } else if (response.ok) {
                        // إذا كان الرد ناجحًا ولكن ليس JSON، نذهب إلى صفحة الفهرس
                        window.location.href = '{{ route('admin.cars.index') }}';
                    } else {
                        // عرض أي أخطاء في HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = responseText;
                        const errorMessages = tempDiv.querySelectorAll('.alert-danger, .invalid-feedback');
                        if (errorMessages.length > 0) {
                            alert(errorMessages[0].textContent);
                        } else {
                            alert('حدث خطأ غير متوقع');
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>حفظ السيارة';
                    }
                }
                // إذا كان الرد JSON - حالة خاصة
                else if (contentType.includes('application/json')) {
                    const result = await response.json();
                    console.log('Server returned JSON:', result);

                    if (result.success && result.redirect) {
                        window.location.href = result.redirect;
                    } else if (result.success) {
                        window.location.href = '{{ route('admin.cars.index') }}';
                    } else {
                        alert(result.message || 'حدث خطأ أثناء الحفظ');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>حفظ السيارة';
                    }
                }
                // أي نوع آخر
                else {
                    console.log('Unknown response type');
                    if (response.ok) {
                        window.location.href = '{{ route('admin.cars.index') }}';
                    } else {
                        alert('حدث خطأ غير متوقع');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>حفظ السيارة';
                    }
                }
            } catch (error) {
                console.error('Network error:', error);
                alert('حدث خطأ في الشبكة: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>حفظ السيارة';
            }
        });

        // عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Car Create Page Loaded');
            console.log('Form action:', carForm.action);
            console.log('CSRF Token:', document.querySelector('input[name="_token"]').value);
        });
    </script>
@endpush
