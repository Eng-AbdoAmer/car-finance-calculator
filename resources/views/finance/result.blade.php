<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج حساب التمويل</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
<link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
            padding-top: 20px;
            padding-bottom: 50px;
        }

        .result-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            font-weight: 700;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .summary-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 1rem;
            opacity: 0.9;
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
            font-size: 14px;
        }

        .table-custom tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            font-size: 13px;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .result-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
     <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
      <script src="{{ asset('js/navbar.js') }}"></script>
</head>

<body>
     <x-navbar /> 
    <div class="container">
        <div class="result-container">
            <!-- رأس الصفحة -->
            <div class="header">
                <h1><i class="fas fa-file-invoice-dollar"></i> نتائج حساب التمويل</h1>
                <p class="text-muted">تاريخ الحساب: {{ date('Y-m-d H:i') }}</p>
                <div class="no-print">
                    <a href="{{ route('finance.calculator') }}" class="btn btn-primary me-2">
                        <i class="fas fa-arrow-right"></i> رجوع للحاسبة
                    </a>
                    <button onclick="window.print()" class="btn btn-success">
                        <i class="fas fa-print"></i> طباعة النتائج
                    </button>
                    <a href="{{ route('finance.send.whatsapp', ['id' => $financeCalculation->id]) }}"
                        class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp"></i> إرسال عبر واتساب
                    </a>
                </div>
            </div>

            <!-- بطاقة الملخص الرئيسية -->
            <div class="summary-card">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="summary-value">
                            {{ number_format($allCalculations['monthly_installment_with_insurance'], 2) }} ر.س</div>
                        <div class="summary-label">القسط الشهري شامل التأمين</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="summary-value">{{ number_format($allCalculations['down_payment_amount'], 2) }} ر.س
                        </div>
                        <div class="summary-label">الدفعة الأولى</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="summary-value">{{ number_format($allCalculations['final_payment_amount'], 2) }} ر.س
                        </div>
                        <div class="summary-label">الدفعة الأخيرة</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="summary-value">{{ number_format($allCalculations['grand_total'], 2) }} ر.س</div>
                        <div class="summary-label">الإجمالي الكلي</div>
                    </div>
                </div>
            </div>

            <!-- المدخلات الأساسية -->
            <h3 class="section-title"><i class="fas fa-info-circle"></i> المدخلات الأساسية</h3>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 60%">سعر السيارة</th>
                            <td class="text-success fw-bold">{{ number_format($allCalculations['car_price'], 2) }} ر.س
                            </td>
                        </tr>
                        <tr>
                            <th>الدفعة الأولى</th>
                            <td>{{ $allCalculations['down_payment_percentage'] }}%
                                ({{ number_format($allCalculations['down_payment_amount'], 2) }} ر.س)</td>
                        </tr>
                        <tr>
                            <th>مبلغ التمويل</th>
                            <td class="text-primary fw-bold">
                                {{ number_format($allCalculations['financed_amount'], 2) }} ر.س</td>
                        </tr>
                        <tr>
                            <th>المدة بالأشهر</th>
                            <td>{{ $allCalculations['loan_term_months'] }} شهر</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 60%">نسبة هامش الربح</th>
                            <td class="text-info fw-bold">{{ $allCalculations['profit_margin_percentage'] }}%</td>
                        </tr>
                        <tr>
                            <th>الرسوم الإدارية</th>
                            <td>{{ $allCalculations['administrative_fees_percentage'] }}%
                                ({{ number_format($allCalculations['administrative_fees_amount'], 2) }} ر.س)</td>
                        </tr>
                        <tr>
                            <th>نسبة التأمين</th>
                            <td class="text-warning fw-bold">{{ $allCalculations['insurance_rate_percentage'] }}%</td>
                        </tr>
                        <tr>
                            <th>الدفعة الأخيرة</th>
                            <td>{{ $allCalculations['final_payment_percentage'] }}%
                                ({{ number_format($allCalculations['final_payment_amount'], 2) }} ر.س)</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- النتائج الأساسية -->
            <h3 class="section-title"><i class="fas fa-chart-line"></i> النتائج الأساسية</h3>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">القسط الشهري بدون تأمين</h5>
                            <h3 class="card-text">
                                {{ number_format($allCalculations['monthly_installment_without_insurance'], 2) }} ر.س
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">إجمالي الأرباح</h5>
                            <h3 class="card-text">{{ number_format($allCalculations['total_profit'], 2) }} ر.س</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <h5 class="card-title">إجمالي التأمين</h5>
                            <h3 class="card-text">{{ number_format($allCalculations['total_insurance'], 2) }} ر.س</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المؤشرات المالية -->
            <h3 class="section-title"><i class="fas fa-chart-bar"></i> المؤشرات المالية</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> تكاليف التشغيل</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>تكلفة الأموال على البنك (FTP)</td>
                                    <td class="fw-bold">{{ number_format($financialIndicators['ftp_percentage'], 2) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>خسائر متوقعة (CoR)</td>
                                    <td class="fw-bold">{{ number_format($financialIndicators['cor_percentage'], 2) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>تكاليف التشغيل (OPEX)</td>
                                    <td class="fw-bold">
                                        {{ number_format($financialIndicators['opex_percentage'], 2) }}%</td>
                                </tr>
                                <tr>
                                    <td>إجمالي التكلفة بدون ربح (Breakeven)</td>
                                    <td class="fw-bold text-danger">
                                        {{ number_format($financialIndicators['breakeven_percentage'], 2) }}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> المؤشرات</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>هامش الربح المستهدف</td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($financialIndicators['margin_percentage'], 2) }}%</td>
                                </tr>
                                <tr>
                                    <td>صافي الربح النهائي</td>
                                    <td class="fw-bold">{{ number_format($financialIndicators['net_profit'], 2) }} ر.س
                                    </td>
                                </tr>
                                <tr>
                                    <td>العائد الداخلي (IRR)</td>
                                    <td class="fw-bold">{{ number_format($financialIndicators['irr_percentage'], 4) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>النسبة السنوية الحقيقية (APR)</td>
                                    <td class="fw-bold text-primary">
                                        {{ number_format($financialIndicators['apr_percentage'], 5) }}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول التقسيط -->
            <h3 class="section-title"><i class="fas fa-calendar-alt"></i> جدول التقسيط الشهري</h3>
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>السنة</th>
                            <th>Outstanding + Insurance</th>
                            <th>Outstanding</th>
                            <th>Profit</th>
                            <th>Principal + Insurance</th>
                            <th>Principal</th>
                            <th>Insurance</th>
                            <th>Cash Flows</th>
                            <th>CF %</th>
                            <th>FTP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($installments as $installment)
                            <tr>
                                <td class="fw-bold">{{ $installment['installment_number'] }}</td>
                                <td>{{ $installment['year'] }}</td>
                                <td>{{ number_format($installment['outstanding_plus_insurance'], 2) }}</td>
                                <td>{{ number_format($installment['outstanding_balance'], 2) }}</td>
                                <td class="text-success">{{ number_format($installment['profit_amount'], 2) }}</td>
                                <td>{{ number_format($installment['principal_plus_insurance'], 2) }}</td>
                                <td class="text-primary">{{ number_format($installment['principal_amount'], 2) }}</td>
                                <td class="text-warning">{{ number_format($installment['insurance_amount'], 2) }}</td>
                                <td class="fw-bold text-danger">{{ number_format($installment['cash_flow'], 2) }}</td>
                                <td>{{ number_format($installment['cf_percentage'], 2) }}%</td>
                                <td>{{ number_format($installment['ftp_monthly'], 5) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="2" class="text-end fw-bold">المجاميع:</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="fw-bold">{{ number_format($allCalculations['total_profit'], 2) }}</td>
                            <td>-</td>
                            <td class="fw-bold">{{ number_format($allCalculations['remaining_car_value'], 2) }}</td>
                            <td class="fw-bold">{{ number_format($allCalculations['total_insurance'], 2) }}</td>
                            <td class="fw-bold">{{ number_format($T19, 2) }}</td>
                            <td>100%</td>
                            <td class="fw-bold">{{ number_format($V19, 5) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- أزرار التنقل -->
            <div class="row mt-4 no-print">
                <div class="col-md-12 text-center">
                    <a href="{{ route('finance.calculator') }}" class="btn btn-lg btn-primary me-3">
                        <i class="fas fa-calculator"></i> حساب جديد
                    </a>
                    @if (auth()->check())
                        <a href="{{ route('finance.history') }}" class="btn btn-lg btn-info">
                            <i class="fas fa-history"></i> سجل الحسابات
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // زر الطباعة
        document.addEventListener('DOMContentLoaded', function() {
            // إضافة تأثيرات للجدول
            const tableRows = document.querySelectorAll('.table-custom tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
</body>

</html>
