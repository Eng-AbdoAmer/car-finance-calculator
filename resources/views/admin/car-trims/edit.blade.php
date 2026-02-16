@extends('layouts.admin')

@section('title', 'تعديل فئة السيارة: ' . $trim->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تعديل فئة السيارة: {{ $trim->full_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.car-trims.update', $trim) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="car_brand_id" class="form-label">العلامة التجارية <span class="text-danger">*</span></label>
                                <select name="car_brand_id" id="car_brand_id" class="form-select @error('car_brand_id') is-invalid @enderror" required>
                                    <option value="">اختر العلامة التجارية</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" 
                                            {{ (old('car_brand_id', $trim->car_brand_id) == $brand->id) ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('car_brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $trim->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="code" class="form-label">الكود</label>
                                <input type="text" name="code" id="code" 
                                       class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code', $trim->code) }}">
                                <small class="text-muted">كود مميز للفئة (اختياري)</small>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.car-trims.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> رجوع
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection