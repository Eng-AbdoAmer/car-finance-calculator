<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل حساب التمويل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }

        .detail-container {
            max-width: 1200px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            font-weight: 700;
        }

        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-right: 4px solid #3498db;
        }

        .info-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .info-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .btn-action {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn-action:hover {
            background: linear-gradient(135deg, #2980b9, #1c5a7a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-print {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366, #128C7E);
        }

        .btn-history {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table-custom thead th {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .summary-box {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .detail-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <script src="{{ asset('js/navbar.js') }}"></script>
</head>

<body>
    <x-navbar />
    <div class="container mt-4">
        @if (isset($calculation))
            <div class="detail-container">
                <!-- رأس الصفحة -->
                <div class="header">
                    <h1><i class="fas fa-file-invoice"></i> تفاصيل حساب التمويل</h1>
                    <p class="text-muted">تاريخ الحساب: {{ $calculation->created_at->format('Y-m-d H:i') }}</p>
                    <div class="no-print mt-3">
                        <button onclick="window.print()" class="btn btn-action btn-print">
                            <i class="fas fa-print"></i> طباعة النتائج
                        </button>
                        <a href="{{ route('finance.history') }}" class="btn btn-action btn-history">
                            <i class="fas fa-history"></i> رجوع للسجل
                        </a>
                    </div>
                </div>

                <!-- ملخص سريع -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="summary-box text-center">
                            <div class="summary-label">سعر السيارة</div>
                            <div class="summary-value">{{ $calculation->car_price }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-box text-center">
                            <div class="summary-label">القسط الشهري</div>
                            <div class="summary-value">{{ $calculation->monthly_installment_with_insurance }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-box text-center">
                            <div class="summary-label">مدة التمويل</div>
                            <div class="summary-value">{{ $calculation->loan_term_months ?? 0 }} شهر</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-box text-center">
                            <div class="summary-label">الإجمالي الكلي</div>
                            <div class="summary-value">{{ $calculation->grand_total }}</div>
                        </div>
                    </div>
                </div>

                <!-- معلومات السيارة -->
                <div class="form-section">
                    <h4 class="section-title"><i class="fas fa-car"></i> معلومات السيارة</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">العلامة التجارية</div>
                                <div class="info-value">{{ $calculation->car_brand ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">موديل السيارة</div>
                                <div class="info-value">
                                    @if ($calculation->carModel)
                                        {{ $calculation->carModel->model_year }}
                                    @else
                                        غير محدد
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">فئة السيارة</div>
                                <div class="info-value">{{ $calculation->car_segment ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات التمويل -->
                <div class="form-section">
                    <h4 class="section-title"><i class="fas fa-money-bill-wave"></i> معلومات التمويل</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">مبلغ التمويل</div>
                                <div class="info-value">{{ $calculation->financed_amount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">الدفعة الأولى</div>
                                <div class="info-value">{{ $calculation->down_payment_percentage }}%</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">الدفعة الأخيرة</div>
                                <div class="info-value">{{ $calculation->final_payment_percentage }}%</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">نسبة هامش الربح</div>
                                <div class="info-value">{{ $calculation->profit_margin_percentage }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">مبلغ الدفعة الأولى</div>
                                <div class="info-value">{{ $calculation->down_payment_amount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">مبلغ الدفعة الأخيرة</div>
                                <div class="info-value">{{ $calculation->final_payment_amount }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">إجمالي الربح</div>
                                <div class="info-value">{{ $calculation->total_profit }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">إجمالي التأمين</div>
                                <div class="info-value">{{ $calculation->total_insurance }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات العميل -->
                <div class="form-section">
                    <h4 class="section-title"><i class="fas fa-user"></i> معلومات العميل</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">الجنس</div>
                                <div class="info-value">{{ $calculation->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">الفئة العمرية</div>
                                <div class="info-value">{{ $calculation->age_bracket ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">رقم الهاتف</div>
                                <div class="info-value">{{ $calculation->phone ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="info-label">الحالة</div>
                                <div class="info-value">
                                    @if ($calculation->status == 'pending')
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    @elseif($calculation->status == 'sold')
                                        <span class="badge bg-success">تم البيع</span>
                                    @elseif($calculation->status == 'follow_up')
                                        <span class="badge bg-primary">متابعة</span>
                                    @elseif($calculation->status == 'not_sold')
                                        <span class="badge bg-danger">لم يتم البيع</span>
                                    @elseif($calculation->status == 'cancelled')
                                        <span class="badge bg-secondary">ملغي</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول التقسيط -->
                <div class="form-section">
                    <h4 class="section-title"><i class="fas fa-calendar-alt"></i> جدول التقسيط</h4>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>السنة</th>
                                    <th>الرصيد المتبقي</th>
                                    <th>الربح</th>
                                    <th>أصل القرض</th>
                                    <th>التأمين</th>
                                    <th>القسط الشهري</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($calculation->installments && count($calculation->installments) > 0)
                                    @foreach ($calculation->installments as $installment)
                                        <tr>
                                            <td class="fw-bold">{{ $installment->installment_number ?? '' }}</td>
                                            <td>{{ $installment->year ?? '' }}</td>
                                            <td>{{ $installment->outstanding_balance }}</td>
                                            <td class="text-success">{{ $installment->profit_amount }}</td>
                                            <td class="text-primary">{{ $installment->principal_amount }}</td>
                                            <td class="text-warning">{{ $installment->insurance_amount }}</td>
                                            <td class="fw-bold text-danger">{{ $installment->total_installment }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">لا توجد بيانات للتقسيط</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- أزرار التنقل -->
                <div class="row mt-4 no-print">
                    <div class="col-md-12 text-center">
                        <a href="{{ route('finance.history') }}" class="btn btn-action btn-history me-3">
                            <i class="fas fa-history"></i> رجوع للسجل
                        </a>
                        <a href="{{ route('finance.calculator') }}" class="btn btn-action">
                            <i class="fas fa-calculator"></i> حساب جديد
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="detail-container text-center">
                <div class="alert alert-danger">
                    <h4><i class="fas fa-exclamation-triangle"></i> خطأ</h4>
                    <p>لا توجد بيانات لعرضها</p>
                    <a href="{{ route('finance.history') }}" class="btn btn-action mt-3">
                        <i class="fas fa-arrow-right"></i> الرجوع للسجل
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تنسيق الأرقام بشكل آمن
        document.addEventListener('DOMContentLoaded', function() {
            // هذه الدالة تساعد في عرض الأرقام حتى لو كانت تحتوي على مشاكل
            function safeNumberFormat(value) {
                if (!value && value !== 0) return '0.00';

                // تحويل القيمة إلى رقم
                const num = parseFloat(value);

                // التحقق مما إذا كان الرقم صالحاً
                if (isNaN(num)) return '0.00';

                // تنسيق الرقم
                return new Intl.NumberFormat('ar-SA').format(num.toFixed(2));
            }

            // تحديث جميع الأرقام في الصفحة
            const numberElements = document.querySelectorAll('[data-format-number]');
            numberElements.forEach(element => {
                const value = element.getAttribute('data-value') || element.textContent;
                element.textContent = safeNumberFormat(value);
            });
        });
    </script>
</body>

</html>
