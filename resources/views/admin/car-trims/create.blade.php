@extends('layouts.admin')

@section('title', 'إضافة فئة سيارة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        إضافة فئة سيارة جديدة
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$brandId)
                    <!-- الخطوة 1: اختيار العلامة التجارية -->
                    <div class="step active" id="step1">
                        <div class="step-header mb-4">
                            <h6 class="step-number d-inline-flex align-items-center justify-content-center rounded-circle">1</h6>
                            <h4 class="d-inline ms-2">اختر العلامة التجارية</h4>
                        </div>
                        <div class="row">
                            @foreach($brands->chunk(ceil($brands->count() / 3)) as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $brand)
                                <a href="{{ route('admin.car-trims.create', ['brand_id' => $brand->id]) }}" 
                                   class="brand-card d-block p-3 mb-2 border rounded text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <div class="brand-logo me-3">
                                            <div class="logo-placeholder">
                                                {{ substr($brand->name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ $brand->name }}</h6>
                                            <small class="text-muted">اختر هذه العلامة</small>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.car-trims.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                            </a>
                        </div>
                    </div>
                    @elseif($brandId && !$typeId)
                    <!-- الخطوة 2: اختيار النوع -->
                    <div class="step active" id="step2">
                        <div class="step-header mb-4">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.car-trims.create') }}" class="btn btn-sm btn-outline-secondary me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div>
                                    <h6 class="step-number d-inline-flex align-items-center justify-content-center rounded-circle">2</h6>
                                    <h4 class="d-inline ms-2">اختر نوع السيارة</h4>
                                    <p class="text-muted mb-0">
                                        العلامة التجارية: <strong>{{ $brands->find($brandId)->name ?? 'غير معروف' }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if($types->count() > 0)
                        <div class="row">
                            @foreach($types->chunk(ceil($types->count() / 3)) as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $type)
                                <a href="{{ route('admin.car-trims.create', ['brand_id' => $brandId, 'type_id' => $type->id]) }}" 
                                   class="type-card d-block p-3 mb-2 border rounded text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <div class="type-icon me-3">
                                            <i class="fas fa-car text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ $type->name }}</h6>
                                            <small class="text-muted">اختر هذا النوع</small>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            لا يوجد أنواع مسجلة لهذه العلامة التجارية.
                        </div>
                        @endif
                        <div class="mt-4">
                            <a href="{{ route('admin.car-trims.create') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> العودة لاختيار العلامة
                            </a>
                        </div>
                    </div>
                    @else
                    <!-- الخطوة 3: إدخال بيانات الفئة -->
                    <div class="step active" id="step3">
                        <div class="step-header mb-4">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.car-trims.create', ['brand_id' => $brandId]) }}" class="btn btn-sm btn-outline-secondary me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div>
                                    <h6 class="step-number d-inline-flex align-items-center justify-content-center rounded-circle">3</h6>
                                    <h4 class="d-inline ms-2">إدخال بيانات الفئة</h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-tag me-1"></i> 
                                        <strong>{{ $brands->find($brandId)->name ?? 'غير معروف' }}</strong> → 
                                        <strong>{{ $types->find($typeId)->name ?? 'غير معروف' }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.car-trims.store') }}" method="POST">
                            @csrf
                            
                            <input type="hidden" name="car_brand_id" value="{{ $brandId }}">
                            <input type="hidden" name="car_type_id" value="{{ $typeId }}">
                            
                            <div class="info-card bg-light p-3 rounded mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>العلامة التجارية:</strong> 
                                        <span class="text-primary">{{ $brands->find($brandId)->name ?? 'غير معروف' }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>النوع:</strong> 
                                        <span class="text-primary">{{ $types->find($typeId)->name ?? 'غير معروف' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required
                                           placeholder="مثال: SE, Limited, Sport...">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">اسم الفئة مثل: SE, Limited, Sport, LX, EX</small>
                                </div>

                                <div class="col-md-12">
                                    <label for="code" class="form-label">الكود (اختياري)</label>
                                    <input type="text" name="code" id="code" 
                                           class="form-control @error('code') is-invalid @enderror"
                                           value="{{ old('code') }}"
                                           placeholder="مثال: CAMRY_SE, LEXUS_ES250">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">كود مميز للفئة يمكن استخدامه للبحث</small>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between gap-2">
                                        <a href="{{ route('admin.car-trims.create', ['brand_id' => $brandId]) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> العودة لاختيار النوع
                                        </a>
                                        <div>
                                            <a href="{{ route('admin.car-trims.index') }}" class="btn btn-outline-secondary me-2">
                                                إلغاء
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> حفظ الفئة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.step-header {
    border-bottom: 2px solid #4361ee;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.step-number {
    width: 40px;
    height: 40px;
    background: #4361ee;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}

.brand-card, .type-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.brand-card:hover, .type-card:hover {
    background-color: #f8f9fa;
    border-color: #4361ee;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.logo-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.type-icon {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.info-card {
    border-left: 4px solid #4361ee;
}

/* على الشاشات الصغيرة */
@media (max-width: 768px) {
    .step-header h4 {
        font-size: 1.3rem;
    }
    
    .brand-card, .type-card {
        padding: 15px;
    }
    
    .logo-placeholder, .type-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto-generate code based on brand, type and trim name
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    if (nameInput && codeInput) {
        nameInput.addEventListener('input', function() {
            if (!codeInput.value) {
                // يمكن إضافة توليد كود تلقائي هنا إذا أردت
                // codeInput.value = generateCode(this.value);
            }
        });
    }
});
</script>
@endpush