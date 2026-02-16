@extends('layouts.admin')

@section('title', 'إضافة حالة سيارة جديدة')

@section('page-title', 'إضافة حالة سيارة جديدة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-statuses.index') }}">حالات السيارات</a></li>
<li class="breadcrumb-item active">إضافة حالة جديدة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus me-2"></i>إضافة حالة سيارة جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-statuses.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الحالة <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="مثال: جديد، مستعمل، بحالة جيدة"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اسم الحالة يجب أن يكون فريداً</small>
                        </div>

                        <div class="col-md-6">
                            <label for="color" class="form-label">اللون <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" 
                                       name="color" 
                                       id="color" 
                                       class="form-control form-control-color @error('color') is-invalid @enderror"
                                       value="{{ old('color', '#4361ee') }}"
                                       title="اختر لوناً للحالة"
                                       required>
                                <input type="text" 
                                       id="color_text" 
                                       class="form-control @error('color') is-invalid @enderror"
                                       value="{{ old('color', '#4361ee') }}"
                                       placeholder="#4361ee"
                                       maxlength="7">
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اختر لوناً يميز هذه الحالة</small>
                        </div>

                        <div class="col-md-6">
                            <label for="order" class="form-label">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', 0) }}"
                                   min="0"
                                   required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">رقم الترتيب لعرض الحالات (الأقل يظهر أولاً)</small>
                        </div>

                        <div class="col-12">
                            <div class="color-preview p-3 rounded mb-3" style="background-color: {{ old('color', '#4361ee') }};">
                                <h6 class="mb-0 text-white text-center">عرض اللون المختار</h6>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.car-statuses.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> حفظ الحالة
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

@push('styles')
<style>
.form-control-color {
    height: 45px;
    width: 70px;
    padding: 5px;
}

.color-preview {
    transition: background-color 0.3s ease;
    border: 1px solid #dee2e6;
}

.input-group {
    gap: 5px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color_text');
    const colorPreview = document.querySelector('.color-preview');
    
    // تحديث نص اللون عند تغيير اللون
    colorInput.addEventListener('input', function() {
        colorText.value = this.value;
        colorPreview.style.backgroundColor = this.value;
    });
    
    // تحديث اللون عند تغيير النص
    colorText.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
            colorInput.value = this.value;
            colorPreview.style.backgroundColor = this.value;
        }
    });
    
    // تحديث العرض الأولي
    colorPreview.style.backgroundColor = colorInput.value;
});
</script>
@endpush