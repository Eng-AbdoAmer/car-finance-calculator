@extends('layouts.admin')

@section('title', 'تفاصيل نوع الوقود')

@section('page-title', 'تفاصيل نوع الوقود')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.fuel-types.index') }}">أنواع الوقود</a></li>
<li class="breadcrumb-item active">تفاصيل النوع</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-eye me-2"></i>تفاصيل نوع الوقود
                    </h5>
                    <div>
                        <a href="{{ route('admin.fuel-types.edit', $type->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- معلومات النوع -->
                <div class="info-section mb-4">
                    <div class="info-header bg-light p-3 rounded mb-3 d-flex align-items-center">
                        <div class="fuel-icon me-3">
                            <i class="fas fa-gas-pump fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات نوع الوقود</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">اسم النوع:</label>
                                <div class="fw-bold fs-5">{{ $type->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">رقم المعرف:</label>
                                <div class="fw-bold">#{{ $type->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">عدد السيارات:</label>
                                <div>
                                    <span class="badge bg-info fs-6">{{ $type->cars_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">تاريخ الإنشاء:</label>
                                <div class="fw-bold">
                                    {{ $type->created_at->format('Y/m/d') }}
                                    <small class="text-muted d-block">{{ $type->created_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="text-muted small mb-1">آخر تحديث:</label>
                                <div class="fw-bold">
                                    {{ $type->updated_at->format('Y/m/d') }}
                                    <small class="text-muted d-block">{{ $type->updated_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- السيارات المرتبطة -->
                @if($type->cars_count > 0)
                <div class="cars-section mb-4">
                    <div class="cars-header bg-light p-3 rounded mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-car me-2"></i>السيارات المرتبطة ({{ $type->cars_count }})</h6>
                        <a href="{{ route('admin.cars.index', ['fuel_type_id' => $type->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>عرض جميع السيارات
                        </a>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        هذا النوع مرتبط بــ <strong>{{ $type->cars_count }}</strong> سيارة. لا يمكن حذف النوع إلا بعد إزالة جميع السيارات المرتبطة به.
                    </div>
                </div>
                @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    هذا النوع غير مرتبط بأي سيارة حالياً، ويمكن حذفه بأمان إذا لزم الأمر.
                </div>
                @endif

                <!-- زر الحذف -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.fuel-types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                    </a>
                    @if($type->cars_count == 0)
                    <form action="{{ route('admin.fuel-types.destroy', $type->id) }}" 
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> حذف النوع
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

.fuel-icon {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4361ee;
}

.badge.fs-6 {
    font-size: 1rem;
    padding: 0.5em 1em;
}
</style>
@endpush