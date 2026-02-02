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
                            <i class="fas fa-cash-register me-2"></i>مبيعات الكاش
                        </span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                        إدارة <span class="">مبيعات الكاش</span>
                    </h1>
                </div>
                
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                    نظام متكامل لإدارة عمليات البيع النقدي للسيارات مع تقارير تفصيلية وتتبع لحظي
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

<!-- Main Content -->
<section class="cash-sales-section py-5">
    <div class="container">
        <!-- إحصائيات سريعة -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="section-header mb-4">
                    <h3 class="fw-bold mb-3 text-center text-white">
                        <i class="fas fa-chart-line me-2 text-white"></i>الإحصائيات السريعة
                    </h3>
                </div>
                
                <div class="row g-4">
                    <!-- إجمالي المبيعات -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp">
                            <div class="card-body p-4 text-center">
                                <div class="stats-icon mb-3">
                                    <div class="icon-wrapper bg-gradient-primary rounded-circle p-3">
                                        <i class="fas fa-shopping-cart fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2 text-white">إجمالي المبيعات</h4>
                                <h2 class="mb-0 text-primary">{{ number_format($stats['total']) }}</h2>
                                <p class="text-muted mb-0">عملية بيع</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- إجمالي المبالغ -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s">
                            <div class="card-body p-4 text-center">
                                <div class="stats-icon mb-3">
                                    <div class="icon-wrapper bg-gradient-success rounded-circle p-3">
                                        <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2 text-white">إجمالي المبالغ</h4>
                                <h2 class="text-success mb-0">{{ number_format($stats['total_amount'], 2) }}</h2>
                                <p class="text-muted mb-0">ريال سعودي</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- إجمالي المدفوع -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s">
                            <div class="card-body p-4 text-center">
                                <div class="stats-icon mb-3">
                                    <div class="icon-wrapper bg-gradient-info rounded-circle p-3">
                                        <i class="fas fa-check-circle fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2 text-white">إجمالي المدفوع</h4>
                                <h2 class="text-info mb-0">{{ number_format($stats['total_paid'], 2) }}</h2>
                                <p class="text-muted mb-0">ريال سعودي</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- إجمالي المتبقي -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-3s">
                            <div class="card-body p-4 text-center">
                                <div class="stats-icon mb-3">
                                    <div class="icon-wrapper bg-gradient-warning rounded-circle p-3">
                                        <i class="fas fa-clock fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2 text-white">إجمالي المتبقي</h4>
                                <h2 class="text-warning mb-0">{{ number_format($stats['total_remaining'], 2) }}</h2>
                                <p class="text-muted mb-0">ريال سعودي</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- رأس الصفحة مع الأزرار -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">
                                    <i class="fas fa-list me-2 text-primary"></i>
                                    قائمة عمليات البيع
                                </h3>
                                <p class="text-muted mb-0">إدارة وتتبع جميع عمليات البيع النقدي</p>
                            </div>
                            <div>
                                <a href="{{ route('cash-sales.create') }}" class="btn btn-primary me-2">
                                    <i class="fas fa-plus-circle me-2"></i>إضافة بيع جديد
                                </a>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-2"></i>فلتر
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- عرض الرسائل -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        <!-- جدول المبيعات -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0" id="cashSalesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>السيارة</th>
                                        <th class="text-center">اللون</th>
                                        <th class="text-center">السعر</th>
                                        <th class="text-center">المدفوع</th>
                                        <th class="text-center">المتبقي</th>
                                        <th class="text-center">المصدر</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">التاريخ</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashSales as $sale)
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="text-center fw-bold">{{ $sale->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-car"></i>
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0 text-white">{{ $sale->carBrand->name ?? '' }}</h6>
                                                    <small class="text-white">{{ $sale->carModel->model_year ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge" style="background-color: {{ $sale->car_color }}; color: white; min-width: 70px;">
                                                {{ $sale->car_color }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-primary">{{ number_format($sale->car_price, 2) }} ر.س</td>
                                        <td class="text-center">
                                            <span class="text-success fw-bold">{{ number_format($sale->paid_amount, 2) }} ر.س</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-danger fw-bold">{{ number_format($sale->remaining_amount, 2) }} ر.س</span>
                                        </td>
                                        <td class="text-center">
                                            @if($sale->source == 'in_stock')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                                    <i class="fas fa-warehouse me-1"></i>مخزون
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                    <i class="fas fa-truck me-1"></i>خارجي
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusColors = [
                                                    'pending' => ['bg' => 'bg-secondary bg-opacity-10', 'text' => 'text-secondary', 'icon' => 'fas fa-clock'],
                                                    'partial_paid' => ['bg' => 'bg-info bg-opacity-10', 'text' => 'text-info', 'icon' => 'fas fa-money-check-alt'],
                                                    'fully_paid' => ['bg' => 'bg-success bg-opacity-10', 'text' => 'text-success', 'icon' => 'fas fa-check-circle'],
                                                    'delivered' => ['bg' => 'bg-primary bg-opacity-10', 'text' => 'text-primary', 'icon' => 'fas fa-truck'],
                                                    'cancelled' => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger', 'icon' => 'fas fa-times-circle'],
                                                    'refunded' => ['bg' => 'bg-warning bg-opacity-10', 'text' => 'text-warning', 'icon' => 'fas fa-undo'],
                                                    'on_hold' => ['bg' => 'bg-dark bg-opacity-10', 'text' => 'text-dark', 'icon' => 'fas fa-pause-circle']
                                                ];
                                                $statusText = [
                                                    'pending' => 'قيد الانتظار',
                                                    'partial_paid' => 'مدفوع جزئياً',
                                                    'fully_paid' => 'مدفوع بالكامل',
                                                    'delivered' => 'تم التسليم',
                                                    'cancelled' => 'ملغي',
                                                    'refunded' => 'تم الاسترجاع',
                                                    'on_hold' => 'معلق'
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusColors[$sale->payment_status]['bg'] }} {{ $statusColors[$sale->payment_status]['text'] }} border {{ $statusColors[$sale->payment_status]['text'] }}">
                                                <i class="{{ $statusColors[$sale->payment_status]['icon'] }} me-1"></i>
                                                {{ $statusText[$sale->payment_status] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="">{{ $sale->created_at->format('Y-m-d') }}</span><br>
                                            <small class="text-muted">{{ $sale->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('cash-sales.show', $sale->id) }}" 
                                                   class="btn btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('cash-sales.edit', $sale->id) }}" 
                                                   class="btn btn-outline-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $sale->id }}"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- الترقيم -->
                        @if($cashSales->hasPages())
                        <div class="card-footer border-0 bg-transparent">
                            <div class="d-flex justify-content-center">
                                {{ $cashSales->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals للحذف (يجب أن تكون خارج الجدول تماماً) -->
@foreach($cashSales as $sale)
<div class="modal fade" id="deleteModal{{ $sale->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="deleteModalLabel{{ $sale->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <div class="avatar-lg mx-auto bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-trash fa-2x"></i>
                    </div>
                </div>
                <h5 class="mb-3">هل أنت متأكد من حذف هذه العملية؟</h5>
                <p class="text-white mb-0">
                    عملية البيع رقم: <strong>{{ $sale->id }}</strong><br>
                    ماركة السيارة: <strong>{{ $sale->carBrand->name ?? 'غير محدد' }}</strong><br>
                    المبلغ الإجمالي: <strong>{{ number_format($sale->car_price, 2) }} ر.س</strong><br>
                    <small class="text-danger">هذا الإجراء لا يمكن التراجع عنه</small>
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('cash-sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash me-2"></i>حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal للفلترة -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="filterModalLabel">
                    <i class="fas fa-filter me-2"></i>فلتر المبيعات
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('cash-sales.index') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">حالة الدفع</label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="partial_paid" {{ request('status') == 'partial_paid' ? 'selected' : '' }}>مدفوع جزئياً</option>
                                <option value="fully_paid" {{ request('status') == 'fully_paid' ? 'selected' : '' }}>مدفوع بالكامل</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">مصدر السيارة</label>
                            <select name="source" class="form-select form-select-lg">
                                <option value="">جميع المصادر</option>
                                <option value="in_stock" {{ request('source') == 'in_stock' ? 'selected' : '' }}>من المخزون</option>
                                <option value="external_purchase" {{ request('source') == 'external_purchase' ? 'selected' : '' }}>مشتريات خارجية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">من تاريخ</label>
                            <input type="date" name="start_date" class="form-control form-control-lg" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">إلى تاريخ</label>
                            <input type="date" name="end_date" class="form-control form-control-lg" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">البنك</label>
                            <select name="bank_id" class="form-select form-select-lg">
                                <option value="">جميع البنوك</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{ request('bank_id') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-white">موديل السيارة</label>
                            <select name="car_model_id" class="form-select form-select-lg">
                                <option value="">جميع الموديلات</option>
                                @foreach($carModels as $model)
                                <option value="{{ $model->id }}" {{ request('car_model_id') == $model->id ? 'selected' : '' }}>
                                    {{ $model->brand->name ?? '' }} - {{ $model->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="{{ route('cash-sales.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>إلغاء الفلتر
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check me-2"></i>تطبيق الفلتر
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Floating Buttons -->
<div class="floating-buttons">
    <a href="{{ route('cash-sales.create') }}" class="floating-btn add-btn animate__animated animate__bounceIn animate__delay-1s">
        <i class="fas fa-plus"></i>
        <span class="tooltip">إضافة بيع جديد</span>
    </a>
    
    <button class="floating-btn filter-btn animate__animated animate__bounceIn" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fas fa-filter"></i>
        <span class="tooltip">فلتر المبيعات</span>
    </button>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    color: white;
    min-height: 50vh;
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

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
}

.stats-icon .icon-wrapper {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.stats-card:hover .icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffd166 0%, #ff9e00 100%) !important;
}

/* Table Styling */
.table-hover tbody tr {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    transform: translateX(5px);
}

.avatar-sm, .avatar-lg {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Floating Buttons */
.floating-buttons {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 1050; /* تأكد أن الأزرار فوق الـ modals */
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
    border: none;
    z-index: 1051; /* تأكد أن الأزرار فوق كل شيء */
}

.add-btn {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
}

.filter-btn {
    background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
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

/* Modal Styling - تصحيح مشاكل الـ z-index */
.modal {
    color: #ffff !important;
    z-index: 1060 !important; /* تأكد أن الـ modal فوق كل شيء */
}

.modal-backdrop {
    z-index: 1055 !important; /* تأكد أن الخلفية تحت الـ modal */
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
}

.modal-header.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 1rem 2rem;
}

/* إصلاح مشاكل الـ z-index للجدول */
.table-responsive {
    position: relative;
    z-index: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 40vh;
        padding: 80px 0 40px;
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
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .stats-card .card-body {
        padding: 1.5rem !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Badge Customization */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* Pagination Customization */
.pagination {
    --bs-pagination-color: #4361ee;
    --bs-pagination-hover-color: #3a0ca3;
    --bs-pagination-focus-color: #3a0ca3;
    --bs-pagination-active-bg: #4361ee;
    --bs-pagination-active-border-color: #4361ee;
}

/* Form Controls */
.form-select-lg, .form-control-lg {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

.form-select-lg:focus, .form-control-lg:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
}

/* تحسين مظهر الـ Modals */
.modal.fade .modal-dialog {
    transform: translate(0, -50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
}

/* إصلاح مشكلة الـ backdrop */
.modal-backdrop.show {
    opacity: 0.5;
}

/* Animation Delays */
.animate__delay-0\.2s { animation-delay: 0.2s; }
.animate__delay-0\.4s { animation-delay: 0.4s; }
.animate__delay-0\.6s { animation-delay: 0.6s; }
.animate__delay-0\.8s { animation-delay: 0.8s; }
.animate__delay-1s { animation-delay: 1s; }

/* Card Hover Effects */
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل أدوات التلميح
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // تفعيل DataTable
    $('#cashSalesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        columnDefs: [
            { orderable: false, targets: [9] } // تعطيل الترتيب لعمود الإجراءات
        ]
    });
    
    // النقر على الصف لعرض التفاصيل (باستثناء الأزرار)
    $('#cashSalesTable tbody tr').click(function(e) {
        if (!$(e.target).is('a, button, i') && !$(e.target).closest('a, button').length) {
            window.location = $(this).find('a.btn-outline-info').attr('href');
        }
    });
    
    // إصلاح مشكلة ظهور الـ Modals
    var deleteModals = document.querySelectorAll('.modal');
    deleteModals.forEach(function(modal) {
        // إعادة تهيئة الـ modal إذا كان هناك مشكلة
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var modal = this;
            
            // تأكد من أن الـ modal في المقدمة
            modal.style.zIndex = '1060';
            modal.style.display = 'block';
            modal.classList.add('show');
            
            // إضافة backdrop إذا لم يكن موجوداً
            if (!document.querySelector('.modal-backdrop')) {
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.style.zIndex = '1055';
                document.body.appendChild(backdrop);
            }
        });
        
        // عند إخفاء الـ modal
        modal.addEventListener('hidden.bs.modal', function () {
            // إزالة backdrop إذا كان موجوداً
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
    });
    
    // تأثيرات للصفوف عند الظهور
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__fadeIn');
            }
        });
    }, observerOptions);
    
    // مراقبة الصفوف في الجدول
    document.querySelectorAll('#cashSalesTable tbody tr').forEach(row => {
        observer.observe(row);
    });
    
    // تحديث تلميح الأزرار العائمة
    window.addEventListener('scroll', function() {
        const floatingBtns = document.querySelectorAll('.floating-btn');
        floatingBtns.forEach(btn => {
            if (window.scrollY > 100) {
                btn.style.opacity = '1';
            } else {
                btn.style.opacity = '0.9';
            }
        });
    });
    
    // إضافة تأثير للـ Modals عند الفتح
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);
            if (modal) {
                // إضافة تأثير ظهور
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.opacity = '1';
                    modal.style.transition = 'opacity 0.3s ease';
                }, 10);
            }
        });
    });
});
</script>
@endpush