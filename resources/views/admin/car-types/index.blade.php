@extends('layouts.admin')

@section('title', 'إدارة أنواع السيارات')

@section('page-title', 'إدارة أنواع السيارات')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">براندات السيارات</a></li>
<li class="breadcrumb-item active">أنواع السيارات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-list me-2"></i>قائمة أنواع السيارات
                    </h5>
                    <a href="{{ route('admin.car-types.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>إضافة نوع جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.car-types.index') }}" class="row g-3">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="ابحث باسم النوع أو البراند..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search'))
                                    <a href="{{ route('admin.car-types.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="brand_id" class="form-select">
                                    <option value="">جميع البراندات</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="badge bg-success fs-6">
                                    {{ $types->total() }} نوع
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي الأنواع</h6>
                                </div>
                                <i class="fas fa-list-alt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #7209b7 0%, #4361ee 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['with_trims'] }}</h2>
                                    <h6 class="stat-label">أنواع تحتوي على طرازات</h6>
                                </div>
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] - $stats['with_trims'] }}</h2>
                                    <h6 class="stat-label">أنواع بدون طرازات</h6>
                                </div>
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول الأنواع -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>اسم النوع</th>
                                <th>البراند</th>
                                <th>عدد الطرازات</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ التحديث</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $type)
                            <tr>
                                <td>{{ $loop->iteration + (($types->currentPage() - 1) * $types->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="type-icon me-3">
                                            <div class="icon-wrapper bg-light rounded-circle p-2">
                                                <i class="fas fa-car text-success"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $type->name }}</h6>
                                            <small class="text-muted">ID: {{ $type->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($type->brand)
                                        <a href="{{ route('admin.car-brands.show', $type->brand->id) }}" 
                                           class="badge bg-primary text-decoration-none">
                                            <i class="fas fa-tag me-1"></i>{{ $type->brand->name }}
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($type->trims_count > 0)
                                        <a href="#" class="badge bg-info text-decoration-none">
                                            <i class="fas fa-layer-group me-1"></i>{{ $type->trims_count }} طراز
                                        </a>
                                    @else
                                        <span class="badge bg-warning">لا يوجد طرازات</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $type->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $type->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($type->updated_at->eq($type->created_at))
                                        <span class="text-muted">لم يتم التعديل</span>
                                    @else
                                        <div>{{ $type->updated_at->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $type->updated_at->format('h:i A') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.car-types.show', $type->id) }}" 
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.car-types.edit', $type->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.car-types.destroy', $type->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
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
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد أنواع</h5>
                                        <p class="text-muted">لم يتم إضافة أي أنواع سيارات بعد.</p>
                                        <a href="{{ route('admin.car-types.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus me-1"></i>إضافة نوع جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم المصغر الذكي -->
                @if($types->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        عرض {{ $types->firstItem() }} إلى {{ $types->lastItem() }} من أصل {{ $types->total() }} نوع
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            @php
                                $current = $types->currentPage();
                                $last = $types->lastPage();
                                $start = max($current - 1, 1);
                                $end = min($current + 1, $last);
                            @endphp
                            
                            {{-- Previous Page Link --}}
                            @if ($types->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $types->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $types->url(1) }}">1</a>
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
                                        <a class="page-link" href="{{ $types->url($i) }}">{{ $i }}</a>
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
                                    <a class="page-link" href="{{ $types->url($last) }}">{{ $last }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($types->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $types->nextPageUrl() }}" aria-label="Next">
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
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
    border-top: 1px solid #dee2e6;
}

.btn-group .btn {
    border-radius: 5px;
    margin: 0 2px;
}

.type-icon .icon-wrapper {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
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
    color: #06d6a0;
    line-height: 1.2;
}

.pagination.pagination-sm .page-item.active .page-link {
    background-color: #06d6a0;
    border-color: #06d6a0;
    color: white;
}

.pagination.pagination-sm .page-item.disabled .page-link {
    color: #6c757d;
}

.pagination.pagination-sm .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #1b9aaa;
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
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}

/* تخصيص البادجات */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.85em;
    font-weight: 500;
}

.badge.bg-success {
    background-color: #06d6a0 !important;
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
    
    // إرسال الفورم عند تغيير البراند
    document.querySelector('select[name="brand_id"]').addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endpush