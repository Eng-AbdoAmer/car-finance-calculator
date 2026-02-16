@extends('layouts.admin')

@section('title', 'إدارة براندات السيارات')

@section('page-title', 'إدارة براندات السيارات')

@section('breadcrumb')
<li class="breadcrumb-item active">براندات السيارات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-car me-2"></i>قائمة براندات السيارات
                    </h5>
                    <a href="{{ route('admin.car-brands.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>إضافة براند جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.car-brands.index') }}" class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="ابحث باسم البراند..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search'))
                                    <a href="{{ route('admin.car-brands.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="text-muted">
                                    {{ $brands->total() }} براند
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
                                    <h6 class="stat-label">إجمالي البراندات</h6>
                                </div>
                                 <i class="fas fa-car-side"></i>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="col-xl-6 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-label">براندات بها موديلات</h6>
                                </div>
                                <i class="fas fa-car-side"></i>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- جدول البراندات -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>اسم البراند</th>
                                <th>عدد الموديلات</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ التحديث</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration + (($brands->currentPage() - 1) * $brands->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="brand-icon me-3">
                                            <div class="icon-wrapper bg-light rounded-circle p-2">
                                                <i class="fas fa-car text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $brand->name }}</h6>
                                            {{-- <small class="text-muted">ID: {{ $brand->id }}</small> --}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                         موديل
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $brand->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $brand->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($brand->updated_at->eq($brand->created_at))
                                        <span class="text-muted">لم يتم التعديل</span>
                                    @else
                                        <div>{{ $brand->updated_at->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $brand->updated_at->format('h:i A') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.car-brands.show', $brand->id) }}" 
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.car-brands.edit', $brand->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.car-brands.destroy', $brand->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا البراند؟')">
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
                                <td colspan="6" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد براندات</h5>
                                        <p class="text-muted">لم يتم إضافة أي براندات بعد.</p>
                                        <a href="{{ route('admin.car-brands.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>إضافة براند جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم المصغر الذكي -->
                @if($brands->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            @php
                                $current = $brands->currentPage();
                                $last = $brands->lastPage();
                                $start = max($current - 1, 1);
                                $end = min($current + 1, $last);
                            @endphp
                            
                            {{-- Previous Page Link --}}
                            @if ($brands->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $brands->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $brands->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Pagination Elements --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $current)
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $brands->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $last)
                                @if($end < $last - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $brands->url($last) }}">{{ $last }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($brands->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $brands->nextPageUrl() }}" aria-label="Next">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
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

.brand-icon .icon-wrapper {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* تخصيص الترقيم المصغر */
.pagination.pagination-sm {
    font-size: 0.75rem;
    margin-bottom: 0;
}

.pagination.pagination-sm .page-link {
    border-radius: 3px;
    margin: 0 1px;
    border: 1px solid #dee2e6;
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
    min-width: 28px;
    text-align: center;
    color: #4361ee;
    line-height: 1.2;
}

.pagination.pagination-sm .page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
    color: white;
}

.pagination.pagination-sm .page-item.disabled .page-link {
    color: #6c757d;
}

.pagination.pagination-sm .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #3a0ca3;
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
    
    .pagination.pagination-sm .page-link {
        padding: 0.15rem 0.3rem;
        font-size: 0.7rem;
        min-width: 24px;
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