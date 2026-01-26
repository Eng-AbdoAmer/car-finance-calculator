@extends('layouts.app')

@section('title', 'نتائج حساب التمويل')

@section('content')
    <x-navbar />
    
    <div class="result-wrapper">
        <!-- رأس الصفحة -->
        <div class="result-header">
            <div class="header-content">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h1 class="display-5 fw-bold text-primary">
                            <i class="fas fa-file-invoice-dollar me-2"></i>نتائج حساب التمويل
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $financingRequest->created_at->translatedFormat('Y-m-d H:i') }}
                        </p>
                    </div>
                    <div class="header-badges">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>تم الحفظ
                        </span>
                    </div>
                </div>
                
                <div class="header-actions">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('financing.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right me-1"></i>رجوع للحاسبة
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-success print-btn">
                            <i class="fas fa-print me-1"></i>طباعة النتائج
                        </button>
                        <a href="{{ route('financing.send.whatsapp', $financingRequest->id) }}" 
                           class="btn btn-success whatsapp-btn"
                           target="_blank"
                           onclick="return confirmWhatsApp()">
                            <i class="fab fa-whatsapp me-1"></i>إرسال عبر واتساب
                        </a>
                        <a href="{{ route('financing.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-1"></i>سجل الحسابات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة الملخص الرئيسية -->
        <div class="summary-grid">
            <div class="summary-card primary">
                <div class="summary-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">القسط الشهري</div>
                    <div class="summary-value">
                        {{ number_format($financingRequest->monthly_installment, 0) }}
                        <span class="currency">ر.س</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card success">
                <div class="summary-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">الدفعة الأولى</div>
                    <div class="summary-value">
                        {{ number_format($financingRequest->down_payment, 0) }}
                        <span class="currency">ر.س</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card warning">
                <div class="summary-icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">الدفعة الأخيرة</div>
                    <div class="summary-value">
                        {{ number_format($financingRequest->last_payment, 0) }}
                        <span class="currency">ر.س</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card info">
                <div class="summary-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">الإجمالي الكلي</div>
                    <div class="summary-value">
                        {{ number_format($financingRequest->total_amount, 0) }}
                        <span class="currency">ر.س</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات التمويل -->
        <div class="row g-4">
            <!-- معلومات السيارة والبنك -->
            <div class="col-lg-6">
                <div class="card info-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-car me-2"></i>معلومات السيارة والتمويل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">سعر السيارة</span>
                                <span class="info-value highlight">
                                    {{ number_format($financingRequest->car_price, 0) }} ر.س
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">مبلغ التمويل</span>
                                <span class="info-value text-primary">
                                    {{ number_format($financingRequest->financing_amount, 0) }} ر.س
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">المدة</span>
                                <span class="info-value">
                                    {{ $financingRequest->duration_months }} شهر
                                    <small class="text-muted">({{ number_format($financingRequest->duration_months / 12, 1) }} سنة)</small>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">العلامة التجارية</span>
                                <span class="info-value">{{ $financingRequest->brand->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">الموديل</span>
                                <span class="info-value">{{ $financingRequest->model->model_year ?? 'غير محدد' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">البنك</span>
                                <span class="info-value">{{ $financingRequest->bank->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- النسب والتكاليف -->
            <div class="col-lg-6">
                <div class="card info-card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-percentage me-2"></i>النسب والتكاليف
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">نسبة المرابحة</span>
                                <span class="info-value">
                                    <span class="badge bg-info">{{ $financingRequest->murabaha_rate }}%</span>
                                    <small class="text-muted ms-2">{{ number_format($financingRequest->murabaha_total, 0) }} ر.س</small>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">نسبة التأمين</span>
                                <span class="info-value">
                                    <span class="badge bg-success">{{ $financingRequest->insurance_rate }}%</span>
                                    <small class="text-muted ms-2">{{ number_format($financingRequest->insurance_total, 0) }} ر.س</small>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">نسبة الدفعة الأولى</span>
                                <span class="info-value">
                                    <span class="badge bg-primary">{{ $financingRequest->down_payment_percentage }}%</span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">نسبة الدفعة الأخيرة</span>
                                <span class="info-value">
                                    <span class="badge bg-warning">{{ $financingRequest->last_payment_rate }}%</span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">رقم الهاتف</span>
                                <span class="info-value">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    {{ $financingRequest->phone }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">التكلفة الإضافية</span>
                                <span class="info-value text-warning">
                                    {{ number_format($financingRequest->interest_with_insurance, 0) }} ر.س
                                    <small class="text-muted">({{ number_format($financingRequest->interest_with_insurance / $financingRequest->car_price * 100, 1) }}%)</small>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول الأقساط الشهرية -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>جدول الأقساط الشهرية
                </h5>
                <div class="table-controls">
                    <button class="btn btn-sm btn-outline-light" onclick="toggleInstallmentView()">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="view-label">عرض مبسط</span>
                    </button>
                    <input type="text" id="searchInstallments" class="form-control form-control-sm ms-2" 
                           placeholder="بحث في الجدول..." style="width: 200px;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="installmentsTable">
                    <table class="table table-hover mb-0 installment-table">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>الشهر</th>
                                <th class="text-center">القيمة الشهرية</th>
                                <th class="text-center">المبلغ المتبقي</th>
                                <th class="text-center">الحالة</th>
                                <th class="text-center">نسبة السداد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $remaining = $financingRequest->financing_amount + 
                                             $financingRequest->murabaha_total + 
                                             $financingRequest->insurance_total - 
                                             $financingRequest->last_payment;
                                $totalWithoutLast = $remaining;
                                $monthly = $financingRequest->monthly_installment;
                                $currentDate = now();
                            @endphp

                            @for ($i = 1; $i <= $financingRequest->duration_months; $i++)
                                @php
                                    $remaining -= $monthly;
                                    if ($remaining < 0) $remaining = 0;
                                    
                                    $paymentDate = $currentDate->copy()->addMonths($i);
                                    $completion = (($totalWithoutLast - $remaining) / $totalWithoutLast) * 100;
                                    
                                    if ($i <= 12) {
                                        $status = 'قريب';
                                        $badge = 'primary';
                                        $progress = 'success';
                                    } elseif ($i <= 36) {
                                        $status = 'متوسط';
                                        $badge = 'warning';
                                        $progress = 'warning';
                                    } else {
                                        $status = 'بعيد';
                                        $badge = 'secondary';
                                        $progress = 'secondary';
                                    }
                                @endphp
                                <tr class="installment-row" data-month="{{ $i }}">
                                    <td class="text-center fw-bold">{{ $i }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <div>
                                                <div>{{ $paymentDate->translatedFormat('F Y') }}</div>
                                                <small class="text-muted">{{ $paymentDate->format('Y-m') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        {{ number_format($monthly, 0) }} ر.س
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($remaining, 0) }} ر.س
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $badge }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $progress }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min($completion, 100) }}%"
                                                 aria-valuenow="{{ $completion }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($completion, 1) }}%</small>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr class="table-warning">
                                <td colspan="3" class="text-end fw-bold">الدفعة الأخيرة (نهاية المدة):</td>
                                <td colspan="3" class="text-center fw-bold">
                                    {{ number_format($financingRequest->last_payment, 0) }} ر.س
                                </td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="3" class="text-end fw-bold">الإجمالي الكلي المدفوع:</td>
                                <td colspan="3" class="text-center fw-bold">
                                    {{ number_format($financingRequest->total_amount, 0) }} ر.س
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ملخص التكلفة -->
        <div class="row mt-4 g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>تحليل التكلفة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="cost-analysis">
                            <div class="cost-item">
                                <div class="cost-label">سعر السيارة الأساسي</div>
                                <div class="cost-value">{{ number_format($financingRequest->car_price, 0) }} ر.س</div>
                                <div class="cost-percentage">100%</div>
                            </div>
                            
                            <div class="cost-item">
                                <div class="cost-label">تكاليف المرابحة</div>
                                <div class="cost-value">{{ number_format($financingRequest->murabaha_total, 0) }} ر.س</div>
                                <div class="cost-percentage">
                                    {{ number_format($financingRequest->murabaha_total / $financingRequest->car_price * 100, 1) }}%
                                </div>
                            </div>
                            
                            <div class="cost-item">
                                <div class="cost-label">تكاليف التأمين</div>
                                <div class="cost-value">{{ number_format($financingRequest->insurance_total, 0) }} ر.س</div>
                                <div class="cost-percentage">
                                    {{ number_format($financingRequest->insurance_total / $financingRequest->car_price * 100, 1) }}%
                                </div>
                            </div>
                            
                            <div class="cost-divider">
                                <div class="line"></div>
                            </div>
                            
                            <div class="cost-item total">
                                <div class="cost-label">التكلفة الإجمالية</div>
                                <div class="cost-value">{{ number_format($financingRequest->total_amount, 0) }} ر.س</div>
                                <div class="cost-percentage">
                                    {{ number_format($financingRequest->total_amount / $financingRequest->car_price * 100, 1) }}%
                                </div>
                            </div>
                            
                            <div class="cost-item extra">
                                <div class="cost-label">الزيادة الإجمالية</div>
                                <div class="cost-value text-warning">
                                    {{ number_format($financingRequest->total_amount - $financingRequest->car_price, 0) }} ر.س
                                </div>
                                <div class="cost-percentage">
                                    {{ number_format(($financingRequest->total_amount / $financingRequest->car_price * 100) - 100, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>نصائح تمويلية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tips-list">
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>حاول زيادة الدفعة الأولى لتقليل الفائدة</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>المدة الأقصر تقلل من إجمالي الفائدة</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>قارن بين عروض البنوك المختلفة</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>تأكد من شروط التأمين المضمنة</span>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>هذه النتائج تقديرية وقد تختلف قليلاً عند التقديم الفعلي للتمويل</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار التنقل السفلية -->
        <div class="action-buttons mt-4">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('financing.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-calculator me-2"></i>حساب جديد
                </a>
                <button onclick="shareResults()" class="btn btn-outline-primary btn-lg px-5">
                    <i class="fas fa-share-alt me-2"></i>مشاركة النتائج
                </button>
                <a href="{{ route('financing.history') }}" class="btn btn-outline-secondary btn-lg px-5">
                    <i class="fas fa-history me-2"></i>سجل الحسابات
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // إضافة تأثيرات للجدول
                const tableRows = document.querySelectorAll('.installment-row');
                tableRows.forEach(row => {
                    row.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f8f9fa';
                        this.style.transform = 'translateY(-2px)';
                        this.style.transition = 'all 0.2s ease';
                    });
                    
                    row.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                        this.style.transform = 'translateY(0)';
                    });
                    
                    // إضافة تأثير النقر
                    row.addEventListener('click', function() {
                        const month = this.dataset.month;
                        const payment = '{{ number_format($financingRequest->monthly_installment, 0) }}';
                        alert(`القسط ${month}: ${payment} ر.س`);
                    });
                });
                
                // بحث في جدول الأقساط
                const searchInput = document.getElementById('searchInstallments');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const rows = document.querySelectorAll('.installment-row');
                        
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        });
                    });
                }
                
                // طباعة عند الضغط على Ctrl+P
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                        e.preventDefault();
                        window.print();
                    }
                });
                
                // تحميل بيانات إضافية عند التمرير
                let isLoading = false;
                window.addEventListener('scroll', function() {
                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !isLoading) {
                        // يمكن إضافة تحميل المزيد من البيانات هنا
                    }
                });
            });
            
            function toggleInstallmentView() {
                const table = document.getElementById('installmentsTable');
                const button = document.querySelector('.view-label');
                
                table.classList.toggle('detailed-view');
                table.classList.toggle('simple-view');
                
                if (table.classList.contains('simple-view')) {
                    button.textContent = 'عرض مفصل';
                } else {
                    button.textContent = 'عرض مبسط';
                }
            }
            
            function confirmWhatsApp() {
                return confirm(`هل تريد إرسال النتائج إلى الرقم {{ $financingRequest->phone }} عبر واتساب؟`);
            }
            
            function shareResults() {
                if (navigator.share) {
                    navigator.share({
                        title: 'نتائج تمويل السيارة',
                        text: `القسط الشهري: {{ number_format($financingRequest->monthly_installment, 0) }} ر.س\nالإجمالي: {{ number_format($financingRequest->total_amount, 0) }} ر.س`,
                        url: window.location.href
                    });
                } else {
                    // نسخ الرابط
                    navigator.clipboard.writeText(window.location.href);
                    alert('تم نسخ رابط النتائج إلى الحافظة');
                }
            }
            
            // تحسين تجربة الطباعة
            function prepareForPrint() {
                // يمكن إضافة تحضيرات إضافية قبل الطباعة
                document.body.classList.add('printing');
                return true;
            }
            
            window.onbeforeprint = prepareForPrint;
            window.onafterprint = function() {
                document.body.classList.remove('printing');
            };
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
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        .result-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: var(--light-bg);
            min-height: 100vh;
        }
        
        .result-header {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            border-left: 5px solid var(--secondary-color);
        }
        
        .header-content h1 {
            font-weight: 800;
            color: var(--primary-color);
        }
        
        .header-badges .badge {
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 20px;
        }
        
        .header-actions .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .header-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .whatsapp-btn {
            background: linear-gradient(135deg, #25D366, #128C7E);
            border: none;
        }
        
        .whatsapp-btn:hover {
            background: linear-gradient(135deg, #128C7E, #075E54);
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .summary-card.primary {
            border-top: 4px solid var(--secondary-color);
        }
        
        .summary-card.success {
            border-top: 4px solid var(--success-color);
        }
        
        .summary-card.warning {
            border-top: 4px solid var(--warning-color);
        }
        
        .summary-card.info {
            border-top: 4px solid #3498db;
        }
        
        .summary-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .summary-card.primary .summary-icon {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
        }
        
        .summary-card.success .summary-icon {
            background: linear-gradient(135deg, var(--success-color), #219653);
        }
        
        .summary-card.warning .summary-icon {
            background: linear-gradient(135deg, var(--warning-color), #e67e22);
        }
        
        .summary-card.info .summary-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
        
        .summary-content {
            flex: 1;
        }
        
        .summary-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .summary-value {
            font-size: 28px;
            font-weight: 800;
            color: #2c3e50;
        }
        
        .currency {
            font-size: 18px;
            font-weight: 500;
            color: #666;
        }
        
        .info-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .info-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        
        .info-card .card-header {
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-weight: 500;
        }
        
        .info-value {
            font-weight: 600;
            color: #333;
        }
        
        .info-value.highlight {
            color: var(--success-color);
            font-size: 18px;
        }
        
        .installment-table {
            margin: 0;
        }
        
        .installment-table thead th {
            background: #f8f9fa;
            color: var(--primary-color);
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #dee2e6;
        }
        
        .installment-table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .installment-row:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .cost-analysis {
            padding: 20px;
        }
        
        .cost-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .cost-item.total {
            border-top: 2px solid var(--primary-color);
            font-weight: 800;
            font-size: 18px;
            color: var(--primary-color);
            margin-top: 15px;
            padding-top: 20px;
        }
        
        .cost-item.extra {
            background: #fff9e6;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #ffd700;
        }
        
        .cost-label {
            flex: 2;
            font-weight: 500;
        }
        
        .cost-value {
            flex: 1;
            text-align: center;
            font-weight: 600;
        }
        
        .cost-percentage {
            flex: 1;
            text-align: right;
            color: #666;
            font-size: 14px;
        }
        
        .cost-divider {
            margin: 15px 0;
            text-align: center;
        }
        
        .cost-divider .line {
            height: 1px;
            background: linear-gradient(90deg, transparent, #ccc, transparent);
        }
        
        .tips-list {
            padding: 10px 0;
        }
        
        .tip-item {
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
            display: flex;
            align-items: center;
        }
        
        .tip-item:last-child {
            border-bottom: none;
        }
        
        .table-controls {
            display: flex;
            align-items: center;
        }
        
        .action-buttons .btn {
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* عرض مبسط للجدول */
        .simple-view .installment-table tbody tr td:nth-child(4),
        .simple-view .installment-table tbody tr td:nth-child(6) {
            display: none;
        }
        
        /* الطباعة */
        @media print {
            .no-print, .header-actions, .action-buttons, .table-controls {
                display: none !important;
            }
            
            .result-wrapper {
                padding: 0;
                background: white;
            }
            
            .summary-card, .info-card, .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .summary-value, .info-value.highlight {
                color: black !important;
            }
        }
        
        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .header-actions .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .table-controls {
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
            
            .table-controls input {
                width: 100% !important;
            }
        }
        
        /* تحسينات للشاشات المتوسطة */
        @media (max-width: 992px) {
            .result-header {
                padding: 20px;
            }
            
            .summary-card {
                padding: 20px;
            }
            
            .summary-value {
                font-size: 24px;
            }
        }
        
        /* تأثيرات إضافية */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* تحسينات النصوص العربية */
        body {
            font-family: 'Tajawal', 'Segoe UI', sans-serif;
        }
        
        .arabic-text {
            line-height: 1.8;
            letter-spacing: 0;
        }
        
        /* تلميحات الأدوات */
        [title] {
            cursor: help;
        }
        
        /* تحسينات إضافية */
        .progress-bar {
            border-radius: 4px;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
@endsection