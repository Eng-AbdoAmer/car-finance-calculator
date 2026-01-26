@extends('layouts.admin')

@section('title', 'جميع الحسابات')

@section('page-title', 'جميع الحسابات')

@section('breadcrumb')
<li class="breadcrumb-item active">جميع الحسابات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- فلتر البحث -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.calculations.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="phone" class="form-label">البحث برقم الهاتف</label>
                        <input type="text" 
                               name="phone" 
                               id="phone"
                               class="form-control" 
                               placeholder="ابحث برقم الهاتف..."
                               value="{{ request('phone') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">فلتر حسب المستخدم</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">جميع المستخدمين</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['phone', 'user_id', 'cash_status', 'financing_status', 'calculation_status']))
                        <a href="{{ route('admin.calculations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>إلغاء البحث
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="stat-number">{{ $stats['total_cash_sales'] }}</h2>
                            <h6 class="stat-label">المبيعات النقدية</h6>
                            <small class="stat-change">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                كاش وتقسيط
                            </small>
                        </div>
                        <i class="fas fa-cash-register"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="stat-number">{{ $stats['total_financing'] }}</h2>
                            <h6 class="stat-label">طلبات التمويل</h6>
                            <small class="stat-change">
                                <i class="fas fa-file-invoice-dollar me-1"></i>
                                البنوك المختلفة
                            </small>
                        </div>
                        <i class="fas fa-university"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="stat-number">{{ $stats['total_calculations'] }}</h2>
                            <h6 class="stat-label">حسابات الراجحي</h6>
                            <small class="stat-change">
                                <i class="fas fa-calculator me-1"></i>
                                تمويل الراجحي
                            </small>
                        </div>
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- تبويبات لأنواع الحسابات -->
        <ul class="nav nav-tabs mb-4" id="calculationsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="cash-tab" data-bs-toggle="tab" data-bs-target="#cash" type="button" role="tab">
                    <i class="fas fa-cash-register me-2"></i>المبيعات النقدية
                    <span class="badge bg-primary ms-2">{{ $cashSales->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="financing-tab" data-bs-toggle="tab" data-bs-target="#financing" type="button" role="tab">
                    <i class="fas fa-file-invoice-dollar me-2"></i>طلبات التمويل
                    <span class="badge bg-success ms-2">{{ $financingRequests->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="calculations-tab" data-bs-toggle="tab" data-bs-target="#calculations" type="button" role="tab">
                    <i class="fas fa-calculator me-2"></i>حسابات الراجحي
                    <span class="badge bg-info ms-2">{{ $financeCalculations->total() }}</span>
                </button>
            </li>
        </ul>

        <!-- محتوى التبويبات -->
        <div class="tab-content" id="calculationsTabContent">
            <!-- تبويب المبيعات النقدية -->
            <div class="tab-pane fade show active" id="cash" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-cash-register me-2"></i>المبيعات النقدية
                        </h5>
                        <form method="GET" action="{{ route('admin.calculations.index') }}" class="d-inline">
                            <input type="hidden" name="phone" value="{{ request('phone') }}">
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                            <select name="cash_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('cash_status') == 'all' ? 'selected' : '' }}>جميع الحالات</option>
                                <option value="pending" {{ request('cash_status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="partial_paid" {{ request('cash_status') == 'partial_paid' ? 'selected' : '' }}>مدفوع جزئياً</option>
                                <option value="fully_paid" {{ request('cash_status') == 'fully_paid' ? 'selected' : '' }}>مدفوع بالكامل</option>
                                <option value="delivered" {{ request('cash_status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                <option value="cancelled" {{ request('cash_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                <option value="on_hold" {{ request('cash_status') == 'on_hold' ? 'selected' : '' }}>معلق</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        @if($cashSales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>العميل</th>
                                        <th>الهاتف</th>
                                        <th>السيارة</th>
                                        <th>المبلغ</th>
                                        <th>المدفوع</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashSales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration + (($cashSales->currentPage() - 1) * $cashSales->perPage()) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $sale->user->name ?? 'غير محدد' }}</h6>
                                                    <small class="text-muted">{{ $sale->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $sale->phone }}</td>
                                        <td>
                                            {{ $sale->carBrand->name ?? '' }} - {{ $sale->carModel->model_year ?? '' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ number_format($sale->car_price, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($sale->paid_amount, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'partial_paid' => 'info',
                                                    'fully_paid' => 'success',
                                                    'delivered' => 'primary',
                                                    'cancelled' => 'danger',
                                                    'refunded' => 'secondary',
                                                    'on_hold' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$sale->payment_status] ?? 'secondary' }}">
                                                @switch($sale->payment_status)
                                                    @case('pending') قيد الانتظار @break
                                                    @case('partial_paid') مدفوع جزئياً @break
                                                    @case('fully_paid') مدفوع بالكامل @break
                                                    @case('delivered') تم التسليم @break
                                                    @case('cancelled') ملغي @break
                                                    @case('refunded') تم الاسترجاع @break
                                                    @case('on_hold') معلق @break
                                                    @default {{ $sale->payment_status }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $sale->created_at->format('Y/m/d') }}</div>
                                            <small class="text-muted">{{ $sale->created_at->format('h:i A') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- الترقيم -->
                        @if($cashSales->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $cashSales->appends(request()->except('cash_sales_page'))->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                        @else
                        <div class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
                                <h5>لا يوجد مبيعات نقدية</h5>
                                <p class="text-muted">لم يتم تسجيل أي مبيعات نقدية بعد.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- تبويب طلبات التمويل -->
            <div class="tab-pane fade" id="financing" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-file-invoice-dollar me-2"></i>طلبات التمويل (البنوك الأخرى)
                        </h5>
                        <form method="GET" action="{{ route('admin.calculations.index') }}" class="d-inline">
                            <input type="hidden" name="phone" value="{{ request('phone') }}">
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                            <select name="financing_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('financing_status') == 'all' ? 'selected' : '' }}>جميع الحالات</option>
                                <option value="pending" {{ request('financing_status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="sold" {{ request('financing_status') == 'sold' ? 'selected' : '' }}>تم البيع</option>
                                <option value="not_sold" {{ request('financing_status') == 'not_sold' ? 'selected' : '' }}>لم يتم البيع</option>
                                <option value="follow_up" {{ request('financing_status') == 'follow_up' ? 'selected' : '' }}>متابعة</option>
                                <option value="cancelled" {{ request('financing_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        @if($financingRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>العميل</th>
                                        <th>الهاتف</th>
                                        <th>البنك</th>
                                        <th>السيارة</th>
                                        <th>المبلغ</th>
                                        <th>القسط الشهري</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financingRequests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration + (($financingRequests->currentPage() - 1) * $financingRequests->perPage()) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $request->user->name ?? 'غير محدد' }}</h6>
                                                    <small class="text-muted">{{ $request->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $request->phone }}</td>
                                        <td>{{ $request->bank->name ?? 'غير محدد' }}</td>
                                        <td>
                                            {{ $request->brand->name ?? '' }} - {{ $request->model->model_year ?? '' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ number_format($request->car_price, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ number_format($request->monthly_installment, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'sold' => 'success',
                                                    'not_sold' => 'danger',
                                                    'follow_up' => 'info',
                                                    'cancelled' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }}">
                                                {{ $request->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $request->created_at->format('Y/m/d') }}</div>
                                            <small class="text-muted">{{ $request->created_at->format('h:i A') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- الترقيم -->
                        @if($financingRequests->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $financingRequests->appends(request()->except('financing_requests_page'))->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                        @else
                        <div class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                <h5>لا يوجد طلبات تمويل</h5>
                                <p class="text-muted">لم يتم تسجيل أي طلبات تمويل بعد.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- تبويب حسابات الراجحي -->
            <div class="tab-pane fade" id="calculations" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-calculator me-2"></i>حسابات الراجحي
                        </h5>
                        <form method="GET" action="{{ route('admin.calculations.index') }}" class="d-inline">
                            <input type="hidden" name="phone" value="{{ request('phone') }}">
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                            <select name="calculation_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ request('calculation_status') == 'all' ? 'selected' : '' }}>جميع الحالات</option>
                                <option value="pending" {{ request('calculation_status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="sold" {{ request('calculation_status') == 'sold' ? 'selected' : '' }}>تم البيع</option>
                                <option value="not_sold" {{ request('calculation_status') == 'not_sold' ? 'selected' : '' }}>لم يتم البيع</option>
                                <option value="follow_up" {{ request('calculation_status') == 'follow_up' ? 'selected' : '' }}>متابعة</option>
                                <option value="cancelled" {{ request('calculation_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        @if($financeCalculations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>العميل</th>
                                        <th>الهاتف</th>
                                        <th>الموديل</th>
                                        <th>المبلغ</th>
                                        <th>الدفعة</th>
                                        <th>القسط الشهري</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financeCalculations as $calculation)
                                    <tr>
                                        <td>{{ $loop->iteration + (($financeCalculations->currentPage() - 1) * $financeCalculations->perPage()) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $calculation->user->name ?? 'غير محدد' }}</h6>
                                                    <small class="text-muted">{{ $calculation->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $calculation->phone }}</td>
                                        <td>
                                            {{ $calculation->car_brand }} - {{ $calculation->carModel->model_year ?? '' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ number_format($calculation->car_price, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-warning">{{ number_format($calculation->down_payment_amount, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ number_format($calculation->monthly_installment_with_insurance, 2) }} ر.س</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'sold' => 'success',
                                                    'not_sold' => 'danger',
                                                    'follow_up' => 'info',
                                                    'cancelled' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$calculation->status] ?? 'secondary' }}">
                                                {{ $calculation->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $calculation->created_at->format('Y/m/d') }}</div>
                                            <small class="text-muted">{{ $calculation->created_at->format('h:i A') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- الترقيم -->
                        @if($financeCalculations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $financeCalculations->appends(request()->except('finance_calculations_page'))->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                        @else
                        <div class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                                <h5>لا يوجد حسابات راجحي</h5>
                                <p class="text-muted">لم يتم تسجيل أي حسابات راجحي بعد.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- زر التصدير -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>تصدير
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('admin.calculations.export', ['type' => 'cash_sales', 'phone' => request('phone'), 'status' => request('cash_status')]) }}">
                    <i class="fas fa-cash-register me-2"></i>تصدير المبيعات النقدية
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.calculations.export', ['type' => 'financing', 'phone' => request('phone'), 'status' => request('financing_status')]) }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i>تصدير طلبات التمويل
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.calculations.export', ['type' => 'calculations', 'phone' => request('phone'), 'status' => request('calculation_status')]) }}">
                    <i class="fas fa-calculator me-2"></i>تصدير حسابات الراجحي
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('styles')
<style>
.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-state i {
    opacity: 0.5;
}

.stat-card {
    border-radius: 10px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(45deg);
}

.stat-card i {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat-change {
    font-size: 0.85rem;
    opacity: 0.9;
}

/* تنسيق التبويبات */
.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    color: #4361ee;
    border: none;
}

.nav-tabs .nav-link.active {
    color: #4361ee;
    background-color: transparent;
    border: none;
    border-bottom: 3px solid #4361ee;
}

/* تنسيق الجداول */
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.table-hover tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

/* تنسيق الترقيم */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 5px;
    margin: 0 3px;
    border: 1px solid #dee2e6;
    color: #4361ee;
}

.page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
    color: white;
}

/* على الشاشات الصغيرة */
@media (max-width: 768px) {
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .table td, .table th {
        padding: 0.5rem;
    }
    
    .avatar-sm {
        width: 30px;
        height: 30px;
    }
}

/* تحسين عرض التبويبات على الهواتف */
@media (max-width: 576px) {
    .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }
    
    .nav-tabs .nav-item {
        flex-shrink: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
// حفظ التبويب النشط في localStorage
document.addEventListener('DOMContentLoaded', function() {
    // استعادة التبويب النشط من localStorage
    const activeTab = localStorage.getItem('activeCalculationTab');
    if (activeTab) {
        const tabTrigger = document.querySelector(`[data-bs-target="${activeTab}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
    
    // حفظ التبويب عند التغيير
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEls.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (event) {
            const activeTab = event.target.getAttribute('data-bs-target');
            localStorage.setItem('activeCalculationTab', activeTab);
        });
    });
    
    // تفعيل البحث عند الضغط على Enter
    document.getElementById('phone')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
    
    // تحديث الإحصائيات
    function updateStats() {
        fetch('{{ route("admin.calculations.stats") }}')
            .then(response => response.json())
            .then(data => {
                // يمكنك تحديث عناصر HTML بالإحصائيات هنا إذا أردت
                console.log('Updated stats:', data);
            })
            .catch(error => console.error('Error fetching stats:', error));
    }
    
    // تحديث الإحصائيات كل دقيقة
    // setInterval(updateStats, 60000);
});
</script>
@endpush