@extends('layouts.admin')

@section('title', 'إضافة نوع سيارة جديد')

@section('page-title', 'إضافة نوع سيارة جديد')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">براندات السيارات</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.car-types.index') }}">أنواع السيارات</a></li>
<li class="breadcrumb-item active">إضافة جديد</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus-circle me-2"></i>إضافة نوع سيارة جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">اسم النوع <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="أدخل اسم نوع السيارة"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="car_brand_id" class="form-label">البراند <span class="text-danger">*</span></label>
                            <select class="form-control @error('car_brand_id') is-invalid @enderror" 
                                    id="car_brand_id" 
                                    name="car_brand_id"
                                    required>
                                <option value="">اختر البراند</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('car_brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.car-types.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>حفظ النوع
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection