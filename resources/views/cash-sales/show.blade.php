@extends('layouts.app')
@section('title', ' مبيعات كاش')
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
                                <i class="fas fa-eye me-2"></i>تفاصيل العملية
                            </span>
                        </div>

                        <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                            تفاصيل <span class="text-gradient-primary">عملية البيع</span>
                        </h1>
                    </div>

                    <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                        عرض كافة تفاصيل عملية البيع رقم {{ $cashSale->id }}
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

    <!-- Sale Details Section -->
    <section class="sale-details-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header Card -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1">عملية البيع رقم: <span
                                            class="text-primary">{{ $cashSale->id }}</span></h3>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        تاريخ الإضافة: {{ $cashSale->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('cash-sales.edit', $cashSale->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>تعديل
                                    </a>
                                    <a href="{{ route('cash-sales.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-right me-2"></i>رجوع
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Cards -->
                    <div class="row g-4">
                        <!-- معلومات السيارة -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-lg h-100 animate__animated animate__fadeInUp">
                                <div class="card-header bg-gradient-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-car me-2"></i>معلومات السيارة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 70px; height: 70px;">
                                                        <i class="fas fa-car fa-2x"></i>
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1">{{ $cashSale->carBrand->name ?? '' }}</h4>
                                                    <p class="text-muted mb-0">{{ $cashSale->carModel->model_year ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-palette me-2"></i>اللون
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-box me-2"
                                                        style="background-color: {{ $cashSale->car_color }}; width: 20px; height: 20px; border-radius: 4px;">
                                                    </div>
                                                    <span class="fw-bold">{{ $cashSale->car_color }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-tags me-2"></i>الفئة
                                                </h6>
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                                    {{ $cashSale->car_category }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-warehouse me-2"></i>المصدر
                                                </h6>
                                                @if ($cashSale->source == 'in_stock')
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success border border-success">
                                                        <i class="fas fa-warehouse me-1"></i>من المخزون
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                        <i class="fas fa-truck me-1"></i>مشتريات خارجية
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-university me-2"></i>البنك
                                                </h6>
                                                <span class="fw-bold">{{ $cashSale->bank->name ?? 'غير محدد' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات المالية -->
                        <div class="col-lg-6">
                            <div
                                class="card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s">
                                <div class="card-header bg-gradient-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-money-bill-wave me-2"></i>المعلومات المالية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-tag me-2"></i>سعر السيارة
                                                </h6>
                                                <h4 class="text-primary mb-0">{{ number_format($cashSale->car_price, 2) }}
                                                    ر.س</h4>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-money-check-alt me-2"></i>المبلغ المدفوع
                                                </h6>
                                                <h4 class="text-success mb-0">
                                                    {{ number_format($cashSale->paid_amount, 2) }} ر.س</h4>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-calculator me-2"></i>المبلغ المتبقي
                                                </h6>
                                                <h4 class="text-danger mb-0">
                                                    {{ number_format($cashSale->remaining_amount, 2) }} ر.س</h4>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-percentage me-2"></i>نسبة الدفع
                                                </h6>
                                                @php
                                                    $paymentPercentage =
                                                        $cashSale->car_price > 0
                                                            ? ($cashSale->paid_amount / $cashSale->car_price) * 100
                                                            : 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $paymentPercentage }}%;"
                                                            aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                    <span
                                                        class="fw-bold">{{ number_format($paymentPercentage, 1) }}%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-info-circle me-2"></i>حالة الدفع
                                                </h6>
                                                @php
                                                    $statusColors = [
                                                        'pending' => [
                                                            'bg' => 'bg-secondary bg-opacity-10',
                                                            'text' => 'text-secondary',
                                                            'icon' => 'fas fa-clock',
                                                        ],
                                                        'partial_paid' => [
                                                            'bg' => 'bg-info bg-opacity-10',
                                                            'text' => 'text-info',
                                                            'icon' => 'fas fa-money-check-alt',
                                                        ],
                                                        'fully_paid' => [
                                                            'bg' => 'bg-success bg-opacity-10',
                                                            'text' => 'text-success',
                                                            'icon' => 'fas fa-check-circle',
                                                        ],
                                                        'delivered' => [
                                                            'bg' => 'bg-primary bg-opacity-10',
                                                            'text' => 'text-primary',
                                                            'icon' => 'fas fa-truck',
                                                        ],
                                                        'cancelled' => [
                                                            'bg' => 'bg-danger bg-opacity-10',
                                                            'text' => 'text-danger',
                                                            'icon' => 'fas fa-times-circle',
                                                        ],
                                                        'refunded' => [
                                                            'bg' => 'bg-warning bg-opacity-10',
                                                            'text' => 'text-warning',
                                                            'icon' => 'fas fa-undo',
                                                        ],
                                                        'on_hold' => [
                                                            'bg' => 'bg-dark bg-opacity-10',
                                                            'text' => 'text-dark',
                                                            'icon' => 'fas fa-pause-circle',
                                                        ],
                                                    ];
                                                    $statusText = [
                                                        'pending' => 'قيد الانتظار',
                                                        'partial_paid' => 'مدفوع جزئياً',
                                                        'fully_paid' => 'مدفوع بالكامل',
                                                        'delivered' => 'تم التسليم',
                                                        'cancelled' => 'ملغي',
                                                        'refunded' => 'تم الاسترجاع',
                                                        'on_hold' => 'معلق',
                                                    ];
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span
                                                        class="badge {{ $statusColors[$cashSale->payment_status]['bg'] }} {{ $statusColors[$cashSale->payment_status]['text'] }} 
                                                      border {{ $statusColors[$cashSale->payment_status]['text'] }} px-3 py-2">
                                                        <i
                                                            class="{{ $statusColors[$cashSale->payment_status]['icon'] }} me-2 fa-lg"></i>
                                                        {{ $statusText[$cashSale->payment_status] }}
                                                    </span>

                                                    <!-- زر تغيير الحالة -->
                                                    <button class="btn btn-outline-secondary btn-sm ms-3"
                                                        data-bs-toggle="modal" data-bs-target="#statusModal">
                                                        <i class="fas fa-edit me-1"></i>تغيير
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- قسم الدفعات -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-lg">
                                    <div class="card-header bg-gradient-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>سجل الدفعات
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- نموذج إضافة دفعة جديدة -->
                                        <!-- نموذج إضافة دفعة جديدة -->
                                        @if ($cashSale->remaining_amount > 0 && !in_array($cashSale->payment_status, ['cancelled', 'refunded']))
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6>إضافة دفعة جديدة</h6>
                                                    <form method="POST"
                                                        action="{{ route('cash-sales.add-payment', $cashSale->id) }}"
                                                        id="addPaymentForm">
                                                        @csrf
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label class="form-label">المبلغ <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" name="payment_amount"
                                                                    class="form-control @error('payment_amount') is-invalid @enderror"
                                                                    step="0.01" min="0.01"
                                                                    max="{{ $cashSale->remaining_amount }}"
                                                                    value="{{ old('payment_amount') }}" required>
                                                                @error('payment_amount')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">تاريخ الدفع <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="date" name="payment_date"
                                                                    class="form-control @error('payment_date') is-invalid @enderror"
                                                                    value="{{ old('payment_date', date('Y-m-d')) }}"
                                                                     required>
                                                                @error('payment_date')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">طريقة الدفع <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="payment_method"
                                                                    class="form-select @error('payment_method') is-invalid @enderror"
                                                                    required>
                                                                    <option value="نقدي"
                                                                        {{ old('payment_method') == 'نقدي' ? 'selected' : '' }}>
                                                                        نقدي</option>
                                                                    <option value="تحويل بنكي"
                                                                        {{ old('payment_method') == 'تحويل بنكي' ? 'selected' : '' }}>
                                                                        تحويل بنكي</option>
                                                                    <option value="شيك"
                                                                        {{ old('payment_method') == 'شيك' ? 'selected' : '' }}>
                                                                        شيك</option>
                                                                </select>
                                                                @error('payment_method')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">ملاحظات</label>
                                                                <textarea name="payment_notes" class="form-control @error('payment_notes') is-invalid @enderror" rows="2">{{ old('payment_notes') }}</textarea>
                                                                @error('payment_notes')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <button type="submit" class="btn btn-success"
                                                                    id="submitPaymentBtn">
                                                                    <i class="fas fa-plus-circle me-2"></i>إضافة الدفعة
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="alert alert-info h-100 d-flex flex-column justify-content-center">
                                                        <h6 class="alert-heading">ملخص الدفع</h6>
                                                        <p class="mb-1">سعر السيارة:
                                                            <strong>{{ number_format($cashSale->car_price, 2) }}
                                                                ر.س</strong></p>
                                                        <p class="mb-1">المبلغ المدفوع:
                                                            <strong>{{ number_format($cashSale->paid_amount, 2) }}
                                                                ر.س</strong></p>
                                                        <p class="mb-1">المبلغ المتبقي:
                                                            <strong>{{ number_format($cashSale->remaining_amount, 2) }}
                                                                ر.س</strong></p>
                                                        <p class="mb-0">نسبة الدفع:
                                                            <strong>{{ $cashSale->car_price > 0 ? number_format(($cashSale->paid_amount / $cashSale->car_price) * 100, 1) : 0 }}%</strong>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif
                                        <!-- عرض سجل الدفعات -->
                                        <h6>الدفعات السابقة</h6>
                                        @php
                                            // تحويل payments إلى مصفوفة إذا لزم الأمر
                                            $payments = is_array($cashSale->payments) ? $cashSale->payments : [];
                                        @endphp

                                        @if (!empty($payments))
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>المبلغ</th>
                                                            <th>تاريخ الدفع</th>
                                                            <th>ملاحظات</th>
                                                            <th>تاريخ الإضافة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cashSale->payments as $payment)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td class="fw-bold text-success">
                                                                    {{ number_format($payment['amount'], 2) }} ر.س</td>
                                                                <td>{{ \Carbon\Carbon::parse($payment['date'])->format('Y-m-d') }}
                                                                </td>
                                                                <td>{{ $payment['notes'] ?? '-' }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('Y-m-d H:i') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="table-primary">
                                                            <td colspan="5" class="text-end fw-bold">
                                                                إجمالي المدفوع:
                                                                {{ number_format($cashSale->paid_amount, 2) }} ر.س
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-money-bill-slash fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد دفعات مسجلة حتى الآن</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- الملاحظات والمعلومات الإضافية -->
                        <div class="col-12">
                            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp animate__delay-2s">
                                <div class="card-header bg-gradient-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-sticky-note me-2"></i>ملاحظات ومعلومات إضافية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($cashSale->notes)
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">الملاحظات:</h6>
                                            <div class="bg-light p-3 rounded">
                                                {{ $cashSale->notes }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-user me-2"></i>أضيف بواسطة
                                                </h6>
                                                <span class="fw-bold">{{ $cashSale->user->name ?? 'غير معروف' }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-calendar-plus me-2"></i>تاريخ الإضافة
                                                </h6>
                                                <span
                                                    class="fw-bold">{{ $cashSale->created_at->format('Y-m-d H:i') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-calendar-edit me-2"></i>آخر تحديث
                                                </h6>
                                                <span
                                                    class="fw-bold">{{ $cashSale->updated_at->format('Y-m-d H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>تغيير حالة الدفع
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('cash-sales.update-status', $cashSale->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">الحالة الحالية:</label>
                            <div class="alert alert-info">
                                <i class="{{ $statusColors[$cashSale->payment_status]['icon'] }} me-2"></i>
                                {{ $statusText[$cashSale->payment_status] }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">اختر الحالة الجديدة:</label>
                            <select name="status" class="form-select" required>
                                @foreach ($statusText as $key => $text)
                                    <option value="{{ $key }}"
                                        {{ $cashSale->payment_status == $key ? 'selected' : '' }}>
                                        {{ $text }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغيير</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Buttons -->
    <div class="floating-buttons">
        <a href="{{ route('cash-sales.edit', $cashSale->id) }}" class="floating-btn edit-btn">
            <i class="fas fa-edit"></i>
            <span class="tooltip">تعديل العملية</span>
        </a>

        <a href="{{ route('cash-sales.index') }}" class="floating-btn back-btn">
            <i class="fas fa-arrow-right"></i>
            <span class="tooltip">رجوع للقائمة</span>
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            color: white;
            min-height: 40vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .sale-details-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 60vh;
        }

        .card-header.bg-gradient-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
        }

        .card-header.bg-gradient-success {
            background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%) !important;
        }

        .card-header.bg-gradient-info {
            background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%) !important;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .color-box {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .avatar-lg {
            display: flex;
            align-items: center;
            justify-content: center;
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

        .edit-btn {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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

        /* Progress Bar */
        .progress {
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 5px;
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
        document.addEventListener('DOMContentLoaded', function() {
            // عند تغيير الحالة عبر Ajax
            const statusForm = document.querySelector('#statusModal form');
            if (statusForm) {
                statusForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // إعادة تحميل الصفحة لتحديث الحالة
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء تحديث الحالة');
                        });
                });
            }
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من صحة مبلغ الدفعة
    const paymentForm = document.getElementById('addPaymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const paymentAmount = parseFloat(this.querySelector('input[name="payment_amount"]').value) || 0;
            const remainingAmount = parseFloat('{{ $cashSale->remaining_amount }}') || 0;
            
            if (paymentAmount > remainingAmount) {
                e.preventDefault();
                alert('المبلغ المدفوع لا يمكن أن يكون أكبر من المبلغ المتبقي (' + remainingAmount + ' ر.س)');
                return false;
            }
            
            if (paymentAmount <= 0) {
                e.preventDefault();
                alert('يجب أن يكون المبلغ أكبر من صفر');
                return false;
            }
            
            // تعطيل الزر لمنع النقر المتكرر
            const submitBtn = document.getElementById('submitPaymentBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
            
            return true;
        });
    }
    
    // تحديث الحد الأقصى للمبلغ تلقائياً
    const paymentAmountInput = document.querySelector('input[name="payment_amount"]');
    if (paymentAmountInput) {
        paymentAmountInput.addEventListener('input', function() {
            const maxAmount = parseFloat('{{ $cashSale->remaining_amount }}') || 0;
            if (parseFloat(this.value) > maxAmount) {
                this.value = maxAmount;
            }
        });
    }
});
</script>
@endpush
