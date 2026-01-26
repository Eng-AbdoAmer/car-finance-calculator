@extends('layouts.admin')

@section('title', 'تعديل براند')

@section('page-title', 'تعديل براند')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">براندات السيارات</a></li>
<li class="breadcrumb-item active">تعديل براند</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-edit me-2"></i>تعديل براند: {{ $brand->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-brands.update', $brand->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم البراند <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $brand->name) }}"
                               placeholder="أدخل اسم البراند (مثال: تويوتا)"
                               required
                               autofocus>
                        <div class="form-text">أدخل اسم البراند باللغة العربية أو الإنجليزية.</div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ التغييرات
                        </button>
                        <a href="{{ route('admin.car-brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection