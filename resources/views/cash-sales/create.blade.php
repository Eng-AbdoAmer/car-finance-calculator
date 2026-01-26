@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <x-navbar />
    
    <div class="container position-relative z-index-2">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <div class="hero-badge mb-4 animate__animated animate__bounceIn">
                        <span class="badge bg-gradient-primary rounded-pill px-4 py-2">
                            <i class="fas fa-plus-circle me-2"></i>إضافة بيع جديد
                        </span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                        إضافة <span class="text-gradient-primary">عملية بيع نقدي</span>
                    </h1>
                </div>
                
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                    أضف عملية بيع جديدة للنظام مع جميع التفاصيل المطلوبة
                </p>
            </div>
        </div>
    </div>
    
    <!-- خلفية متحركة -->
    <div class="hero-background">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
</section>

<!-- Create Form Section -->
<section class="create-sale-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-cash-register fa-2x"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-0">نموذج إضافة عملية بيع جديدة</h4>
                                <p class="mb-0 opacity-75">املأ جميع الحقول المطلوبة لإضافة العملية</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('cash-sales.store') }}" id="createSaleForm">
                            @csrf
                            
                            <div class="row g-4">
                                <!-- معلومات السيارة -->
                                <div class="col-12">
                                    <div class="section-header mb-3">
                                        <h5 class="fw-bold text-primary mb-0">
                                            <i class="fas fa-car me-2"></i>معلومات السيارة
                                        </h5>
                                        <div class="border-bottom border-primary border-2 mt-2" style="width: 60px;"></div>
                                    </div>
                                </div>
                                
                                <!-- ماركة السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-car me-2 text-primary"></i>ماركة السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="car_brand_id" id="car_brand_id" class="form-select form-select-lg @error('car_brand_id') is-invalid @enderror" required>
                                            <option value="">اختر ماركة السيارة</option>
                                            @foreach($carBrands as $brand)
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
                                
                                <!-- موديل السيارة -->
                                <!-- موديل السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tags me-2 text-primary"></i>موديل السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="car_model_id" id="car_model_id" class="form-select form-select-lg @error('car_model_id') is-invalid @enderror" required>
                                            <option value="">اختر ماركة أولاً</option>
                                            @foreach($models as $model)
                                               <option value="{{ $model->id }}" {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
                                                {{ $model->model_year }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('car_model_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- لون السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-palette me-2 text-primary"></i>لون السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="text" name="car_color" class="form-control @error('car_color') is-invalid @enderror"
                                                   value="{{ old('car_color') }}" placeholder="أدخل لون السيارة" required>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#colorPickerModal">
                                                <i class="fas fa-eye-dropper"></i>
                                            </button>
                                        </div>
                                        @error('car_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- فئة السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tags me-2 text-primary"></i>فئة السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="car_category" class="form-control form-control-lg @error('car_category') is-invalid @enderror"
                                               value="{{ old('car_category') }}" placeholder="مثال: سيدان فاخرة" required>
                                        @error('car_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- رقم الهاتف -->
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label fw-bold">
            <i class="fas fa-phone me-2 text-primary"></i>رقم الهاتف
            <span class="text-danger">*</span>
        </label>
        <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror"
               value="{{ old('phone') }}" placeholder="أدخل رقم الهاتف" required>
        @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
                                <!-- مصدر السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-warehouse me-2 text-primary"></i>مصدر السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="source" id="in_stock" 
                                                       value="in_stock" {{ old('source', 'in_stock') == 'in_stock' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="in_stock">
                                                    <i class="fas fa-warehouse me-1"></i>من المخزون
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="source" id="external_purchase" 
                                                       value="external_purchase" {{ old('source') == 'external_purchase' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="external_purchase">
                                                    <i class="fas fa-truck me-1"></i>مشتريات خارجية
                                                </label>
                                            </div>
                                        </div>
                                        @error('source')
                                        <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- المعلومات المالية -->
                                <div class="col-12 mt-4">
                                    <div class="section-header mb-3">
                                        <h5 class="fw-bold text-primary mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>المعلومات المالية
                                        </h5>
                                        <div class="border-bottom border-primary border-2 mt-2" style="width: 60px;"></div>
                                    </div>
                                </div>
                                
                                <!-- سعر السيارة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tag me-2 text-primary"></i>سعر السيارة (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" name="car_price" class="form-control @error('car_price') is-invalid @enderror"
                                                   value="{{ old('car_price') }}" placeholder="0.00" step="0.01" min="0" required
                                                   oninput="calculateRemaining()">
                                            <span class="input-group-text bg-primary text-white">ر.س</span>
                                        </div>
                                        @error('car_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- المبلغ المدفوع -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-money-check-alt me-2 text-primary"></i>المبلغ المدفوع (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror"
                                                   value="{{ old('paid_amount', 0) }}" placeholder="0.00" step="0.01" min="0" required
                                                   oninput="calculateRemaining()">
                                            <span class="input-group-text bg-primary text-white">ر.س</span>
                                        </div>
                                        @error('paid_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- المبلغ المتبقي (محسوب تلقائياً) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calculator me-2 text-primary"></i>المبلغ المتبقي (ريال)
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" id="remaining_amount" class="form-control bg-light" 
                                                   value="0.00" readonly>
                                            <span class="input-group-text bg-success text-white">ر.س</span>
                                        </div>
                                        <small class="text-muted">يتم حساب المبلغ المتبقي تلقائياً</small>
                                    </div>
                                </div>
                                
                                <!-- البنك -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-university me-2 text-primary"></i>البنك (اختياري)
                                        </label>
                                        <select name="bank_id" class="form-select form-select-lg @error('bank_id') is-invalid @enderror">
                                            <option value="">اختر البنك</option>
                                            @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- ملاحظات -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-sticky-note me-2 text-primary"></i>ملاحظات (اختياري)
                                        </label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                                  rows="3" placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- أزرار التقديم -->
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cash-sales.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                            <i class="fas fa-arrow-right me-2"></i>رجوع للقائمة
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4 py-2">
                                            <i class="fas fa-save me-2"></i>حفظ العملية
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
</section>

<!-- Color Picker Modal -->
<div class="modal fade" id="colorPickerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-palette me-2"></i>اختيار اللون
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    @php
                        $commonColors = [
                            'أحمر' => '#dc3545',
                            'أزرق' => '#0d6efd',
                            'أخضر' => '#198754',
                            'أسود' => '#212529',
                            'أبيض' => '#ffffff',
                            'فضي' => '#c0c0c0',
                            'رمادي' => '#6c757d',
                            'ذهبي' => '#ffd700',
                            'فضي داكن' => '#a9a9a9',
                            'أزرق داكن' => '#00008b',
                            'بني' => '#8b4513',
                            'بيج' => '#f5f5dc',
                            'برتقالي' => '#fd7e14',
                            'أصفر' => '#ffc107'
                        ];
                    @endphp
                    
                    @foreach($commonColors as $name => $color)
                    <div class="col-3">
                        <button type="button" class="btn w-100 p-3 mb-2 border" 
                                style="background-color: {{ $color }}; color: {{ $color == '#ffffff' ? '#000' : '#fff' }}"
                                onclick="selectColor('{{ $name }}')">
                            {{ $name }}
                        </button>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-3">
                    <label class="form-label">أو أدخل لون مخصص:</label>
                    <div class="input-group">
                        <input type="text" id="customColor" class="form-control" placeholder="#000000 أو اسم اللون">
                        <button type="button" class="btn btn-outline-primary" onclick="selectCustomColor()">تطبيق</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Buttons -->
<div class="floating-buttons">
    <a href="{{ route('cash-sales.index') }}" class="floating-btn back-btn">
        <i class="fas fa-arrow-right"></i>
        <span class="tooltip">رجوع للقائمة</span>
    </a>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    color: white;
    min-height: 40vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.text-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-background .shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.hero-background .shape-1 {
    width: 400px;
    height: 400px;
    background: var(--primary-color);
    top: -200px;
    right: -100px;
    animation: float 20s infinite ease-in-out;
}

.hero-background .shape-2 {
    width: 300px;
    height: 300px;
    background: #ff6b6b;
    bottom: -150px;
    left: -50px;
    animation: float 25s infinite ease-in-out reverse;
}

.hero-background .shape-3 {
    width: 200px;
    height: 200px;
    background: #4cc9f0;
    top: 30%;
    left: 10%;
    animation: float 30s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Form Styling */
.create-sale-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 60vh;
}

.form-select-lg, .form-control-lg {
    border-radius: 10px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-select-lg:focus, .form-control-lg:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    transform: translateY(-2px);
}

.form-check-input:checked {
    background-color: #4361ee;
    border-color: #4361ee;
}

.section-header h5 {
    position: relative;
    padding-bottom: 10px;
}

.input-group-text {
    border-radius: 0 10px 10px 0;
}

.input-group .form-control {
    border-radius: 10px 0 0 10px;
}

/* Card Header Gradient */
.card-header.bg-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
}

/* Floating Buttons */
.floating-buttons {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 1000;
}

.floating-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: white;
    font-size: 24px;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    margin-bottom: 15px;
}

.back-btn {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.floating-btn:hover {
    transform: scale(1.1);
    color: white;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.floating-btn .tooltip {
    position: absolute;
    right: 70px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    white-space: nowrap;
    font-weight: 500;
}

.floating-btn:hover .tooltip {
    opacity: 1;
    visibility: visible;
    right: 75px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 30vh;
        padding: 60px 0 30px;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .floating-buttons {
        bottom: 20px;
        left: 20px;
    }
    
    .floating-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .floating-btn .tooltip {
        display: none;
    }
}

@media (max-width: 576px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function calculateRemaining() {
    const price = parseFloat(document.querySelector('input[name="car_price"]').value) || 0;
    const paid = parseFloat(document.querySelector('input[name="paid_amount"]').value) || 0;
    const remaining = Math.max(0, price - paid);
    
    document.getElementById('remaining_amount').value = remaining.toFixed(2);
}

function selectColor(colorName) {
    document.querySelector('input[name="car_color"]').value = colorName;
    $('#colorPickerModal').modal('hide');
}

function selectCustomColor() {
    const customColor = document.getElementById('customColor').value;
    if (customColor) {
        document.querySelector('input[name="car_color"]').value = customColor;
        $('#colorPickerModal').modal('hide');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // حساب المبلغ المتبقي عند التحميل
    calculateRemaining();
    
    // التحقق من صحة النموذج قبل الإرسال
    const form = document.getElementById('createSaleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const price = parseFloat(document.querySelector('input[name="car_price"]').value) || 0;
            const paid = parseFloat(document.querySelector('input[name="paid_amount"]').value) || 0;
            
            if (paid > price) {
                e.preventDefault();
                alert('المبلغ المدفوع لا يمكن أن يكون أكبر من سعر السيارة');
                return false;
            }
            
            if (price <= 0) {
                e.preventDefault();
                alert('سعر السيارة يجب أن يكون أكبر من صفر');
                return false;
            }
            
            return true;
        });
    }
    
  
});
</script>
{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('car_brand_id');
    const modelSelect = document.getElementById('car_model_id');
    
    // تخزين جميع الموديلات في متغير
    const allModels = @json($models);
    
    // عند تغيير الماركة
    if (brandSelect) {
        brandSelect.addEventListener('change', function() {
            const brandId = this.value;
            
            if (brandId) {
                // تفريغ قائمة الموديلات
                modelSelect.innerHTML = '<option value="">اختر الموديل</option>';
                
                // تصفية الموديلات بناءً على الماركة المختارة
                const filteredModels = allModels.filter(model => model.car_brand_id == brandId);
                
                if (filteredModels.length > 0) {
                    filteredModels.forEach(model => {
                        const option = document.createElement('option');
                        option.value = model.id;
                        option.textContent = `${model.name} (${model.model_year})`;
                        modelSelect.appendChild(option);
                    });
                } else {
                    modelSelect.innerHTML = '<option value="">لا توجد موديلات لهذه الماركة</option>';
                }
                
                modelSelect.disabled = false;
            } else {
                modelSelect.innerHTML = '<option value="">اختر ماركة أولاً</option>';
                modelSelect.disabled = true;
            }
        });
        
        // تشغيل الحدث عند التحميل إذا كان هناك ماركة محددة
        @if(old('car_brand_id'))
            const oldBrandId = "{{ old('car_brand_id') }}";
            if (oldBrandId) {
                brandSelect.value = oldBrandId;
                brandSelect.dispatchEvent(new Event('change'));
            }
        @endif
    }
});
</script> --}}
@endpush