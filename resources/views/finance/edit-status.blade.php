<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل حالة الحساب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }

        .edit-container {
            max-width: 600px;
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

        .btn-save {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #27ae60, #219653);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn-cancel:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-right: 4px solid #3498db;
        }

        .info-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
        }

        .form-control, .form-select, .form-textarea {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-sold { background-color: #d4edda; color: #155724; }
        .status-follow_up { background-color: #cce5ff; color: #004085; }
        .status-not_sold { background-color: #f8d7da; color: #721c24; }
        .status-cancelled { background-color: #e2e3e5; color: #383d41; }

        @media (max-width: 768px) {
            .edit-container {
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
        <div class="edit-container">
            <!-- رأس الصفحة -->
            <div class="header">
                <h1><i class="fas fa-edit"></i> تعديل حالة الحساب</h1>
                <p class="text-muted">رقم الحساب: #{{ $calculation->id }}</p>
            </div>

            <!-- معلومات الحساب -->
            <div class="info-box">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-label">السيارة</div>
                        <div class="info-value">
                            {{ $calculation->car_brand ?? 'غير محدد' }}
                            ({{ $calculation->carModel->model_year ?? 'غير محدد' }})
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">السعر</div>
                        <div class="info-value">{{ number_format($calculation->car_price, 0) }} ر.س</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">القسط الشهري</div>
                        <div class="info-value">{{ number_format($calculation->monthly_installment_with_insurance, 0) }} ر.س</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">الحالة الحالية</div>
                        <div class="info-value">
                            @if($calculation->status == 'pending')
                                <span class="status-badge status-pending">قيد الانتظار</span>
                            @elseif($calculation->status == 'sold')
                                <span class="status-badge status-sold">تم البيع</span>
                            @elseif($calculation->status == 'follow_up')
                                <span class="status-badge status-follow_up">متابعة</span>
                            @elseif($calculation->status == 'not_sold')
                                <span class="status-badge status-not_sold">لم يتم البيع</span>
                            @elseif($calculation->status == 'cancelled')
                                <span class="status-badge status-cancelled">ملغي</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج تعديل الحالة -->
            <form method="POST" action="{{ route('finance.updateStatus', $calculation->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="status" class="form-label fw-bold">
                        <i class="fas fa-exchange-alt"></i> تغيير الحالة
                    </label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="">اختر الحالة الجديدة</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" 
                                {{ old('status', $calculation->status) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-4">
                    <label for="notes" class="form-label fw-bold">
                        <i class="fas fa-sticky-note"></i> ملاحظات (اختياري)
                    </label>
                    <textarea id="notes" name="notes" class="form-control form-textarea" 
                              rows="4" placeholder="أضف ملاحظات عن سبب تغيير الحالة...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> --}}

                <!-- أزرار التنفيذ -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-save w-100">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('finance.history') }}" class="btn btn-cancel w-100">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </div>
            </form>

            <!-- تاريخ التحديث -->
            <div class="mt-4 pt-3 border-top text-muted text-center">
                <small>
                    <i class="fas fa-calendar"></i> 
                    تاريخ الإنشاء: {{ $calculation->created_at->format('Y-m-d H:i') }} |
                    آخر تحديث: {{ $calculation->updated_at->format('Y-m-d H:i') }}
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تغيير لون الحقل حسب الحالة المختارة
        document.getElementById('status').addEventListener('change', function() {
            const statusColors = {
                'pending': '#fff3cd',
                'sold': '#d4edda',
                'follow_up': '#cce5ff',
                'not_sold': '#f8d7da',
                'cancelled': '#e2e3e5'
            };
            
            this.style.backgroundColor = statusColors[this.value] || '';
            this.style.color = this.value ? '#000' : '';
        });

        // تنشيط اللون عند التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            statusSelect.dispatchEvent(new Event('change'));
        });
    </script>
</body>
</html>