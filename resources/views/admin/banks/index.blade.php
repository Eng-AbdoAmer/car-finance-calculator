@extends('layouts.admin')

@section('title', 'إدارة البنوك')

@section('page-title', 'إدارة البنوك')

@section('breadcrumb')
<li class="breadcrumb-item active">البنوك</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-university me-2"></i>قائمة البنوك
                    </h5>
                    <a href="{{ route('admin.banks.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>إضافة بنك جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.banks.index') }}" class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="ابحث باسم البنك..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search'))
                                    <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="text-muted">
                                    {{ $banks->total() }} بنك
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي البنوك</h6>
                                </div>
                                 <i class="fas fa-university"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول البنوك -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>اسم البنك</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ التحديث</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banks as $bank)
                            <tr>
                                <td>{{ $loop->iteration + (($banks->currentPage() - 1) * $banks->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bank-icon me-3">
                                            <div class="icon-wrapper bg-light rounded-circle p-2">
                                                <i class="fas fa-university text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $bank->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $bank->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $bank->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($bank->updated_at->eq($bank->created_at))
                                        <span class="text-muted">لم يتم التعديل</span>
                                    @else
                                        <div>{{ $bank->updated_at->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $bank->updated_at->format('h:i A') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.banks.show', $bank->id) }}" 
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.banks.edit', $bank->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banks.destroy', $bank->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا البنك؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-university fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد بنوك</h5>
                                        <p class="text-muted">لم يتم إضافة أي بنوك بعد.</p>
                                        <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>إضافة بنك جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                @if($banks->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $banks->links() }}
                </div>
                @endif
            </div>
        </div>
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

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.btn-group .btn {
    border-radius: 5px;
    margin: 0 2px;
}

.bank-icon .icon-wrapper {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* تخصيص الترقيم */
.pagination .page-link {
    border-radius: 5px;
    margin: 0 3px;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
}

/* تحسين الشكل على الأجهزة الصغيرة */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-group {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .table td, .table th {
        padding: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// تفعيل أدوات التلميح
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush