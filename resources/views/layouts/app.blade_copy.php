<!-- <!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة بيانات السيارة - {{ $car->code }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .paper {
                box-shadow: none;
                padding: 0;
            }

            .print-button {
                display: none;
            }
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .paper {
            width: 210mm;
            min-height: 297mm;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 15mm 15mm 10mm 15mm;
            margin: 0 auto;
            border-radius: 5px;
        }

        .print-container {
            max-width: 100%;
        }

        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            flex: 0 0 80px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            background: white;
            padding: 5px;
            border: 1px solid #ccc;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .qr-code {
            text-align: center;
            flex: 0 0 100px;
        }

        .qr-code svg {
            width: 80px;
           
            height: 80px;
            filter: grayscale(100%);
        }

        .qr-code small {
            display: block;
            color: #555;
            font-size: 11px;
            margin-top: 3px;
        }

        .car-info {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .car-image {
            flex: 0 0 200px;
            background: #f5f5f5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 14px;
            height: 120px;
            border: 1px solid #ccc;
            overflow: hidden;
        }

        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .car-summary {
            flex: 1;
        }

        .car-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .car-summary table td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
        }

        .car-summary table td:first-child {
            font-weight: bold;
            width: 35%;
            color: #333;
        }

        .color-dot {
            display: inline-block;
            width: 15px;
            height: 15px;
            background: {{ $car->color ?? '#ccc' }};
            border: 1px solid #666;
            vertical-align: middle;
            margin-left: 5px;
            border-radius: 2px;
            filter: grayscale(100%);
        }

       
        .specs-section {
            margin-top: 25px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        .specs-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .spec-item {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .spec-item .label {
            font-size: 13px;
            color: #555;
            margin-bottom: 4px;
        }

        .spec-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #222;
        }

       
        .price-box {
            margin-top: 30px;
            background: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #aaa;
        }

        .price-box .label {
            font-size: 16px;
            color: #333;
        }

        .price-box .value {
            font-size: 28px;
            font-weight: bold;
            color: #000;
        }

        .price-box small {
            display: block;
            color: #555;
            margin-top: 5px;
        }

        
        .features {
            margin-top: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .feature-badge {
            background: #e0e0e0;
            color: #333;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid #888;
        }

        .description-box {
            margin-top: 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px dashed #aaa;
            padding-top: 15px;
        }

       
        .print-button {
            text-align: center;
            margin-top: 20px;
        }

        .print-button button {
            background: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>

<body>
    <div class="paper">
        <div class="print-container">
          
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="شعار الشركة">
                </div>
                <div class="company-name">
                    شركة العربة الفريدة للسيارات
                </div>
                <div class="qr-code">
                    {!! QrCode::size(80)->generate($car->code) !!}
                    <small>كود: {{ $car->code }}</small>
                </div>
            </div>

           
            <div class="car-info">
                <div class="car-image">
                    @if ($car->mainImage)
                        <img src="{{ asset('storage/' . $car->mainImage->image_path) }}" alt="صورة السيارة">
                    @else
                        <span>لا توجد صورة</span>
                    @endif
                </div>
                <div class="car-summary">
                    <table>
                        <tr>
                            <td>العلامة التجارية:</td>
                            <td>{{ $car->brand->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td>الموديل:</td>
                            <td>{{ $car->type->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td>الفئة:</td>
                            <td>{{ $car->trim->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td>سنة الموديل:</td>
                            <td>{{ $car->model_year ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td>اللون:</td>
                            <td><span class="color-dot"></span> {{ $car->color ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td>الممشي:</td>
                            <td>{{ number_format($car->mileage) ?? '0' }} كم</td>
                        </tr>
                        <tr>
                            <td>الحالة:</td>
                            <td>
                                @php
                                    $conditions = [
                                        'new' => 'جديدة',
                                        'used' => 'مستعملة',
                                        'salvage' => 'تشليح',
                                        'refurbished' => 'مجددة',
                                    ];
                                @endphp
                                {{ $conditions[$car->condition] ?? $car->condition }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

           
            <div class="specs-section">
                <div class="specs-title">المواصفات الفنية</div>
                <div class="specs-grid">
                    <div class="spec-item">
                        <div class="label">ناقل الحركة</div>
                        <div class="value">{{ $car->transmission->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">نوع الوقود</div>
                        <div class="value">{{ $car->fuelType->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">نوع الدفع</div>
                        <div class="value">{{ $car->driveType->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">سعة المحرك</div>
                        <div class="value">
                            {{ $car->engine_capacity ? number_format($car->engine_capacity) . ' سي سي' : 'غير محدد' }}
                        </div>
                    </div>
                    <div class="spec-item">
                        <div class="label">القوة الحصانية</div>
                        <div class="value">
                            {{ $car->horse_power ? number_format($car->horse_power) . ' حصان' : 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">عدد الأسطوانات</div>
                        <div class="value">{{ $car->cylinders ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">عدد الأبواب</div>
                        <div class="value">{{ $car->doors ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label">عدد المقاعد</div>
                        <div class="value">{{ $car->seats ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

           
            <div class="price-box">
                <div class="label">سعر البيع</div>
                <div class="value">{{ number_format($car->selling_price, 2) }} ر.س</div>
                @if ($car->is_negotiable)
                    <small>(قابل للتفاوض)</small>
                @endif
            </div>

           
            <div class="specs-section">
                <div class="specs-title">معلومات إضافية</div>
                <div class="features">
                    @if ($car->is_featured)
                        <span class="feature-badge"><i class="fas fa-star"></i> مميزة</span>
                    @endif
                    @if ($car->is_financeable)
                        <span class="feature-badge"><i class="fas fa-hand-holding-usd"></i> قابلة للتمويل</span>
                    @endif
                    @if ($car->is_negotiable)
                        <span class="feature-badge"><i class="fas fa-comments"></i> قابل للتفاوض</span>
                    @endif
                </div>
                @if ($car->description)
                    <div class="description-box">
                        <strong>الوصف:</strong> {{ $car->description }}
                    </div>
                @endif
            </div>

           
            <div class="footer">
                تم إنشاء هذا المستند في {{ now()->format('Y-m-d H:i') }} - جميع الحقوق محفوظة © {{ date('Y') }}
            </div>

            
            <div class="print-button">
                <button onclick="window.print()">طباعة</button>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html> -->
