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
                        <i class="fas fa-layer-group me-2"></i>قائمة فئات السيارات
                    </h5>
                    <a href="{{ route('admin.car-trims.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>إضافة فئة جديدة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.car-trims.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="ابحث بالاسم أو الكود..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="brand_id" class="form-control">
                                    <option value="">جميع العلامات</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type_id" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    @if(request()->anyFilled(['search', 'brand_id', 'type_id']))
                                    <a href="{{ route('admin.car-trims.index') }}" class="btn btn-secondary">
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
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي الفئات</h6>
                                </div>
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['brands'] }}</h2>
                                    <h6 class="stat-label">علامات تجارية</h6>
                                </div>
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['types'] }}</h2>
                                    <h6 class="stat-label">أنواع سيارات</h6>
                                </div>
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #7209b7 0%, #560bad 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['with_cars'] }}</h2>
                                    <h6 class="stat-label">فئات مستخدمة</h6>
                                </div>
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accordion لعرض الفئات -->
                @if($groupedTrims->isNotEmpty())
                    <div class="accordion" id="brandsAccordion">
                        @foreach($groupedTrims as $brandName => $brandTypes)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $loop->index }}" 
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                        aria-controls="collapse{{ $loop->index }}">
                                    <div class="d-flex align-items-center w-100">
                                        <div class="brand-logo me-3">
                                            <div class="logo-placeholder">
                                                {{ substr($brandName, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="brand-info flex-grow-1 text-center">
                                            <h5 class="mb-0">{{ $brandName }}</h5>
                                            <small class="text-muted">{{ count($brandTypes) }} نوع، {{ $brandTypes->sum(function($types) { return count($types); }) }} فئة</small>
                                        </div>
                                        <span class="badge bg-primary ms-2">{{ $brandTypes->sum(function($types) { return count($types); }) }}</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $loop->index }}" 
                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                 aria-labelledby="heading{{ $loop->index }}" 
                                 data-bs-parent="#brandsAccordion">
                                <div class="accordion-body">
                                    @foreach($brandTypes as $typeName => $trims)
                                    <div class="type-section mb-4">
                                        <div class="type-header bg-info p-3 rounded mb-3">
                                            <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                                <span>{{ $typeName }}</span>
                                                <span class="badge bg-secondary">{{ count($trims) }} فئة</span>
                                            </h5>
                                        </div>
                                        
                                        <div class="row">
                                            @foreach($trims as $trim)
                                            <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                                                <div class="trim-card h-100">
                                                    <div class="card border">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div>
                                                                    <h6 class="card-title mb-1">{{ $trim->name }}</h6>
                                                                    <p class="card-text mb-1">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-hashtag me-1"></i>
                                                                            {{ $trim->code ?? 'N/A' }}
                                                                        </small>
                                                                    </p>
                                                                </div>
                                                                <span class="badge bg-info">{{ $trim->cars_count ?? 0 }} سيارة</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="far fa-calendar me-1"></i>
                                                                    {{ $trim->created_at->format('Y/m/d') }}
                                                                </small>
                                                                <div class="btn-group">
                                                                    <a href="{{ route('admin.car-trims.show', $trim->id) }}" 
                                                                       class="btn btn-sm btn-outline-info"
                                                                       data-bs-toggle="tooltip"
                                                                       title="عرض">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.car-trims.edit', $trim->id) }}" 
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       data-bs-toggle="tooltip"
                                                                       title="تعديل">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('admin.car-trims.destroy', $trim->id) }}" 
                                                                          method="POST"
                                                                          class="d-inline"
                                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" 
                                                                                class="btn btn-sm btn-outline-danger"
                                                                                data-bs-toggle="tooltip"
                                                                                title="حذف">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- الترقيم -->
                    @if($trimsPaginated->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($trimsPaginated->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $trimsPaginated->previousPageUrl() }}" aria-label="Previous">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $current = $trimsPaginated->currentPage();
                                    $last = $trimsPaginated->lastPage();
                                    $start = max($current - 1, 1);
                                    $end = min($current + 1, $last);
                                @endphp

                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $trimsPaginated->url(1) }}">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $current)
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $trimsPaginated->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $trimsPaginated->url($last) }}">{{ $last }}</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($trimsPaginated->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $trimsPaginated->nextPageUrl() }}" aria-label="Next">
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
                    
                @else
                <div class="empty-state text-center py-5">
                    <i class="fas fa-layer-group fa-4x text-muted mb-3"></i>
                    <h5>لا يوجد فئات</h5>
                    <p class="text-muted mb-4">لم يتم إضافة أي فئات سيارات بعد.</p>
                    <a href="{{ route('admin.car-trims.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>إضافة فئة جديدة
                    </a>
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
    padding: 3rem;
}

.empty-state i {
    opacity: 0.5;
}

.stat-card {
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
    font-weight: 500;
}

/* بطاقات الفئات */
.trim-card {
    transition: all 0.3s ease;
}

.trim-card:hover {
    transform: translateY(-3px);
    background-color: #eee;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.trim-card .card {
    border-radius: 8px;
    overflow: hidden;
}
.trim-card .card-body:hover{
        background-color: azure;
}
/* لوجو العلامة التجارية */
.logo-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
}

/* Accordion customization */
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #4361ee;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    border-color: #4361ee;
}

.type-header {
    background-color: #f8f9fa;
    border-left: 4px solid #4361ee;
}

/* تنسيق البادجات */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* تنسيق العناصر النصية */
.text-muted {
    font-size: 0.85rem;
}

/* تنسيق الأزرار */
.btn-group .btn {
    border-radius: 5px !important;
    margin: 0 2px;
}

/* على الشاشات الصغيرة */
@media (max-width: 768px) {
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .accordion-button .brand-info h5 {
        font-size: 1rem;
    }
    
    .logo-placeholder {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .btn-group .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
}

/* Pagination styling for Bootstrap 5 */
.pagination .page-link {
    color: #4361ee;
    border-radius: 5px;
    margin: 0 2px;
    min-width: 35px;
    text-align: center;
}

.pagination .page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
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
    
    // فلترة أنواع السيارات بناءً على العلامة التجارية المختارة
    const brandSelect = document.querySelector('select[name="brand_id"]');
    const typeSelect = document.querySelector('select[name="type_id"]');
    
    if (brandSelect && typeSelect) {
        brandSelect.addEventListener('change', function() {
            const brandId = this.value;
            if (brandId) {
                // تحديث الرابط بالمعلمة الجديدة
                const url = new URL(window.location.href);
                url.searchParams.set('brand_id', brandId);
                url.searchParams.delete('type_id'); // إعادة تعيين النوع
                window.location.href = url.toString();
            }
        });
    }
});
</script>
@endpush