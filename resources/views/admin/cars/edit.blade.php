@extends('layouts.admin')

@section('title', 'تعديل سيارة: ' . $car->code)
@section('page-title', 'تعديل سيارة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item active">تعديل: {{ $car->code }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-edit me-2"></i>تعديل بيانات السيارة</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>خطأ!</strong> لم يتم حفظ البيانات. يرجى مراجعة الأخطاء أدناه.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- نموذج التعديل الرئيسي --}}
                    <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data"
                        id="carForm" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- كود السيارة (مخفي) --}}
                        <input type="hidden" name="code" value="{{ old('code', $car->code) }}">

                        {{-- 1. المعلومات الأساسية --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-car me-2"></i>معلومات أساسية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="car_brand_id" class="form-label">العلامة التجارية <span
                                                class="text-danger">*</span></label>
                                        <select name="car_brand_id" id="car_brand_id"
                                            class="form-control select2 @error('car_brand_id') is-invalid @enderror"
                                            data-placeholder="اختر العلامة التجارية">
                                            <option value=""></option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('car_brand_id', $car->car_brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_brand_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="car_type_id" class="form-label">الموديل <span
                                                class="text-danger">*</span></label>
                                        <select name="car_type_id" id="car_type_id"
                                            class="form-control select2 @error('car_type_id') is-invalid @enderror"
                                            data-placeholder="اختر الموديل">
                                            <option value=""></option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ old('car_type_id', $car->car_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="car_trim_id" class="form-label">الفئة</label>
                                        <select name="car_trim_id" id="car_trim_id"
                                            class="form-control select2 @error('car_trim_id') is-invalid @enderror"
                                            data-placeholder="اختر الفئة">
                                            <option value=""></option>
                                            @foreach ($trims as $trim)
                                                <option value="{{ $trim->id }}"
                                                    {{ old('car_trim_id', $car->car_trim_id) == $trim->id ? 'selected' : '' }}>
                                                    {{ $trim->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_trim_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="car_category_id" class="form-label">التصنيف</label>
                                        <select name="car_category_id" id="car_category_id"
                                            class="form-control select2 @error('car_category_id') is-invalid @enderror"
                                            data-placeholder="اختر التصنيف">
                                            <option value=""></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('car_category_id', $car->car_category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="model_year" class="form-label">سنة الموديل <span
                                                class="text-danger">*</span></label>
                                        <select name="model_year" id="model_year"
                                            class="form-control @error('model_year') is-invalid @enderror">
                                            <option value="">اختر السنة</option>
                                            @for ($year = date('Y') + 1; $year >= 1990; $year--)
                                                <option value="{{ $year }}"
                                                    {{ old('model_year', $car->model_year) == $year ? 'selected' : '' }}>
                                                    {{ $year }}</option>
                                            @endfor
                                        </select>
                                        @error('model_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="color" class="form-label">اللون <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="color" id="color"
                                            class="form-control @error('color') is-invalid @enderror"
                                            value="{{ old('color', $car->color) }}" placeholder="أدخل اللون">
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="condition" class="form-label">الحالة <span
                                                class="text-danger">*</span></label>
                                        <select name="condition" id="condition"
                                            class="form-control @error('condition') is-invalid @enderror">
                                            <option value="">اختر الحالة</option>
                                            @foreach ($conditions as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('condition', $car->condition) == $key ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="mileage" class="form-label">الممشي (كم) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="mileage" id="mileage"
                                                class="form-control @error('mileage') is-invalid @enderror"
                                                value="{{ old('mileage', $car->mileage) }}" min="0">
                                            <span class="input-group-text">كم</span>
                                        </div>
                                        @error('mileage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="car_status_id" class="form-label">حالة السيارة (نظامية) <span
                                                class="text-danger">*</span></label>
                                        <select name="car_status_id" id="car_status_id"
                                            class="form-control select2 @error('car_status_id') is-invalid @enderror"
                                            data-placeholder="اختر الحالة">
                                            <option value=""></option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}"
                                                    {{ old('car_status_id', $car->car_status_id) == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. المواصفات الفنية --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>المواصفات الفنية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="transmission_type_id" class="form-label">ناقل الحركة <span
                                                class="text-danger">*</span></label>
                                        <select name="transmission_type_id" id="transmission_type_id"
                                            class="form-control select2 @error('transmission_type_id') is-invalid @enderror"
                                            data-placeholder="اختر ناقل الحركة">
                                            <option value=""></option>
                                            @foreach ($transmissions as $trans)
                                                <option value="{{ $trans->id }}"
                                                    {{ old('transmission_type_id', $car->transmission_type_id) == $trans->id ? 'selected' : '' }}>
                                                    {{ $trans->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('transmission_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="fuel_type_id" class="form-label">نوع الوقود <span
                                                class="text-danger">*</span></label>
                                        <select name="fuel_type_id" id="fuel_type_id"
                                            class="form-control select2 @error('fuel_type_id') is-invalid @enderror"
                                            data-placeholder="اختر نوع الوقود">
                                            <option value=""></option>
                                            @foreach ($fuelTypes as $fuel)
                                                <option value="{{ $fuel->id }}"
                                                    {{ old('fuel_type_id', $car->fuel_type_id) == $fuel->id ? 'selected' : '' }}>
                                                    {{ $fuel->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('fuel_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="drive_type_id" class="form-label">نوع الدفع</label>
                                        <select name="drive_type_id" id="drive_type_id"
                                            class="form-control select2 @error('drive_type_id') is-invalid @enderror"
                                            data-placeholder="اختر نوع الدفع">
                                            <option value=""></option>
                                            @foreach ($driveTypes as $drive)
                                                <option value="{{ $drive->id }}"
                                                    {{ old('drive_type_id', $car->drive_type_id) == $drive->id ? 'selected' : '' }}>
                                                    {{ $drive->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('drive_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="engine_capacity" class="form-label">سعة المحرك (CC)</label>
                                        <div class="input-group">
                                            <input type="number" name="engine_capacity" id="engine_capacity"
                                                class="form-control @error('engine_capacity') is-invalid @enderror"
                                                value="{{ old('engine_capacity', $car->engine_capacity) }}"
                                                min="0" placeholder="مثال: 2000">
                                            <span class="input-group-text">سي سي</span>
                                        </div>
                                        @error('engine_capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="horse_power" class="form-label">القوة الحصانية (HP)</label>
                                        <div class="input-group">
                                            <input type="number" name="horse_power" id="horse_power"
                                                class="form-control @error('horse_power') is-invalid @enderror"
                                                value="{{ old('horse_power', $car->horse_power) }}" min="0"
                                                placeholder="مثال: 200">
                                            <span class="input-group-text">حصان</span>
                                        </div>
                                        @error('horse_power')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="cylinders" class="form-label">عدد الأسطوانات</label>
                                        <select name="cylinders" id="cylinders"
                                            class="form-control @error('cylinders') is-invalid @enderror">
                                            <option value="">اختر عدد الأسطوانات</option>
                                            @for ($i = 3; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('cylinders', $car->cylinders) == $i ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('cylinders')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="doors" class="form-label">عدد الأبواب</label>
                                        <select name="doors" id="doors"
                                            class="form-control @error('doors') is-invalid @enderror">
                                            <option value="">اختر عدد الأبواب</option>
                                            @for ($i = 2; $i <= 10; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('doors', $car->doors) == $i ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('doors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="seats" class="form-label">عدد المقاعد</label>
                                        <select name="seats" id="seats"
                                            class="form-control @error('seats') is-invalid @enderror">
                                            <option value="">اختر عدد المقاعد</option>
                                            @for ($i = 2; $i <= 20; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('seats', $car->seats) == $i ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('seats')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. الأسعار والتواريخ --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-tag me-2"></i>الأسعار والتواريخ</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="purchase_price" class="form-label">سعر الشراء <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="purchase_price"
                                                id="purchase_price"
                                                class="form-control @error('purchase_price') is-invalid @enderror"
                                                value="{{ old('purchase_price', $car->purchase_price) }}" min="0">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                        @error('purchase_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="selling_price" class="form-label">سعر البيع <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="selling_price" id="selling_price"
                                                class="form-control @error('selling_price') is-invalid @enderror"
                                                value="{{ old('selling_price', $car->selling_price) }}" min="0">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                        @error('selling_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="purchase_date" class="form-label">تاريخ الشراء</label>
                                        <input type="date" name="purchase_date" id="purchase_date"
                                            class="form-control @error('purchase_date') is-invalid @enderror"
                                            value="{{ old('purchase_date', $car->purchase_date ? $car->purchase_date->format('Y-m-d') : '') }}">
                                        @error('purchase_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="entry_date" class="form-label">تاريخ الإدخال <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="entry_date" id="entry_date"
                                            class="form-control @error('entry_date') is-invalid @enderror"
                                            value="{{ old('entry_date', $car->entry_date ? $car->entry_date->format('Y-m-d') : '') }}">
                                        @error('entry_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price_change_reason" class="form-label">سبب تغيير السعر
                                                (اختياري)</label>
                                            <input type="text" name="price_change_reason" id="price_change_reason"
                                                class="form-control" value="{{ old('price_change_reason') }}"
                                                placeholder="مثال: تحديث السعر">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. المعلومات الفريدة --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-fingerprint me-2"></i>المعلومات الفريدة</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="chassis_number" class="form-label">رقم الهيكل (VIN)</label>
                                        <input type="text" name="chassis_number" id="chassis_number"
                                            class="form-control @error('chassis_number') is-invalid @enderror"
                                            value="{{ old('chassis_number', $car->chassis_number) }}" maxlength="50"
                                            placeholder="أدخل رقم الهيكل (17 خانة)">
                                        @error('chassis_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="plate_number" class="form-label">رقم اللوحة</label>
                                        <input type="text" name="plate_number" id="plate_number"
                                            class="form-control @error('plate_number') is-invalid @enderror"
                                            value="{{ old('plate_number', $car->plate_number) }}" maxlength="20"
                                            placeholder="أدخل رقم اللوحة">
                                        @error('plate_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 5. الصور الموجودة --}}
                        @if ($car->images->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-images me-2"></i>الصور الموجودة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($car->images as $image)
                                            <div class="col-md-3 mb-3">
                                                <div class="position-relative border rounded p-2">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="Car Image" class="img-thumbnail"
                                                        style="height:150px; width:100%; object-fit:cover;">
                                                    <div class="mt-2 d-flex justify-content-between">
                                                        @if ($image->is_main)
                                                            <span class="badge bg-success">رئيسية</span>
                                                        @else
                                                            <form
                                                                action="{{ route('admin.cars.set-main-image', [$car->id, $image->id]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-primary"
                                                                    onclick="return confirm('تعيين هذه الصورة كرئيسية؟')">
                                                                    <i class="fas fa-star"></i> تعيين رئيسية
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form
                                                            action="{{ route('admin.cars.delete-image', [$car->id, $image->id]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('حذف الصورة نهائياً؟')">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 6. رفع صور جديدة (طريقة عادية) --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-upload me-2"></i>إضافة صور جديدة</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="main_image" class="form-label">الصورة الرئيسية الجديدة
                                                (اختياري)</label>
                                            <input type="file" name="main_image" id="main_image"
                                                class="form-control @error('main_image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('main_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">سيتم استبدال الصورة الرئيسية الحالية إن وجدت.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="images" class="form-label">صور إضافية متعددة (اختياري)</label>
                                            <input type="file" name="images[]" id="images"
                                                class="form-control @error('images.*') is-invalid @enderror" multiple
                                                accept="image/*">
                                            @error('images.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">يمكنك اختيار عدة صور.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 7. معلومات إضافية --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="country_of_origin">بلد المنشأ</label>
                                            <input type="text" name="country_of_origin" id="country_of_origin"
                                                class="form-control @error('country_of_origin') is-invalid @enderror"
                                                value="{{ old('country_of_origin', $car->country_of_origin) }}"
                                                placeholder="أدخل بلد المنشأ">
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
                                                value="{{ old('import_from', $car->import_from) }}"
                                                placeholder="أدخل بلد الاستيراد">
                                            @error('import_from')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="manufacturing_year">سنة التصنيع</label>
                                            <select name="manufacturing_year" id="manufacturing_year"
                                                class="form-control @error('manufacturing_year') is-invalid @enderror">
                                                <option value="">اختر سنة التصنيع</option>
                                                @for ($year = date('Y') + 1; $year >= 1990; $year--)
                                                    <option value="{{ $year }}"
                                                        {{ old('manufacturing_year', $car->manufacturing_year) == $year ? 'selected' : '' }}>
                                                        {{ $year }}</option>
                                                @endfor
                                            </select>
                                            @error('manufacturing_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="inspection_date">تاريخ الفحص</label>
                                            <input type="date" name="inspection_date" id="inspection_date"
                                                class="form-control @error('inspection_date') is-invalid @enderror"
                                                value="{{ old('inspection_date', $car->inspection_date ? $car->inspection_date->format('Y-m-d') : '') }}">
                                            @error('inspection_date')
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
                                            <textarea name="description" id="description" rows="4"
                                                class="form-control @error('description') is-invalid @enderror" placeholder="أدخل وصفاً تفصيلياً للسيارة...">{{ old('description', $car->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="notes">ملاحظات إضافية (داخلية)</label>
                                            <textarea name="notes" id="notes" rows="2" class="form-control @error('notes') is-invalid @enderror"
                                                placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes', $car->notes) }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="features">المميزات الإضافية</label>
                                            <textarea name="features" id="features" rows="3"
                                                class="form-control @error('features') is-invalid @enderror" placeholder="أدخل المميزات كل مميزة في سطر...">{{ old('features', is_array($car->features) ? implode("\n", $car->features) : $car->features) }}</textarea>
                                            <small class="text-muted">كل مميزة في سطر جديد</small>
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
                                            <textarea name="specifications" id="specifications" rows="3"
                                                class="form-control @error('specifications') is-invalid @enderror" placeholder="أدخل المواصفات كـ JSON...">{{ old('specifications', is_string($car->specifications) ? $car->specifications : json_encode($car->specifications, JSON_PRETTY_PRINT)) }}</textarea>
                                            <small class="text-muted">صيغة JSON مثل: {"طول": "4.5م", "عرض": "1.8م"}</small>
                                            @error('specifications')
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
                                                {{ old('is_featured', $car->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">سيارة مميزة</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="is_negotiable" id="is_negotiable"
                                                class="form-check-input" value="1"
                                                {{ old('is_negotiable', $car->is_negotiable) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_negotiable">قابل للتفاوض</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="is_financeable" id="is_financeable"
                                                class="form-check-input" value="1"
                                                {{ old('is_financeable', $car->is_financeable) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_financeable">تمويل متاح</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_ownership" id="has_ownership"
                                                class="form-check-input" value="1"
                                                {{ old('has_ownership', $car->has_ownership) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_ownership">ملكية واضحة</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_insurance" id="has_insurance"
                                                class="form-check-input" value="1"
                                                {{ old('has_insurance', $car->has_insurance) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_insurance">تأمين ساري</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_registration" id="has_registration"
                                                class="form-check-input" value="1"
                                                {{ old('has_registration', $car->has_registration) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_registration">استمارة سارية</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" name="has_maintenance_record"
                                                id="has_maintenance_record" class="form-check-input" value="1"
                                                {{ old('has_maintenance_record', $car->has_maintenance_record) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_maintenance_record">سجل صيانة</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 8. أزرار الإجراءات --}}
                        <div class="form-group mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="fas fa-save me-1"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('admin.cars.show', $car->id) }}" class="btn btn-info btn-lg px-4">
                                <i class="fas fa-eye me-1"></i> عرض
                            </a>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-1"></i> رجوع
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
    </style>
@endpush

{{-- @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                language: 'ar',
                dir: 'rtl',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // بيانات الأنواع والفئات من المتحكم
            var allTypes = @json($types); // CarType
            var allTrims = @json($trims); // CarTrim

            // تصفية الأنواع حسب العلامة التجارية
            function filterTypes(brandId) {
                var options = '<option value=""></option>';
                if (brandId) {
                    allTypes.forEach(function(type) {
                        if (type.car_brand_id == brandId) {
                            options += `<option value="${type.id}">${type.name}</option>`;
                        }
                    });
                }
                $('#car_type_id').html(options).trigger('change.select2');
            }

            // تصفية الفئات حسب النوع
            function filterTrims(typeId) {
                var options = '<option value=""></option>';
                if (typeId) {
                    allTrims.forEach(function(trim) {
                        if (trim.car_type_id == typeId) {
                            options += `<option value="${trim.id}">${trim.name}</option>`;
                        }
                    });
                }
                $('#car_trim_id').html(options).trigger('change.select2');
            }

            // ربط الأحداث
            $('#car_brand_id').on('change', function() {
                var brandId = $(this).val();
                filterTypes(brandId);
                $('#car_trim_id').html('<option value=""></option>').trigger('change.select2');
            });

            $('#car_type_id').on('change', function() {
                var typeId = $(this).val();
                filterTrims(typeId);
            });

            // استعادة القيم بعد التحقق (validation)
            @if (old('car_brand_id', $car->car_brand_id))
                $('#car_brand_id').val('{{ old('car_brand_id', $car->car_brand_id) }}').trigger('change');
                setTimeout(function() {
                    $('#car_type_id').val('{{ old('car_type_id', $car->car_type_id) }}').trigger(
                    'change');
                    setTimeout(function() {
                        $('#car_trim_id').val('{{ old('car_trim_id', $car->car_trim_id) }}')
                            .trigger('change');
                    }, 200);
                }, 300);
            @endif

            // تحقق بسيط للتأكد من وجود النموذج (اختياري)
            console.log('النموذج موجود:', document.getElementById('carForm'));
        });
    </script>
@endpush --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>
    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                language: 'ar',
                dir: 'rtl',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // بيانات الأنواع والفئات
            var allTypes = @json($types);
            var allTrims = @json($trims);

            function filterTypes(brandId) {
                var options = '<option value=""></option>';
                if (brandId) {
                    allTypes.forEach(function(type) {
                        if (type.car_brand_id == brandId) {
                            options += `<option value="${type.id}">${type.name}</option>`;
                        }
                    });
                }
                $('#car_type_id').html(options).trigger('change.select2');
            }

            function filterTrims(typeId) {
                var options = '<option value=""></option>';
                if (typeId) {
                    allTrims.forEach(function(trim) {
                        if (trim.car_type_id == typeId) {
                            options += `<option value="${trim.id}">${trim.name}</option>`;
                        }
                    });
                }
                $('#car_trim_id').html(options).trigger('change.select2');
            }

            $('#car_brand_id').on('change', function() {
                var brandId = $(this).val();
                filterTypes(brandId);
                $('#car_trim_id').html('<option value=""></option>').trigger('change.select2');
            });

            $('#car_type_id').on('change', function() {
                var typeId = $(this).val();
                filterTrims(typeId);
            });

            @if (old('car_brand_id', $car->car_brand_id))
                $('#car_brand_id').val('{{ old('car_brand_id', $car->car_brand_id) }}').trigger('change');
                setTimeout(function() {
                    $('#car_type_id').val('{{ old('car_type_id', $car->car_type_id) }}').trigger(
                    'change');
                    setTimeout(function() {
                        $('#car_trim_id').val('{{ old('car_trim_id', $car->car_trim_id) }}')
                            .trigger('change');
                    }, 200);
                }, 300);
            @endif

            // ========== الحل النهائي لمشكلة الإرسال ==========
            // إزالة أي عوائق محتملة
            document.getElementById('carForm').onsubmit = null;
            if (typeof $ !== 'undefined') {
                $('#carForm').off('submit');
            }

            // ربط زر الحفظ بإرسال النموذج يدوياً
            document.getElementById('submitBtn').addEventListener('click', function(e) {
                e.preventDefault(); // منع أي سلوك افتراضي للزر
                document.getElementById('carForm').submit(); // إرسال النموذج
            });
        });
    </script>
@endpush
