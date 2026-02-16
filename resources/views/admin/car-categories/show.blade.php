@extends('layouts.admin')

@section('title', 'تفاصيل الفئة')

@section('page-title', 'تفاصيل الفئة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-categories.index') }}">فئات السيارات</a></li>
<li class="breadcrumb-item active">تفاصيل الفئة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-tag me-2"></i>تفاصيل الفئة
                    </h5>
                    <div>
                        <a href="{{ route('admin.car-categories.edit', $category->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- معلومات الفئة -->
                <div class="info-section mb-4">
                    <div class="info-header bg-light p-3 rounded mb-3">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الفئة</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">اسم الفئة:</label>
                                <div class="fw-bold fs-5">{{ $category->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">رقم المعرف:</label>
                                <div class="fw-bold">#{{ $category->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">عدد السيارات:</label>
                                <div>
                                    <span class="badge bg-info fs-6">{{ $category->cars_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">تاريخ الإنشاء:</label>
                                <div class="fw-bold">
                                    {{ $category->created_at->format('Y/m/d') }}
                                    <small class="text-muted d-block">{{ $category->created_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">آخر تحديث:</label>
                                <div class="fw-bold">
                                    {{ $category->updated_at->format('Y/m/d') }}
                                    <small class="text-muted d-block">{{ $category->updated_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- السيارات المرتبطة -->
                @if($category->cars_count > 0)
                <div class="cars-section mb-4">
                    <div class="cars-header bg-light p-3 rounded mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-car me-2"></i>السيارات المرتبطة ({{ $category->cars_count }})</h6>
                        <a href="{{ route('admin.cars.index', ['category_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>عرض جميع السيارات
                        </a>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        هذه الفئة مرتبطة بــ <strong>{{ $category->cars_count }}</strong> سيارة. لا يمكن حذف الفئة إلا بعد إزالة جميع السيارات المرتبطة بها.
                    </div>
                </div>
                @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    هذه الفئة غير مرتبطة بأي سيارة حالياً، ويمكن حذفها بأمان إذا لزم الأمر.
                </div>
                @endif

                <!-- زر الحذف -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.car-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                    </a>
                    @if($category->cars_count == 0)
                    <form action="{{ route('admin.car-categories.destroy', $category->id) }}" 
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> حذف الفئة
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #4361ee;
}

.info-header {
    border-left: 4px solid #4361ee;
}

.badge.fs-6 {
    font-size: 1rem;
    padding: 0.5em 1em;
}
</style>
@endpush