@extends('layouts.admin')

@section('title', 'إدارة فئات السيارات')

@section('page-title', 'إدارة فئات السيارات')

@section('breadcrumb')
<li class="breadcrumb-item active">فئات السيارات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-tags me-2"></i>قائمة فئات السيارات
                    </h5>
                    <a href="{{ route('admin.car-categories.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>إضافة فئة جديدة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('admin.car-categories.index') }}" class="row g-3">
                            <div class="col-md-8">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="ابحث باسم الفئة..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    @if(request('search'))
                                    <a href="{{ route('admin.car-categories.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> إلغاء
                                    </a>
                                    @endif
                                </div>
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
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي الفئات</h6>
                                </div>
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول الفئات -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>اسم الفئة</th>
                                <th>عدد السيارات</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + (($categories->currentPage() - 1) * $categories->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-badge me-2">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $category->name }}</strong>
                                            <small class="text-muted">ID: {{ $category->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->cars_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div>{{ $category->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $category->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.car-categories.show', $category->id) }}" 
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.car-categories.edit', $category->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.car-categories.destroy', $category->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
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
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد فئات</h5>
                                        <p class="text-muted">لم يتم إضافة أي فئات سيارات بعد.</p>
                                        <a href="{{ route('admin.car-categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>إضافة فئة جديدة
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                @if($categories->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            @php
                                $current = $categories->currentPage();
                                $last = $categories->lastPage();
                                $start = max($current - 1, 1);
                                $end = min($current + 1, $last);
                            @endphp
                            
                            {{-- Previous Page Link --}}
                            @if ($categories->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->url(1) }}">1</a>
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
                                        <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
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
                                    <a class="page-link" href="{{ $categories->url($last) }}">{{ $last }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($categories->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
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

.category-badge {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4361ee;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.btn-group .btn {
    border-radius: 5px;
    margin: 0 2px;
}

/* تخصيص الترقيم */
.pagination .page-link {
    border-radius: 5px;
    margin: 0 2px;
    min-width: 35px;
    text-align: center;
    color: #4361ee;
}

.pagination .page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
}

/* على الشاشات الصغيرة */
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
    
    .category-badge {
        width: 30px;
        height: 30px;
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