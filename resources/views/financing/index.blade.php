@extends('layouts.app')

@section('title', 'حاسبة تمويل السيارات')

@section('content')
    <x-navbar />
    
    <div class="calculator-wrapper">
        <!-- رأس الصفحة -->
        <div class="calculator-header">
            <div class="header-content">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h1 class="display-5 fw-bold text-primary">
                            <i class="fas fa-calculator me-2"></i>حاسبة تمويل السيارات
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>احسب قسط سيارتك بكل سهولة ودقة
                        </p>
                    </div>
                    {{-- <div class="header-stats">
                        <div class="stat-item">
                            <i class="fas fa-history"></i>
                            <span>سجل الحسابات</span>
                            <strong>{{ auth()->user()->carFinancingRequests()->count() ?? 0 }}</strong>
                        </div>
                    </div> --}}
                </div>
                
                <div class="progress-steps">
                    <div class="step active">
                        <span class="step-number">1</span>
                        <span class="step-label">معلومات العميل</span>
                    </div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <span class="step-label">معلومات البنك</span>
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span class="step-label">معلومات السيارة</span>
                    </div>
                    <div class="step">
                        <span class="step-number">4</span>
                        <span class="step-label">معلومات التمويل</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- رسائل الأخطاء -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-2">يوجد أخطاء في المدخلات</h5>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- حاسبة التمويل -->
        <div class="calculator-card">
            <form action="{{ route('financing.calculate') }}" method="POST" id="financingForm" class="needs-validation" novalidate>
                @csrf
                
                <!-- معلومات العميل -->
                <div class="form-section" id="client-info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-title">
                            <h4>معلومات العميل</h4>
                            <p class="text-muted mb-0">أدخل معلومات الاتصال الخاصة بك</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <div class="form-floating mb-3">
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone"
                                   placeholder="رقم الهاتف"
                                   pattern="[0-9]{10,15}"
                                   value="{{ old('phone') }}"
                                   required>
                            <label for="phone">
                                <i class="fas fa-phone me-2"></i>رقم الهاتف
                            </label>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                يجب أن يكون الرقم بين 10 و 15 رقماً
                            </div>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                يرجى إدخال رقم هاتف صحيح
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات البنك والنسب -->
                <div class="form-section" id="bank-info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="section-title">
                            <h4>معلومات البنك والنسب</h4>
                            <p class="text-muted mb-0">اختر البنك وأدخل نسب التمويل</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <!-- اختيار البنك -->
                        <div class="form-floating mb-4">
                            <select class="form-select" id="bank_id" name="bank_id" required>
                                <option value="">اختر البنك</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" 
                                            data-murabaha="{{ $bank->murabaha_rate ?? 4.5 }}"
                                            data-insurance="{{ $bank->insurance_rate ?? 3.5 }}"
                                            data-last-payment="{{ $bank->last_payment_rate ?? 5 }}"
                                            {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="bank_id">
                                <i class="fas fa-bank me-2"></i>اختر البنك
                            </label>
                        </div>
                        
                        <!-- نسب التمويل -->
                        <div class="rates-grid">
                            <div class="rate-card">
                                <div class="rate-header">
                                    <i class="fas fa-percentage rate-icon"></i>
                                    <span class="rate-title">نسبة المرابحة</span>
                                </div>
                                <div class="rate-body">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control rate-input" 
                                               id="murabaha_rate" 
                                               name="murabaha_rate" 
                                               step="0.01" 
                                               min="0" 
                                               max="100"
                                               value="{{ old('murabaha_rate', 4.50) }}"
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="rate-info">
                                        <small class="text-muted">تؤثر على إجمالي الفائدة</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rate-card">
                                <div class="rate-header">
                                    <i class="fas fa-shield-alt rate-icon"></i>
                                    <span class="rate-title">نسبة التأمين</span>
                                </div>
                                <div class="rate-body">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control rate-input" 
                                               id="insurance_rate" 
                                               name="insurance_rate" 
                                               step="0.01" 
                                               min="0" 
                                               max="100"
                                               value="{{ old('insurance_rate', 3.50) }}"
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="rate-info">
                                        <small class="text-muted">تغطية تأمينية شاملة</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rate-card">
                                <div class="rate-header">
                                    <i class="fas fa-money-bill-wave rate-icon"></i>
                                    <span class="rate-title">نسبة الدفعة الأخيرة</span>
                                </div>
                                <div class="rate-body">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control rate-input" 
                                               id="last_payment_rate" 
                                               name="last_payment_rate" 
                                               step="0.01" 
                                               min="0" 
                                               max="100"
                                               value="{{ old('last_payment_rate', 5.00) }}"
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="rate-info">
                                        <small class="text-muted">تسدد في نهاية المدة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات السيارة -->
                <div class="form-section" id="car-info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="section-title">
                            <h4>معلومات السيارة</h4>
                            <p class="text-muted mb-0">اختر نوع وموديل السيارة</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <div class="car-selection-grid">
                            <!-- الماركة -->
                            <div class="selection-card">
                                <div class="selection-header">
                                    <i class="fas fa-tag"></i>
                                    <span>العلامة التجارية</span>
                                </div>
                                <div class="selection-body">
                                    <select class="form-select" id="car_brand_id" name="car_brand_id" required>
                                        <option value="">اختر الماركة</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('car_brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- الموديل -->
                            <div class="selection-card">
                                <div class="selection-header">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>موديل السيارة</span>
                                </div>
                                <div class="selection-body">
                                    <select class="form-select" id="car_model_id" name="car_model_id" required>
                                        <option value="">اختر الموديل</option>
                                        @foreach ($models as $model)
                                            <option value="{{ $model->id }}" {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
                                                {{ $model->model_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- سعر السيارة -->
                            <div class="selection-card">
                                <div class="selection-header">
                                    <i class="fas fa-tag"></i>
                                    <span>سعر السيارة</span>
                                </div>
                                <div class="selection-body">
                                    <div class="price-input-wrapper">
                                        <input type="number" 
                                               class="form-control price-input" 
                                               id="car_price" 
                                               name="car_price" 
                                               required
                                               min="10000" 
                                               max="10000000"
                                               value="{{ old('car_price', 116000) }}">
                                        <span class="input-group-text currency">ر.س</span>
                                    </div>
                                    <div class="price-slider">
                                        <input type="range" 
                                               class="form-range" 
                                               min="10000" 
                                               max="1000000" 
                                               step="1000"
                                               value="{{ old('car_price', 116000) }}"
                                               id="car_price_slider">
                                    </div>
                                    <div class="price-range">
                                        <small>10,000 ر.س</small>
                                        <small>1,000,000 ر.س</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات التمويل -->
                <div class="form-section" id="finance-info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="section-title">
                            <h4>معلومات التمويل</h4>
                            <p class="text-muted mb-0">أدخل تفاصيل خطة التمويل</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <!-- نسبة الدفعة الأولى -->
                        <div class="finance-card">
                            <div class="finance-header">
                                <i class="fas fa-hand-holding-usd"></i>
                                <div>
                                    <h6>نسبة الدفعة الأولى</h6>
                                    <small class="text-muted">حدد نسبة الدفعة الأولى</small>
                                </div>
                                <div class="percentage-display" id="down_payment_percentage_display">
                                    {{ old('down_payment_percentage', 20) }}%
                                </div>
                            </div>
                            <div class="finance-body">
                                <div class="percentage-slider">
                                    <input type="range" 
                                           class="form-range" 
                                           id="down_payment_percentage_slider"
                                           min="0" 
                                           max="100" 
                                           step="1"
                                           value="{{ old('down_payment_percentage', 20) }}">
                                    <div class="percentage-markers">
                                        <span>0%</span>
                                        <span>25%</span>
                                        <span>50%</span>
                                        <span>75%</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                                <input type="hidden" 
                                       id="down_payment_percentage" 
                                       name="down_payment_percentage"
                                       value="{{ old('down_payment_percentage', 20) }}">
                            </div>
                        </div>
                        
                        <!-- مدة التمويل -->
                        <div class="finance-card">
                            <div class="finance-header">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <h6>مدة التمويل</h6>
                                    <small class="text-muted">اختر فترة سداد التمويل</small>
                                </div>
                                <div class="duration-display" id="duration_display">
                                    3 سنوات
                                </div>
                            </div>
                            <div class="finance-body">
                                <div class="duration-options">
                                    @foreach([1, 2, 3, 4, 5] as $year)
                                        <button type="button" 
                                                class="duration-option {{ old('duration_years', 3) == $year ? 'active' : '' }}"
                                                data-years="{{ $year }}">
                                            {{ $year }} سنة
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" 
                                       id="duration_years" 
                                       name="duration_years"
                                       value="{{ old('duration_years', 3) }}">
                            </div>
                        </div>
                        
                        <!-- النتائج الفورية -->
                        <div class="instant-results">
                            <div class="result-card">
                                <div class="result-header">
                                    <i class="fas fa-money-check-alt"></i>
                                    <span>الدفعة الأولى</span>
                                </div>
                                <div class="result-value" id="down_payment_result">
                                    0 ر.س
                                </div>
                            </div>
                            
                            <div class="result-card">
                                <div class="result-header">
                                    <i class="fas fa-handshake"></i>
                                    <span>مبلغ التمويل</span>
                                </div>
                                <div class="result-value" id="financing_amount_result">
                                    0 ر.س
                                </div>
                            </div>
                            
                            <div class="result-card">
                                <div class="result-header">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>القسط الشهري</span>
                                </div>
                                <div class="result-value highlight" id="monthly_installment_result">
                                    0 ر.س
                                </div>
                            </div>
                            
                            <div class="result-card">
                                <div class="result-header">
                                    <i class="fas fa-flag-checkered"></i>
                                    <span>الدفعة الأخيرة</span>
                                </div>
                                <div class="result-value" id="last_payment_result">
                                    0 ر.س
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أزرار التحكم -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary" id="resetForm">
                            <i class="fas fa-redo me-2"></i>إعادة تعيين
                        </button>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('financing.history') }}" class="btn btn-outline-primary">
                                <i class="fas fa-history me-2"></i>سجل الحسابات
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="calculateBtn">
                                <i class="fas fa-calculator me-2"></i>
                                <span>احسب التمويل</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- البطاقة الجانبية للنتائج -->
        <div class="results-sidebar">
            <div class="sidebar-header">
                <h5><i class="fas fa-chart-bar me-2"></i>التقدير السريع</h5>
                <button class="btn btn-sm btn-outline-secondary" id="toggleSidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <div class="sidebar-body">
                <div class="estimation-card">
                    <div class="estimation-header">
                        <i class="fas fa-clock"></i>
                        <span>النتائج التقديرية</span>
                    </div>
                    <div class="estimation-body">
                        <div class="estimation-item">
                            <span>القسط الشهري</span>
                            <div class="estimation-value" id="estimated_monthly">0 ر.س</div>
                        </div>
                        <div class="estimation-item">
                            <span>إجمالي التمويل</span>
                            <div class="estimation-value" id="estimated_total">0 ر.س</div>
                        </div>
                        <div class="estimation-item">
                            <span>إجمالي الفوائد</span>
                            <div class="estimation-value" id="estimated_interest">0 ر.س</div>
                        </div>
                        <div class="estimation-item">
                            <span>نسبة الزيادة</span>
                            <div class="estimation-value" id="estimated_increase">0%</div>
                        </div>
                    </div>
                </div>
                
                <div class="tip-card">
                    <div class="tip-header">
                        <i class="fas fa-lightbulb"></i>
                        <span>نصائح ذهبية</span>
                    </div>
                    <div class="tip-body">
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>زيادة الدفعة الأولى تقلل من الفائدة الإجمالية</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>المدة الأقصر = فائدة أقل</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>قارن بين عروض البنوك المختلفة</span>
                        </div>
                    </div>
                </div>
                
                <div class="history-preview">
                    <div class="history-header">
                        <i class="fas fa-history"></i>
                        <span>آخر الحسابات</span>
                        <a href="{{ route('financing.history') }}" class="btn btn-sm btn-link">عرض الكل</a>
                    </div>
                    {{-- <div class="history-body">
                        @php
                            $recentCalculations = auth()->user()->carFinancingRequests()->latest()->take(3)->get();
                        @endphp
                        
                        @if($recentCalculations->count() > 0)
                            @foreach($recentCalculations as $calculation)
                                <div class="history-item">
                                    <div class="history-info">
                                        <strong>{{ $calculation->brand->name ?? 'غير محدد' }}</strong>
                                        <small>{{ $calculation->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="history-amount">
                                        {{ number_format($calculation->monthly_installment, 0) }} ر.س
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-calculator fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">لا توجد حسابات سابقة</p>
                            </div>
                        @endif
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // العناصر الرئيسية
                const form = document.getElementById('financingForm');
                const carPriceInput = document.getElementById('car_price');
                const carPriceSlider = document.getElementById('car_price_slider');
                const downPaymentSlider = document.getElementById('down_payment_percentage_slider');
                const downPaymentDisplay = document.getElementById('down_payment_percentage_display');
                const downPaymentHidden = document.getElementById('down_payment_percentage');
                const durationOptions = document.querySelectorAll('.duration-option');
                const durationHidden = document.getElementById('duration_years');
                const durationDisplay = document.getElementById('duration_display');
                const bankSelect = document.getElementById('bank_id');
                const resetBtn = document.getElementById('resetForm');
                const calculateBtn = document.getElementById('calculateBtn');
                const toggleSidebarBtn = document.getElementById('toggleSidebar');
                const sidebar = document.querySelector('.results-sidebar');
                
                // عناصر النتائج
                const resultElements = {
                    downPayment: document.getElementById('down_payment_result'),
                    financingAmount: document.getElementById('financing_amount_result'),
                    monthlyInstallment: document.getElementById('monthly_installment_result'),
                    lastPayment: document.getElementById('last_payment_result'),
                    estimatedMonthly: document.getElementById('estimated_monthly'),
                    estimatedTotal: document.getElementById('estimated_total'),
                    estimatedInterest: document.getElementById('estimated_interest'),
                    estimatedIncrease: document.getElementById('estimated_increase')
                };
                
                // القيم الحالية
                let currentValues = {
                    carPrice: parseFloat(carPriceInput.value) || 0,
                    downPaymentPercentage: parseFloat(downPaymentHidden.value) || 20,
                    murabahaRate: parseFloat(document.getElementById('murabaha_rate').value) || 4.5,
                    insuranceRate: parseFloat(document.getElementById('insurance_rate').value) || 3.5,
                    lastPaymentRate: parseFloat(document.getElementById('last_payment_rate').value) || 5,
                    durationYears: parseInt(durationHidden.value) || 3
                };
                
                // تحديث شريط التقدم
                function updateProgressSteps() {
                    const sections = document.querySelectorAll('.form-section');
                    const steps = document.querySelectorAll('.step');
                    
                    sections.forEach((section, index) => {
                        const inputs = section.querySelectorAll('input, select');
                        const allFilled = Array.from(inputs).every(input => {
                            if (input.type === 'hidden') return true;
                            return input.value.trim() !== '';
                        });
                        
                        if (allFilled && index < steps.length - 1) {
                            steps[index + 1].classList.add('active');
                        }
                    });
                }
                
                // تزامن السلايدر مع حقل السعر
                carPriceInput.addEventListener('input', function() {
                    const value = parseFloat(this.value) || 0;
                    carPriceSlider.value = Math.min(value, 1000000);
                    currentValues.carPrice = value;
                    updateCalculations();
                    updateProgressSteps();
                });
                
                carPriceSlider.addEventListener('input', function() {
                    carPriceInput.value = this.value;
                    currentValues.carPrice = parseFloat(this.value);
                    updateCalculations();
                });
                
                // تزامن سلايدر الدفعة الأولى
                downPaymentSlider.addEventListener('input', function() {
                    const percentage = parseInt(this.value);
                    downPaymentDisplay.textContent = `${percentage}%`;
                    downPaymentHidden.value = percentage;
                    currentValues.downPaymentPercentage = percentage;
                    updateCalculations();
                });
                
                // خيارات المدة
                durationOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        // إزالة النشاط من جميع الخيارات
                        durationOptions.forEach(opt => opt.classList.remove('active'));
                        
                        // إضافة النشاط للخيار المحدد
                        this.classList.add('active');
                        
                        // تحديث القيم
                        const years = parseInt(this.dataset.years);
                        durationHidden.value = years;
                        durationDisplay.textContent = `${years} سنوات`;
                        currentValues.durationYears = years;
                        
                        updateCalculations();
                    });
                });
                
                // تحديث النسب عند اختيار البنك
                bankSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    
                    if (selectedOption.dataset.murabaha) {
                        document.getElementById('murabaha_rate').value = selectedOption.dataset.murabaha;
                        currentValues.murabahaRate = parseFloat(selectedOption.dataset.murabaha);
                    }
                    
                    if (selectedOption.dataset.insurance) {
                        document.getElementById('insurance_rate').value = selectedOption.dataset.insurance;
                        currentValues.insuranceRate = parseFloat(selectedOption.dataset.insurance);
                    }
                    
                    if (selectedOption.dataset.lastPayment) {
                        document.getElementById('last_payment_rate').value = selectedOption.dataset.lastPayment;
                        currentValues.lastPaymentRate = parseFloat(selectedOption.dataset.lastPayment);
                    }
                    
                    updateCalculations();
                    updateProgressSteps();
                });
                
                // تحديث الحسابات عند تغيير النسب
                document.querySelectorAll('.rate-input').forEach(input => {
                    input.addEventListener('input', function() {
                        const id = this.id;
                        const value = parseFloat(this.value) || 0;
                        
                        switch(id) {
                            case 'murabaha_rate':
                                currentValues.murabahaRate = value;
                                break;
                            case 'insurance_rate':
                                currentValues.insuranceRate = value;
                                break;
                            case 'last_payment_rate':
                                currentValues.lastPaymentRate = value;
                                break;
                        }
                        
                        updateCalculations();
                    });
                });
                
                // تحديث الحسابات
                function updateCalculations() {
                    const {
                        carPrice,
                        downPaymentPercentage,
                        murabahaRate,
                        insuranceRate,
                        lastPaymentRate,
                        durationYears
                    } = currentValues;
                    
                    // حساب الدفعة الأولى
                    const downPayment = carPrice * (downPaymentPercentage / 100);
                    resultElements.downPayment.textContent = formatCurrency(downPayment) + ' ر.س';
                    
                    // حساب مبلغ التمويل
                    const financingAmount = carPrice - downPayment;
                    resultElements.financingAmount.textContent = formatCurrency(financingAmount) + ' ر.س';
                    
                    // حساب الدفعة الأخيرة
                    const lastPayment = carPrice * (lastPaymentRate / 100);
                    resultElements.lastPayment.textContent = formatCurrency(lastPayment) + ' ر.س';
                    
                    // الحسابات التفصيلية
                    if (carPrice > 0) {
                        const insuranceTotal = (insuranceRate / 100) * carPrice * durationYears;
                        const murabahaTotal = (murabahaRate / 100) * financingAmount * durationYears;
                        const totalWithoutLast = financingAmount + murabahaTotal + insuranceTotal - lastPayment;
                        const monthlyInstallment = totalWithoutLast / (durationYears * 12);
                        const totalAmount = (monthlyInstallment * durationYears * 12) + downPayment + lastPayment;
                        const totalInterest = murabahaTotal + insuranceTotal;
                        const increasePercentage = ((totalAmount / carPrice) * 100) - 100;
                        
                        // تحديث النتائج
                        resultElements.monthlyInstallment.textContent = formatCurrency(monthlyInstallment) + ' ر.س';
                        resultElements.estimatedMonthly.textContent = formatCurrency(monthlyInstallment) + ' ر.س';
                        resultElements.estimatedTotal.textContent = formatCurrency(totalAmount) + ' ر.س';
                        resultElements.estimatedInterest.textContent = formatCurrency(totalInterest) + ' ر.س';
                        resultElements.estimatedIncrease.textContent = increasePercentage.toFixed(1) + '%';
                    }
                }
                
                // تنسيق العملة
                function formatCurrency(amount) {
                    return amount.toLocaleString('ar-SA', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
                
                // إعادة تعيين النموذج
                resetBtn.addEventListener('click', function() {
                    if (confirm('هل تريد إعادة تعيين جميع الحقول؟')) {
                        form.reset();
                        currentValues = {
                            carPrice: 116000,
                            downPaymentPercentage: 20,
                            murabahaRate: 4.5,
                            insuranceRate: 3.5,
                            lastPaymentRate: 5,
                            durationYears: 3
                        };
                        
                        // إعادة تعيين السلايدرات
                        carPriceSlider.value = 116000;
                        downPaymentSlider.value = 20;
                        downPaymentDisplay.textContent = '20%';
                        
                        // إعادة تعيين خيارات المدة
                        durationOptions.forEach(opt => {
                            opt.classList.remove('active');
                            if (opt.dataset.years === '3') {
                                opt.classList.add('active');
                            }
                        });
                        
                        // إعادة تعيين شريط التقدم
                        document.querySelectorAll('.step').forEach((step, index) => {
                            if (index > 0) step.classList.remove('active');
                        });
                        
                        updateCalculations();
                    }
                });
                
                // عرض/إخفاء الشريط الجانبي
                toggleSidebarBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.className = 'fas fa-chevron-right';
                    } else {
                        icon.className = 'fas fa-chevron-left';
                    }
                });
                
                // تحقق من صحة النموذج
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // إضافة تأثير اهتزاز للحقول غير الصحيحة
                        const invalidFields = form.querySelectorAll(':invalid');
                        invalidFields.forEach(field => {
                            field.classList.add('is-invalid');
                            
                            // إزالة التأثير بعد ثانيتين
                            setTimeout(() => {
                                field.classList.remove('is-invalid');
                            }, 2000);
                        });
                        
                        // التمرير للحقل الأول غير صحيح
                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    } else {
                        // عرض المؤشر أثناء التحميل
                        calculateBtn.disabled = true;
                        calculateBtn.querySelector('span').classList.add('d-none');
                        calculateBtn.querySelector('.spinner-border').classList.remove('d-none');
                    }
                    
                    form.classList.add('was-validated');
                });
                
                // تحديث تلقائي عند التحميل
                updateCalculations();
                updateProgressSteps();
                
                // إضافة تأثيرات للعناصر
                document.querySelectorAll('.form-control, .form-select').forEach(element => {
                    element.addEventListener('focus', function() {
                        this.parentElement.classList.add('focused');
                    });
                    
                    element.addEventListener('blur', function() {
                        this.parentElement.classList.remove('focused');
                        updateProgressSteps();
                    });
                });
                
                // تحديث شريط التقدم عند التمرير
                let lastScrollTop = 0;
                window.addEventListener('scroll', function() {
                    const st = window.pageYOffset || document.documentElement.scrollTop;
                    if (st > lastScrollTop) {
                        // التمرير لأسفل
                        document.querySelector('.form-actions').classList.add('scrolled');
                    } else {
                        // التمرير لأعلى
                        document.querySelector('.form-actions').classList.remove('scrolled');
                    }
                    lastScrollTop = st <= 0 ? 0 : st;
                });
            });
        </script>
    @endpush

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 8px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }
        
        .calculator-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }
        
        @media (max-width: 1200px) {
            .calculator-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        .calculator-header {
            grid-column: 1 / -1;
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--secondary-color);
        }
        
        .header-content h1 {
            font-weight: 800;
            color: var(--primary-color);
        }
        
        .header-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 20px;
            background: var(--light-bg);
            border-radius: 10px;
            min-width: 120px;
        }
        
        .stat-item i {
            font-size: 24px;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }
        
        .stat-item span {
            font-size: 12px;
            color: #666;
        }
        
        .stat-item strong {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            position: relative;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e0e0e0;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #999;
            margin-bottom: 8px;
            transition: var(--transition);
        }
        
        .step.active .step-number {
            background: var(--secondary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }
        
        .step-label {
            font-size: 14px;
            color: #999;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .calculator-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--card-shadow);
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .section-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 20px;
            color: white;
            font-size: 24px;
        }
        
        .section-title h4 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .section-body {
            padding-right: 80px;
        }
        
        .form-floating {
            position: relative;
        }
        
        .form-floating .form-control,
        .form-floating .form-select {
            height: 60px;
            padding-top: 25px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: var(--transition);
        }
        
        .form-floating .form-control:focus,
        .form-floating .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .form-floating.focused .form-control,
        .form-floating.focused .form-select {
            border-color: var(--secondary-color);
        }
        
        .form-floating label {
            padding-right: 15px;
            color: #666;
            font-weight: 500;
        }
        
        .form-text {
            margin-top: 8px;
            font-size: 13px;
            color: #888;
        }
        
        .rates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .rate-card {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 20px;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        
        .rate-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .rate-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .rate-icon {
            font-size: 20px;
            color: var(--secondary-color);
            margin-left: 10px;
        }
        
        .rate-title {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .rate-body .input-group {
            margin-bottom: 8px;
        }
        
        .rate-input {
            text-align: center;
            font-weight: 600;
            font-size: 18px;
            border: 2px solid #ddd;
            border-left: none;
        }
        
        .rate-input:focus {
            border-color: var(--secondary-color);
            box-shadow: none;
        }
        
        .input-group-text {
            background: white;
            border: 2px solid #ddd;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .rate-info {
            font-size: 12px;
            color: #888;
        }
        
        .car-selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .selection-card {
            background: var(--light-bg);
            border-radius: 10px;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .selection-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .selection-header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        
        .selection-header i {
            margin-left: 10px;
            font-size: 18px;
        }
        
        .selection-header span {
            font-weight: 500;
        }
        
        .selection-body {
            padding: 20px;
        }
        
        .selection-body .form-select {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
        }
        
        .price-input-wrapper {
            position: relative;
        }
        
        .price-input {
            padding-left: 60px;
            font-size: 18px;
            font-weight: 600;
            text-align: right;
            border: 2px solid #ddd;
            border-radius: 8px;
            height: 50px;
        }
        
        .currency {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .price-slider {
            margin: 15px 0 10px;
        }
        
        .form-range::-webkit-slider-thumb {
            background: var(--secondary-color);
            width: 20px;
            height: 20px;
        }
        
        .form-range::-moz-range-thumb {
            background: var(--secondary-color);
            width: 20px;
            height: 20px;
        }
        
        .price-range {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #888;
        }
        
        .finance-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border: 2px solid #eee;
            transition: var(--transition);
        }
        
        .finance-card:hover {
            border-color: var(--secondary-color);
        }
        
        .finance-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .finance-header i {
            font-size: 24px;
            color: var(--secondary-color);
            margin-left: 15px;
        }
        
        .finance-header h6 {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 3px;
        }
        
        .percentage-display,
        .duration-display {
            margin-right: auto;
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .percentage-slider {
            position: relative;
        }
        
        .percentage-markers {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 12px;
            color: #888;
        }
        
        .duration-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .duration-option {
            flex: 1;
            min-width: 80px;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: white;
            text-align: center;
            font-weight: 500;
            color: #666;
            transition: var(--transition);
            cursor: pointer;
        }
        
        .duration-option:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }
        
        .duration-option.active {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        .instant-results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        
        .result-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: var(--transition);
        }
        
        .result-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .result-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .result-header i {
            color: var(--secondary-color);
            margin-left: 8px;
            font-size: 18px;
        }
        
        .result-header span {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .result-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-color);
        }
        
        .result-value.highlight {
            color: var(--success-color);
            font-size: 28px;
        }
        
        .form-actions {
            background: white;
            padding: 20px 30px;
            border-radius: var(--border-radius);
            margin-top: 30px;
            box-shadow: var(--card-shadow);
            position: sticky;
            bottom: 20px;
            z-index: 100;
            transition: var(--transition);
        }
        
        .form-actions.scrolled {
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        }
        
        #calculateBtn {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border: none;
            border-radius: 10px;
            transition: var(--transition);
        }
        
        #calculateBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        }
        
        .results-sidebar {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            height: fit-content;
            position: sticky;
            top: 20px;
            transition: var(--transition);
        }
        
        .results-sidebar.collapsed {
            transform: translateX(calc(100% - 50px));
        }
        
        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        #toggleSidebar {
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-body {
            padding: 20px;
        }
        
        .estimation-card,
        .tip-card,
        .history-preview {
            background: var(--light-bg);
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .estimation-header,
        .tip-header,
        .history-header {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        
        .estimation-header i,
        .tip-header i,
        .history-header i {
            margin-left: 10px;
            font-size: 18px;
        }
        
        .estimation-body,
        .tip-body,
        .history-body {
            padding: 20px;
        }
        
        .estimation-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .estimation-item:last-child {
            border-bottom: none;
        }
        
        .estimation-item span {
            font-weight: 500;
            color: #666;
        }
        
        .estimation-value {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .tip-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        
        .tip-item i {
            margin-left: 10px;
            font-size: 14px;
        }
        
        .tip-item span {
            font-size: 14px;
            color: #555;
        }
        
        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
        
        .history-info {
            display: flex;
            flex-direction: column;
        }
        
        .history-info strong {
            font-size: 14px;
            color: var(--primary-color);
        }
        
        .history-info small {
            font-size: 12px;
            color: #888;
        }
        
        .history-amount {
            font-weight: 700;
            color: var(--success-color);
        }
        
        /* تحسينات عامة */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: var(--card-shadow);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ffeaea, #ffcccc);
            border-right: 4px solid var(--danger-color);
        }
        
        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .calculator-wrapper {
                padding: 10px;
            }
            
            .calculator-header {
                padding: 20px;
            }
            
            .header-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .section-body {
                padding-right: 0;
            }
            
            .rates-grid {
                grid-template-columns: 1fr;
            }
            
            .instant-results {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                border-radius: 0;
                margin-top: 0;
                z-index: 1000;
            }
            
            .results-sidebar {
                position: relative;
                top: 0;
            }
        }
        
        /* تحسينات للشاشات المتوسطة */
        @media (max-width: 992px) {
            .car-selection-grid {
                grid-template-columns: 1fr;
            }
            
            .duration-options {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .duration-option {
                flex: 0 0 auto;
            }
        }
        
        /* تأثيرات الحركة */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-section {
            animation: slideIn 0.5s ease-out;
        }
        
        .form-section:nth-child(2) { animation-delay: 0.1s; }
        .form-section:nth-child(3) { animation-delay: 0.2s; }
        .form-section:nth-child(4) { animation-delay: 0.3s; }
        
        /* تحسينات الخطوط العربية */
        body {
            font-family: 'Tajawal', 'Segoe UI', sans-serif;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
        }
        
        /* تحسينات إضافية */
        .invalid-feedback {
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .invalid-feedback i {
            margin-left: 5px;
        }
        
        .is-invalid {
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
@endsection