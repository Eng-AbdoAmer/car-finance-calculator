@extends('layouts.admin')

@section('title', 'لوحة التحكم - الرئيسية')

@section('breadcrumb')
<li class="breadcrumb-item active">الرئيسية</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="mb-2">مرحباً بعودتك، {{ auth()->user()->name }}! 👋</h3>
                        <p class="text-muted">هذه نظرة عامة على أداء النظام وإحصائيات المبيعات والتمويل.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="date-info">
                            <h5 class="text-primary mb-1" id="current-date"></h5>
                            <p class="text-muted mb-0" id="current-time"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الإحصائيات السريعة -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="stat-number">{{ number_format($stats['total_sales']) }}</h2>
                    <h6 class="stat-label">إجمالي المبيعات</h6>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span class="text-success">12%</span> عن الشهر الماضي
                    </div>
                </div>
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="stat-number">{{ number_format($stats['total_financing']) }}</h2>
                    <h6 class="stat-label">طلبات التمويل</h6>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span class="text-success">8%</span> عن الشهر الماضي
                    </div>
                </div>
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="stat-number">{{ number_format($stats['total_revenue'], 2) }} <small>ر.س</small></h2>
                    <h6 class="stat-label">إجمالي الإيرادات</h6>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span class="text-success">15%</span> عن الشهر الماضي
                    </div>
                </div>
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ffd166 0%, #ff9e00 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="stat-number">{{ number_format($stats['pending_requests']) }}</h2>
                    <h6 class="stat-label">طلبات قيد الانتظار</h6>
                    <div class="stat-change">
                        <i class="fas fa-arrow-down text-danger"></i>
                        <span class="text-danger">3%</span> عن الأسبوع الماضي
                    </div>
                </div>
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- الرسوم البيانية والإحصائيات -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>معدل المبيعات الشهري
                </h5>
            </div>
            <div class="card-body">
                <div id="salesChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>نسبة أنواع السيارات
                </h5>
            </div>
            <div class="card-body">
                <div id="carTypesChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- أحدث العمليات -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>أحدث المبيعات النقدية
                </h5>
                <a href="{{ route('admin.cash-sales.index') }}" class="btn btn-sm btn-primary">عرض الكل</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>العميل</th>
                                <th>السيارة</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_sales as $sale)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">{{ $sale->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $sale->carBrand->name ?? '' }} - {{ $sale->carModel->model_year ?? '' }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($sale->car_price, 2) }} ر.س</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'partial_paid' => 'info',
                                            'fully_paid' => 'success',
                                            'delivered' => 'primary',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$sale->payment_status] ?? 'secondary' }}">
                                        {{ $sale->payment_status_text }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>أحدث طلبات التمويل
                </h5>
                <a href="{{ route('admin.financing-requests.index') }}" class="btn btn-sm btn-primary">عرض الكل</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>العميل</th>
                                <th>السيارة</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_financing as $request)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $request->user->name ?? 'غير محدد' }}</h6>
                                            <small class="text-muted">{{ $request->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $request->brand->name ?? '' }} - {{ $request->model->model_year ?? '' }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($request->car_price, 2) }} ر.س</span>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ملخص الأداء -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>ملخص أداء النظام
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded">
                            <h2 class="text-primary">{{ $stats['conversion_rate'] }}%</h2>
                            <p class="mb-0">معدل التحويل</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded">
                            <h2 class="text-success">{{ $stats['avg_sale_value'] }} ر.س</h2>
                            <p class="mb-0">متوسط قيمة البيع</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded">
                            <h2 class="text-info">{{ $stats['active_users'] }}</h2>
                            <p class="mb-0">المستخدمين النشطين</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded">
                            <h2 class="text-warning">{{ $stats['monthly_growth'] }}%</h2>
                            <p class="mb-0">النمو الشهري</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// التاريخ والوقت الحالي
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const date = now.toLocaleDateString('ar-SA', options);
    const time = now.toLocaleTimeString('ar-SA');
    
    document.getElementById('current-date').textContent = date;
    document.getElementById('current-time').textContent = time;
}

updateDateTime();
setInterval(updateDateTime, 60000);

// مخطط المبيعات الشهرية
var salesChart = new ApexCharts(document.querySelector("#salesChart"), {
    series: [{
        name: 'المبيعات النقدية',
        data: [31, 40, 28, 51, 42, 82, 56]
    }, {
        name: 'طلبات التمويل',
        data: [11, 32, 45, 32, 34, 52, 41]
    }],
    chart: {
        height: 300,
        type: 'area',
        toolbar: {
            show: true
        }
    },
    colors: ['#4361ee', '#06d6a0'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        categories: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو'],
        labels: {
            style: {
                fontSize: '12px'
            }
        }
    },
    yaxis: {
        labels: {
            formatter: function(value) {
                return value + ' عملية';
            }
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'left',
        fontSize: '14px',
        fontFamily: 'Cairo'
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy'
        }
    }
});

salesChart.render();

// مخطط أنواع السيارات
var carTypesChart = new ApexCharts(document.querySelector("#carTypesChart"), {
    series: [44, 55, 13, 43, 22],
    chart: {
        type: 'pie',
        height: 300
    },
    labels: ['تويوتا', 'لكزس', 'هيونداي', 'كيا', 'أخرى'],
    colors: ['#4361ee', '#06d6a0', '#118ab2', '#ffd166', '#ef476f'],
    legend: {
        position: 'bottom',
        fontSize: '14px',
        fontFamily: 'Cairo'
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
});

carTypesChart.render();
</script>
@endpush