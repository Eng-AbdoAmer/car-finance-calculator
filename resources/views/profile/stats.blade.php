{{-- @extends('layouts.app')

@section('title', 'إحصائيات الحساب')

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
                            <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                        </span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                        <span class="text-gradient-primary">إحصائيات</span> الحساب
                    </h1>
                </div>
                
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                    نظرة شاملة على نشاطك وأدائك في المنصة
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
<section class="stats-section py-5">
    <div class="container">
        <!-- إحصائيات تفصيلية -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="section-header mb-4">
                    <h3 class="fw-bold mb-3 text-center text-white">
                        <i class="fas fa-chart-pie me-2 text-white"></i>الإحصائيات التفصيلية
                    </h3>
                </div>
                
                <div class="row g-4">
                    <!-- إجمالي العمليات -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="icon-wrapper bg-gradient-primary rounded-circle p-3">
                                            <i class="fas fa-calculator fa-2x text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">إجمالي العمليات</h6>
                                        <h3 class="mb-0 text-primary">{{ $stats['total_calculations'] }}</h3>
                                        <small class="text-muted">عملية حسابية</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- العمليات الشهرية -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="icon-wrapper bg-gradient-success rounded-circle p-3">
                                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">عمليات هذا الشهر</h6>
                                        <h3 class="mb-0 text-success">{{ $stats['monthly_calculations'] }}</h3>
                                        <small class="text-muted">عملية هذا الشهر</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- إجمالي المبالغ -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="icon-wrapper bg-gradient-info rounded-circle p-3">
                                            <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="text-muted mb-1">إجمالي المبالغ</h6>
                                        <h3 class="mb-0 text-info">{{ number_format($stats['total_amount'], 2) }} ر.س</h3>
                                        <small class="text-muted">ريال سعودي</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- معلومات الحساب -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h5 class="mb-0 text-center">
                            <i class="fas fa-info-circle me-2"></i>معلومات الحساب
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">اسم المستخدم</h6>
                                            <p class="mb-0 text-white">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">البريد الإلكتروني</h6>
                                            <p class="mb-0 text-white">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">تاريخ التسجيل</h6>
                                            <p class="mb-0 text-white">{{ $user->created_at->format('Y/m/d') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">عمر الحساب</h6>
                                            <p class="mb-0 text-white">{{ $stats['account_age'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-3">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">آخر دخول</h6>
                                            <p class="mb-0 text-white">{{ $stats['last_login'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="info-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-wrapper bg-secondary bg-opacity-10 text-secondary rounded-circle p-2 me-3">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-white">نوع الحساب</h6>
                                            <p class="mb-0">
                                                @if($user->isAdmin())
                                                    <span class="badge bg-gradient-primary">مدير النظام</span>
                                                @else
                                                    <span class="badge bg-gradient-success">مستخدم</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
                                <h3 class="mb-0 text-white">
                                    <i class="fas fa-history me-2 text-primary"></i>
                                    آخر العمليات
                                </h3>
                                <p class="text-muted mb-0">آخر العمليات الحسابية التي قمت بها</p>
                            </div>
                            <div>
                                <a href="{{ route('profile.index') }}" class="btn btn-primary me-2">
                                    <i class="fas fa-arrow-right me-2"></i>العودة للبروفيل
                                </a>
                                <a href="{{ route('finance.calculations.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-2"></i>عملية جديدة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- جدول العمليات الأخيرة -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>الوصف</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">المبلغ</th>
                                        <th class="text-center">التاريخ</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['recent_calculations'] as $calculation)
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="text-center fw-bold">{{ $calculation->id }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $calculation->title ?? 'عملية حسابية' }}</h6>
                                            <small class="text-muted">{{ Str::limit($calculation->description, 40) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                {{ $calculation->type ?? 'عام' }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($calculation->amount, 2) }} ر.س
                                        </td>
                                        <td class="text-center">
                                            <span class="">{{ $calculation->created_at->format('Y-m-d') }}</span><br>
                                            <small class="text-muted">{{ $calculation->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusColors = [
                                                    'pending' => ['bg' => 'bg-secondary bg-opacity-10', 'text' => 'text-secondary', 'icon' => 'fas fa-clock'],
                                                    'completed' => ['bg' => 'bg-success bg-opacity-10', 'text' => 'text-success', 'icon' => 'fas fa-check-circle'],
                                                    'failed' => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger', 'icon' => 'fas fa-times-circle'],
                                                ];
                                                $status = $calculation->status ?? 'completed';
                                            @endphp
                                            <span class="badge {{ $statusColors[$status]['bg'] }} {{ $statusColors[$status]['text'] }} border {{ $statusColors[$status]['text'] }}">
                                                <i class="{{ $statusColors[$status]['icon'] }} me-1"></i>
                                                {{ $status == 'completed' ? 'مكتمل' : ($status == 'pending' ? 'قيد الانتظار' : 'فاشل') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('finance.calculations.show', $calculation->id) }}" 
                                                   class="btn btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('finance.calculations.edit', $calculation->id) }}" 
                                                   class="btn btn-outline-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <h5>لا توجد عمليات بعد</h5>
                                                <p class="mb-3">ابدأ بإجراء عملياتك الحسابية الأولى</p>
                                                <a href="{{ route('finance.calculations.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus-circle me-2"></i>إنشاء عملية جديدة
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($stats['recent_calculations']->count() > 0)
                        <div class="card-footer border-0 bg-transparent">
                            <div class="text-center">
                                <a href="{{ route('finance.calculations.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>عرض جميع العمليات
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Buttons -->
<div class="floating-buttons">
    <a href="{{ route('profile.index') }}" class="floating-btn profile-btn animate__animated animate__bounceIn animate__delay-1s">
        <i class="fas fa-user"></i>
        <span class="tooltip">البروفيل</span>
    </a>
</div>
@endsection

@push('styles')
<style>
/* نفس الـ styles السابقة مع تعديلات بسيطة */
.hero-section {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    min-height: 40vh;
}

.text-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.info-item {
    padding: 1rem;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.profile-btn {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
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
    
    document.querySelectorAll('tbody tr').forEach(row => {
        observer.observe(row);
    });
});
</script>
@endpush --}}