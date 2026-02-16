@extends('layouts.admin')

@section('title', 'إضافة نوع جير جديد')

@section('page-title', 'إضافة نوع جير جديد')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.transmission-types.index') }}">أنواع الجير</a></li>
<li class="breadcrumb-item active">إضافة نوع جديد</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus me-2"></i>إضافة نوع جير جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.transmission-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">اسم نوع الجير <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="أدخل اسم نوع الجير (مثال: أوتوماتيك، مانوال، DSG)"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">اسم نوع الجير يجب أن يكون فريداً</small>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-card bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-info-circle me-2"></i>أنواع الجير الشائعة:</h6>
                                <ul class="mb-0 ps-3">
                                    <li>أوتوماتيك (Automatic)</li>
                                    <li>مانوال (Manual)</li>
                                    <li>نصف أوتوماتيك</li>
                                    <li>CVT</li>
                                    <li>DSG</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-card bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-lightbulb me-2"></i>نصائح:</h6>
                                <ul class="mb-0 ps-3">
                                    <li>استخدم أسماء واضحة ومعروفة</li>
                                    <li>تأكد من عدم تكرار الاسم</li>
                                    <li>يمكن إضافة أنواع جديدة حسب الحاجة</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.transmission-types.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> حفظ النوع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-card {
    border-left: 4px solid #4361ee;
}

.form-control-lg {
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1.1rem;
}

.form-control-lg:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
}
</style>
@endpush