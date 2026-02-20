<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                {{-- العلامة التجارية --}}
                <div class="form-group mb-3">
                    <label for="car_brand_id">العلامة التجارية <span class="text-danger">*</span></label>
                    <select name="car_brand_id" id="car_brand_id"
                        class="form-control select2 @error('car_brand_id') is-invalid @enderror"
                        data-placeholder="اختر العلامة التجارية" required>
                        <option value=""></option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('car_brand_id', $car->car_brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_brand_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- الموديل --}}
                <div class="form-group mb-3">
                    <label for="car_type_id">الموديل <span class="text-danger">*</span></label>
                    <select name="car_type_id" id="car_type_id"
                        class="form-control select2 @error('car_type_id') is-invalid @enderror"
                        data-placeholder="اختر الموديل" required>
                        <option value=""></option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" data-brand="{{ $type->car_brand_id }}"
                                {{ old('car_type_id', $car->car_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_type_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- الفئة --}}
                <div class="form-group mb-3">
                    <label for="car_trim_id">الفئة</label>
                    <select name="car_trim_id" id="car_trim_id"
                        class="form-control select2 @error('car_trim_id') is-invalid @enderror"
                        data-placeholder="اختر الفئة">
                        <option value=""></option>
                        @foreach ($trims as $trim)
                            <option value="{{ $trim->id }}" data-brand="{{ $trim->car_brand_id }}"
                                data-type="{{ $trim->car_type_id }}"
                                {{ old('car_trim_id', $car->car_trim_id ?? '') == $trim->id ? 'selected' : '' }}>
                                {{ $trim->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_trim_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                {{-- التصنيف --}}
                <div class="form-group mb-3">
                    <label for="car_category_id">التصنيف</label>
                    <select name="car_category_id" id="car_category_id"
                        class="form-control select2 @error('car_category_id') is-invalid @enderror"
                        data-placeholder="اختر التصنيف">
                        <option value=""></option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('car_category_id', $car->car_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- سنة الموديل --}}
                <div class="form-group mb-3">
                    <label for="model_year">سنة الموديل <span class="text-danger">*</span></label>
                    <select name="model_year" id="model_year"
                        class="form-control select2 @error('model_year') is-invalid @enderror"
                        data-placeholder="اختر السنة" required>
                        <option value=""></option>
                        @forelse($years as $year)
                            <option value="{{ $year }}"
                                {{ old('model_year', $car->model_year ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @empty
                            <option value="" disabled>لا توجد سنوات متاحة</option>
                        @endforelse
                    </select>
                    @error('model_year')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- اللون --}}
                <div class="form-group mb-3">
                    <label for="color">اللون <span class="text-danger">*</span></label>
                    <input type="text" name="color" id="color"
                        class="form-control @error('color') is-invalid @enderror"
                        value="{{ old('color', $car->color ?? '') }}" required>
                    @error('color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="condition">الحالة (جديد/مستعمل) <span class="text-danger">*</span></label>
                    <select name="condition" id="condition"
                        class="form-control @error('condition') is-invalid @enderror" required>
                        <option value=""></option>
                        @foreach ($conditions as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('condition', $car->condition ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('condition')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="mileage">الممشي (كم) <span class="text-danger">*</span></label>
                    <input type="number" name="mileage" id="mileage"
                        class="form-control @error('mileage') is-invalid @enderror"
                        value="{{ old('mileage', $car->mileage ?? 0) }}" min="0" required>
                    @error('mileage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="car_status_id">حالة السيارة (نظامية) <span class="text-danger">*</span></label>
                    <select name="car_status_id" id="car_status_id"
                        class="form-control select2 @error('car_status_id') is-invalid @enderror" required>
                        <option value=""></option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}"
                                {{ old('car_status_id', $car->car_status_id ?? '') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('car_status_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
