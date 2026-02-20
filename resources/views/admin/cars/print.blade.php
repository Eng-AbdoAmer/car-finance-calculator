<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة بيانات السيارة - {{ $car->code }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');

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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 10mm 12mm 8mm 12mm;
            /* تقليل الهوامش الداخلية */
            margin: 0 auto;
            border-radius: 8px;
        }

        .print-container {
            max-width: 100%;
        }

        /* ========== الهيدر ========== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* تغيير من stretch إلى center لتقليل الارتفاع */
            border-bottom: 3px solid #4361ee;
            padding-bottom: 10px;
            /* تقليل */
            margin-bottom: 15px;
            /* تقليل */
            gap: 10px;
            /* تقليل */
        }

        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            /* تقليل */
            max-width: 60%;
        }

        .logo-area img {
            height: 100px;
            /* تصغير قليلاً */
            width: auto;
            max-width: 180px;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 4px;
            background: white;
        }

        .company-name {
            font-size: 18px;
            /* تصغير الخط */
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.3;
            text-align: right;
        }

        .company-name small {
            display: block;
            font-size: 12px;
            /* تصغير */
            font-weight: 400;
            color: #5e6e7e;
            margin-top: 2px;
        }

        .company-name .print-date {
            display: block;
            margin-top: 5px;
            /* تقليل */
            font-size: 12px;
            /* تصغير */
            color: #6c7a8d;
            background: #f1f5f9;
            padding: 4px 10px;
            /* تقليل */
            border-radius: 30px;
            width: fit-content;
        }

        .qr-section {
            text-align: center;
            background: #f8fafc;
            padding: 5px 8px;
            /* تقليل */
            border-radius: 12px;
            align-self: center;
        }

        .qr-section svg {
            width: 75px;
            /* تصغير */
            height: 75px;
            display: block;
            margin: 0 auto 3px;
        }

        .qr-section small {
            color: #2c3e50;
            font-size: 10px;
            /* تصغير */
            font-weight: 500;
            background: white;
            padding: 2px 6px;
            border-radius: 50px;
            display: inline-block;
        }

        /* صورة وملخص السيارة */
        .car-info {
            display: flex;
            gap: 20px;
            /* تقليل كبير (كان 65px) */
            margin-bottom: 10px;
            /* تقليل */
            background: #f9fbfd;
            padding: 10px 12px;
            /* تقليل */
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .car-image {
            flex: 0 0 180px;
            /* تصغير العرض */
            background: #e9edf2;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 14px;
            height: 200px;
            /* تقليل الارتفاع */
            border: 1px solid #d0d9e8;
            overflow: hidden;
        }

        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .car-summary {
            flex: 1;
        }

        .car-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .car-summary table td {
            padding: 6px 5px;
            /* تقليل */
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            /* تصغير */
        }

        .car-summary table td:first-child {
            font-weight: 600;
            width: 35%;
            color: #2c3e50;
            background-color: #f1f5f9;
            border-radius: 8px 0 0 8px;
        }

        .car-summary table td:last-child {
            color: #1e293b;
            font-weight: 500;
        }

        .color-dot {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: {{ $car->color ?? '#ccc' }};
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #aaa;
            vertical-align: middle;
            margin-left: 6px;
            border-radius: 4px;
        }

        /* المواصفات الفنية */
        .specs-section {
            margin-top: 8px;
            /* تقليل */
            border-top: 2px solid #e9ecf2;
            padding-top: 5px;
            /* تقليل */
        }

        .specs-title {
            font-size: 18px;
            /* تصغير */
            color: #0f2b4b;
            margin-bottom: 10px;
            /* تقليل */
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .specs-title i {
            color: #4361ee;
            font-size: 20px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            /* تقليل */
        }

        .spec-item {
            background: #ffffff;
            padding: 10px 8px;
            /* تقليل */
            border-radius: 12px;
            /* تصغير */
            border: 1px solid #eef2f6;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .spec-item .label {
            font-size: 13px;
            /* تصغير */
            color: #64748b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .spec-item .label i {
            color: #4361ee;
            width: 16px;
            font-size: 13px;
        }

        .spec-item .value {
            font-size: 16px;
            /* تصغير */
            font-weight: 700;
            color: #0b1e33;
        }

        /* صندوق السعر */
        .price-box {
            margin-top: 8px;
            /* تقليل */
            background: linear-gradient(145deg, #eef4ff, #e0eaff);
            padding: 12px 20px;
            /* تقليل */
            border-radius: 50px 20px 20px 50px;
            /* تعديل */
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #cdddfd;
        }

        .price-box .label {
            font-size: 16px;
            /* تصغير */
            color: #1e3a8a;
            font-weight: 600;
        }

        .price-box .value {
            font-size: 28px;
            /* تصغير */
            font-weight: 800;
            color: #0f2b4b;
            letter-spacing: -0.5px;
        }

        .price-box small {
            display: block;
            color: #4b5565;
            margin-top: 2px;
            font-size: 12px;
        }

        /* الميزات والوصف */
        .features {
            margin-top: 10px;
            /* تقليل */
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            /* تقليل */
        }

        .feature-badge {
            background: #ecf2ff;
            color: #1e3a8a;
            padding: 5px 12px;
            /* تقليل */
            border-radius: 30px;
            /* تصغير */
            font-size: 13px;
            /* تصغير */
            font-weight: 500;
            border: 1px solid #b9ceff;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .description-box {
            margin-top: 5px;
            /* تقليل */
            background: #f9fcff;
            padding: 12px 16px;
            /* تقليل */
            border-radius: 16px;
            /* تصغير */
            border-right: 5px solid #4361ee;
            line-height: 1.5;
            color: #2d3a4f;
            font-size: 14px;
            /* تصغير */
        }

        .footer {
            margin-top: 8px;
            /* تقليل */
            text-align: center;
            color: #9aa6b5;
            font-size: 12px;
            /* تصغير */
            border-top: 2px dashed #dbe4f0;
            padding-top: 10px;
            /* تقليل */
        }

        .print-button {
            text-align: center;
            margin-top: 15px;
            /* تقليل */
        }

        .print-button button {
            background: #1e293b;
            color: white;
            border: none;
            padding: 8px 25px;
            /* تقليل */
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Tajawal', sans-serif;
            font-weight: 600;
            box-shadow: 0 8px 18px #1e293b30;
            transition: 0.2s;
        }

        .print-button button:hover {
            background: #0f172a;
            transform: scale(1.02);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .paper {
                box-shadow: none;
                padding: 8mm 10mm 6mm 10mm;
                /* تقليل أكثر للطباعة */
                border-radius: 0;
            }

            .print-button {
                display: none;
            }

            .car-info {
                background: none;
                box-shadow: none;
                padding: 5px 0;
            }
        }
    </style>
</head>

<body>
    <div class="paper">
        <div class="print-container">
            <!-- الهيدر -->
            <div class="header">
                <div class="logo-area">
                    <img src="{{ asset('img/logo-removebg-black.png') }}" alt="شعار الشركة"
                        onerror="this.style.display='none'">
                </div>
                <div class="company-name">
                    شركة العربه الفريدة للسيارات
                    <small>وكيل معتمد لجميع أنواع السيارات</small>
                    <span class="print-date">
                        <i class="far fa-calendar-alt"></i> {{ now()->format('Y/m/d') }}
                        <i class="far fa-clock" style="margin-right: 8px;"></i> {{ now()->format('H:i') }}
                    </span>
                </div>
                <div class="qr-section">
                    {!! QrCode::size(75)->generate($car->code) !!}
                    <small>كود: {{ $car->code }}</small>
                </div>
            </div>

            <!-- صورة وملخص سريع -->
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
                            <td><i class="fas fa-car"></i> العلامة التجارية</td>
                            <td>{{ $car->brand->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-tag"></i> الموديل</td>
                            <td>{{ $car->type->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-layer-group"></i> الفئة</td>
                            <td>{{ $car->trim->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar-alt"></i> سنة الموديل</td>
                            <td>{{ $car->model_year ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-palette"></i> اللون</td>
                            <td><span class="color-dot"></span> {{ $car->color ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-tachometer-alt"></i> الممشي</td>
                            <td>{{ number_format($car->mileage) ?? '0' }} كم</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-info-circle"></i> الحالة</td>
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

            <!-- المواصفات الفنية -->
            <div class="specs-section">
                <div class="specs-title"><i class="fas fa-cogs"></i> المواصفات الفنية</div>
                <div class="specs-grid">
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-gear"></i> ناقل الحركة</div>
                        <div class="value">{{ $car->transmission->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-gas-pump"></i> نوع الوقود</div>
                        <div class="value">{{ $car->fuelType->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-truck"></i> نوع الدفع</div>
                        <div class="value">{{ $car->driveType->name ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-engine"></i> سعة المحرك</div>
                        <div class="value">
                            {{ $car->engine_capacity ? number_format($car->engine_capacity) . ' سي سي' : 'غير محدد' }}
                        </div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-horse"></i> القوة الحصانية</div>
                        <div class="value">
                            {{ $car->horse_power ? number_format($car->horse_power) . ' حصان' : 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-bolt"></i> عدد الأسطوانات</div>
                        <div class="value">{{ $car->cylinders ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-car-side"></i> عدد الأبواب</div>
                        <div class="value">{{ $car->doors ?? 'غير محدد' }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="label"><i class="fas fa-chair"></i> عدد المقاعد</div>
                        <div class="value">{{ $car->seats ?? 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <!-- صندوق السعر -->
            <div class="price-box">
                <span class="label">🏷️ سعر البيع</span>
                <span class="value">{{ number_format($car->selling_price, 2) }} <span
                        style="font-size: 16px;">ر.س</span></span>
                @if ($car->is_negotiable)
                    <small>(قابل للتفاوض)</small>
                @endif
            </div>

            <!-- ميزات إضافية ووصف -->
            <div class="specs-section">
                <div class="specs-title"><i class="fas fa-star"></i> ميزات إضافية</div>
                <div class="features">
                    @if ($car->is_featured)
                        <span class="feature-badge"><i class="fas fa-crown"></i> مميزة</span>
                    @endif
                    @if ($car->is_financeable)
                        <span class="feature-badge"><i class="fas fa-hand-holding-usd"></i> قابلة للتمويل</span>
                    @endif
                    @if ($car->is_negotiable)
                        <span class="feature-badge"><i class="fas fa-handshake"></i> قابل للتفاوض</span>
                    @endif
                </div>
                @if ($car->description)
                    <div class="description-box"><strong>📝 الوصف:</strong> {{ $car->description }}</div>
                @endif
            </div>

            <!-- تذييل -->
            <div class="footer">
                <i class="far fa-copyright"></i> جميع الحقوق محفوظة لشركة العربية الفريدة للسيارات
            </div>

            <!-- زر الطباعة -->
            <div class="print-button">
                <button onclick="window.print()"><i class="fas fa-print" style="margin-left: 8px;"></i> طباعة</button>
            </div>
        </div>
    </div>
</body>

</html>
