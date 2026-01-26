<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حاسبة تمويل السيارات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }

        .calculator-container {
            max-width: 800px;
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

        .form-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-calculate {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-calculate:hover {
            background: linear-gradient(135deg, #2980b9, #1c5a7a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .alert {
            border-radius: 10px;
            border: none;
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

        @media (max-width: 768px) {
            .calculator-container {
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
        <div class="calculator-container">
            <div class="header">
                <h1>حاسبة تمويل السيارات من بنك الراجحي</h1>
                <p class="text-muted">احسب قسط سيارتك بكل سهولة ودقة</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5 class="alert-heading">يوجد أخطاء في المدخلات:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('finance.calculate') }}">
                @csrf

                <div class="form-section">
                    <h4 class="section-title">معلومات السيارة</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">سعر السيارة (ريال)</label>
                            <input type="number" name="car_price" class="form-control"
                                value="{{ old('car_price', 130000) }}" required step="0.01" min="0">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نوع السيارة (العلامة التجارية)</label>
                            <select name="car_brand" class="form-select" required>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->name }}"
                                        {{ old('car_brand', 'Toyota') == $brand->name ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">موديل السيارة</label>
                            <select name="car_model_id" id="car_model_id" class="form-select" required>
                                <option value="">اختر الموديل</option>
                                @foreach ($carModels as $model)
                                    <option value="{{ $model->id }}"
                                        {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
                                        {{ $model->model_year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">شروط التمويل</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">نسبة الدفعة الأولى (%)</label>
                            <input type="number" name="down_payment_percentage" class="form-control"
                                value="{{ old('down_payment_percentage', 0) }}" step="0.01" min="0"
                                max="100">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">المدة بالأشهر</label>
                            <input type="number" name="loan_term_months" class="form-control"
                                value="{{ old('loan_term_months', 60) }}" required min="1" max="120">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">نسبة الدفعة الأخيرة (%)</label>
                            <input type="number" name="final_payment_percentage" class="form-control"
                                value="{{ old('final_payment_percentage', 45) }}" step="0.01" min="0"
                                max="100">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">التكاليف والرسوم</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نسبة هامش الربح السنوية (%)</label>
                            <input type="number" name="profit_margin_percentage" class="form-control"
                                value="{{ old('profit_margin_percentage', 6.54) }}" step="0.01" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">نسبة الرسوم الإدارية (%)</label>
                            <input type="number" name="administrative_fees_percentage" class="form-control"
                                value="{{ old('administrative_fees_percentage', 1) }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">معلومات العميل للتأمين</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الجنس</label>
                            <select name="gender" class="form-select" required>
                                <option value="male" {{ old('gender', 'male') == 'male' ? 'selected' : '' }}>ذكر
                                </option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" name="phone" class="form-control" value=""
                                placeholder="مثال: 0501234567">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الفئة العمرية</label>
                            <select name="age_bracket" class="form-select" required>
                                @foreach ($ageBrackets as $bracket)
                                    <option value="{{ $bracket->name }}"
                                        {{ old('age_bracket', '25 to 30') == $bracket->name ? 'selected' : '' }}>
                                        {{ $bracket->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-calculate">
                    <i class="fas fa-calculator"></i> احسب القسط
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>
