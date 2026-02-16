@extends('layouts.admin')

@section('title', 'تعديل الحالة')

@section('page-title', 'تعديل الحالة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-statuses.index') }}">حالات السيارات</a></li>
<li class="breadcrumb-item active">تعديل الحالة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-edit me-2"></i>تعديل الحالة: {{ $status->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-statuses.update', $status->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الحالة <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $status->name) }}"
                                   placeholder="اسم الحالة"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="color" class="form-label">اللون <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" 
                                       name="color" 
                                       id="color" 
                                       class="form-control form-control-color @error('color') is-invalid @enderror"
                                       value="{{ old('color', $status->color) }}"
                                       title="اختر لوناً للحالة"
                                       required>
                                <input type="text" 
                                       id="color_text" 
                                       class="form-control @error('color') is-invalid @enderror"
                                       value="{{ old('color', $status->color) }}"
                                       placeholder="#4361ee"
                                       maxlength="7">
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="order" class="form-label">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', $status->order) }}"
                                   min="0"
                                   required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="color-preview p-3 rounded mb-3" style="background-color: {{ old('color', $status->color) }};">
                                <h6 class="mb-0 text-white text-center">عرض اللون المختار</h6>
                            </div>
                        </div>

                        <!-- معلومات الحالة الحالية -->
                        <div class="col-12">
                            <div class="info-section bg-light p-3 rounded mb-4">
                                <h6 class="mb-3"><i class="fas fa-history me-2"></i>معلومات الحالة الحالية</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">الاسم الحالي:</small>
                                            <div class="fw-bold">{{ $status->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">اللون الحالي:</small>
                                            <div class="d-flex align-items-center">
                                                <div class="color-box me-2" style="background-color: {{ $status->color }};"></div>
                                                <span class="fw-bold">{{ $status->color }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">الترتيب الحالي:</small>
                                            <div><span class="badge bg-secondary">{{ $status->order }}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <small class="text-muted">عدد السيارات:</small>
                                            <div><span class="badge bg-info">{{ $status->cars_count ?? 0 }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تحذير إذا كانت الحالة مرتبطة بسيارات -->
                        @if($status->cars_count > 0)
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>ملاحظة:</strong> هذه الحالة مرتبطة بــ <strong>{{ $status->cars_count }}</strong> سيارة. 
                                عند تعديل الحالة، سيتم تحديث جميع السيارات المرتبطة بهذه الحالة تلقائياً.
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.car-statuses.show', $status->id) }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-eye me-1"></i> عرض
                                    </a>
                                    <a href="{{ route('admin.car-statuses.index') }}" class="btn btn-outline-secondary">
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
.form-control-color {
    height: 45px;
    width: 70px;
    padding: 5px;
}

.color-preview {
    transition: background-color 0.3s ease;
    border: 1px solid #dee2e6;
}

.color-box {
    width: 30px;
    height: 30px;
    border-radius: 5px;
    border: 2px solid #dee2e6;
}

.info-section {
    border-left: 4px solid #4361ee;
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