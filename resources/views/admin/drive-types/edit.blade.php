@extends('layouts.admin')

@section('title', 'تعديل نوع الدفع')

@section('page-title', 'تعديل نوع الدفع')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.drive-types.index') }}">أنواع الدفع</a></li>
<li class="breadcrumb-item active">تعديل النوع</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-edit me-2"></i>تعديل نوع الدفع: {{ $type->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.drive-types.update', $type->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم نوع الدفع <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $type->name) }}"
                                   placeholder="اسم نوع الدفع"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">الكود <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $type->code) }}"
                                   placeholder="كود مختصر"
                                   required
                                   maxlength="10">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- معلومات النوع الحالي -->
                        <div class="col-12">
                            <div class="info-section bg-light p-3 rounded mb-4">
                                <h6 class="mb-3"><i class="fas fa-history me-2"></i>معلومات النوع الحالي</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">الاسم الحالي:</small>
                                            <div class="fw-bold">{{ $type->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">الكود الحالي:</small>
                                            <div class="fw-bold"><code>{{ $type->code }}</code></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">تاريخ الإنشاء:</small>
                                            <div class="fw-bold">{{ $type->created_at->format('Y/m/d') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">عدد السيارات:</small>
                                            <div><span class="badge bg-info">{{ $type->cars_count ?? 0 }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تحذير إذا كان النوع مرتبط بسيارات -->
                        @if($type->cars_count > 0)
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>ملاحظة:</strong> هذا النوع مرتبط بــ <strong>{{ $type->cars_count }}</strong> سيارة. 
                                عند تعديل النوع، سيتم تحديث جميع السيارات المرتبطة بهذا النوع تلقائياً.
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.drive-types.show', $type->id) }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-eye me-1"></i> عرض
                                    </a>
                                    <a href="{{ route('admin.drive-types.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> إلغاء
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> حفظ التعديلات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-section {
    border-left: 4px solid #4361ee;
}

.alert {
    border-radius: 8px;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
}
</style>
@endpush