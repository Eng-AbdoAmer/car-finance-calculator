@extends('layouts.admin')

@section('title', 'إدارة سنوات موديلات السيارات')

@section('page-title', 'إدارة سنوات الموديلات')

@section('breadcrumb')
<li class="breadcrumb-item active">سنوات الموديلات</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-calendar-alt me-2"></i>قائمة سنوات موديلات السيارات
                    </h5>
                    <div>
                        <button type="button" class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                            <i class="fas fa-layer-group me-1"></i>إضافة مجموعة
                        </button>
                        <form action="{{ route('admin.car-models.add-next-year') }}" method="POST" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-light">
                                <i class="fas fa-plus-circle me-1"></i>إضافة سنة جديدة
                            </button>
                        </form>
                        <a href="{{ route('admin.car-models.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة سنة يدوياً
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.car-models.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <select name="period" class="form-select" onchange="this.form.submit()">
                                    <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>جميع السنوات</option>
                                    <option value="recent" {{ request('period') == 'recent' ? 'selected' : '' }}>السنوات الحديثة (آخر 5 سنوات)</option>
                                    <option value="old" {{ request('period') == 'old' ? 'selected' : '' }}>السنوات القديمة</option>
                                    <option value="current_year" {{ request('period') == 'current_year' ? 'selected' : '' }}>السنة الحالية فقط</option>
                                    <option value="next_year" {{ request('period') == 'next_year' ? 'selected' : '' }}>السنة القادمة فقط</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="number" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="ابحث بالسنة..."
                                           value="{{ request('search') }}"
                                           min="1900"
                                           max="{{ $currentYear + 10 }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->hasAny(['period', 'search']))
                                    <a href="{{ route('admin.car-models.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <span class="text-muted">
                                    {{ $models->total() }} سنة
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي السنوات</h6>
                                </div>
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['current_year'] }}</h2>
                                    <h6 class="stat-label">السنة الحالية ({{ $currentYear }})</h6>
                                </div>
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['next_year'] }}</h2>
                                    <h6 class="stat-label">السنة القادمة ({{ $nextYear }})</h6>
                                </div>
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #ffd166 0%, #ff9e00 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['recent'] }}</h2>
                                    <h6 class="stat-label">السنوات الحديثة</h6>
                                </div>
                                <i class="fas fa-history"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول السنوات -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>سنة الموديل</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($models as $model)
                            <tr>
                                <td>{{ $loop->iteration + (($models->currentPage() - 1) * $models->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="year-icon me-3">
                                            <div class="icon-wrapper bg-light rounded-circle p-2">
                                                <i class="fas fa-calendar-day text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">{{ $model->model_year }}</h4>
                                            <small class="text-muted">ID: {{ $model->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $yearDiff = $currentYear - $model->model_year;
                                        if ($model->model_year == $currentYear) {
                                            $statusColor = 'success';
                                            $statusText = 'السنة الحالية';
                                        } elseif ($model->model_year == $nextYear) {
                                            $statusColor = 'info';
                                            $statusText = 'السنة القادمة';
                                        } elseif ($yearDiff <= 5) {
                                            $statusColor = 'primary';
                                            $statusText = 'حديثة';
                                        } elseif ($yearDiff <= 10) {
                                            $statusColor = 'warning';
                                            $statusText = 'متوسطة';
                                        } else {
                                            $statusColor = 'secondary';
                                            $statusText = 'قديمة';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $model->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $model->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.car-models.edit', $model->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.car-models.destroy', $model->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف سنة الموديل {{ $model->model_year }}؟')">
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
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد سنوات موديلات</h5>
                                        <p class="text-muted">لم يتم إضافة أي سنوات موديلات بعد.</p>
                                        <div class="mt-3">
                                            <form action="{{ route('admin.car-models.add-next-year') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="fas fa-plus-circle me-1"></i>إضافة سنة جديدة
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.car-models.create') }}" class="btn btn-secondary">
                                                <i class="fas fa-plus me-1"></i>إضافة سنة يدوياً
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم المصغر -->
                @if($models->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($models->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $models->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($models->getUrlRange(1, $models->lastPage()) as $page => $url)
                                @if ($page == $models->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($models->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $models->nextPageUrl() }}" aria-label="Next">
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

<!-- Modal لإضافة مجموعة سنوات -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مجموعة سنوات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.car-models.bulk-create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_year" class="form-label">من سنة <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control" 
                                   id="start_year" 
                                   name="start_year" 
                                   value="{{ $currentYear - 4 }}"
                                   min="1900"
                                   max="{{ $currentYear + 10 }}"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_year" class="form-label">إلى سنة <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control" 
                                   id="end_year" 
                                   name="end_year" 
                                   value="{{ $currentYear + 1 }}"
                                   min="1900"
                                   max="{{ $currentYear + 10 }}"
                                   required>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        سيتم إضافة جميع السنوات في هذا المدى، مع تخطي السنوات الموجودة مسبقاً.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة المجموعة</button>
                </div>
            </form>
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

.year-icon .icon-wrapper {
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
    
    // التحقق من المدى في نموذج إضافة المجموعة
    const startYearInput = document.getElementById('start_year');
    const endYearInput = document.getElementById('end_year');
    
    if (startYearInput && endYearInput) {
        startYearInput.addEventListener('change', function() {
            if (parseInt(this.value) > parseInt(endYearInput.value)) {
                endYearInput.value = this.value;
            }
        });
        
        endYearInput.addEventListener('change', function() {
            if (parseInt(this.value) < parseInt(startYearInput.value)) {
                startYearInput.value = this.value;
            }
        });
    }
});
</script>
@endpush