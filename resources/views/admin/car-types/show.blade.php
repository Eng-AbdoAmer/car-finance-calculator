@extends('layouts.admin')

@section('title', 'تفاصيل نوع السيارة')

@section('page-title', 'تفاصيل نوع السيارة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">براندات السيارات</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.car-types.index') }}">أنواع السيارات</a></li>
<li class="breadcrumb-item active">تفاصيل</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>تفاصيل نوع السيارة
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.car-types.edit', $type->id) }}" class="btn btn-light">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                        <a href="{{ route('admin.car-types.index') }}" class="btn btn-light">
                            <i class="fas fa-list me-1"></i>القائمة
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="type-icon mx-auto mb-3">
                            <div class="icon-wrapper bg-light rounded-circle p-3">
                                <i class="fas fa-car fa-3x text-info"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ $type->name }}</h3>
                        <p class="text-muted">ID: {{ $type->id }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-card">
                            <h6 class="info-label"><i class="fas fa-tag me-2"></i>البراند</h6>
                            <p class="info-value">
                                @if($type->brand)
                                    <a href="{{ route('admin.car-brands.show', $type->brand->id) }}" 
                                       class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $type->brand->name }}</span>
                                    </a>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-card">
                            <h6 class="info-label"><i class="fas fa-layer-group me-2"></i>عدد الطرازات</h6>
                            <p class="info-value">
                                <span class="badge bg-{{ $type->trims_count > 0 ? 'info' : 'warning' }}">
                                    {{ $type->trims_count }} طراز
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-card">
                            <h6 class="info-label"><i class="fas fa-calendar-plus me-2"></i>تاريخ الإنشاء</h6>
                            <p class="info-value">
                                {{ $type->created_at->format('Y/m/d') }}
                                <small class="text-muted">({{ $type->created_at->format('h:i A') }})</small>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-card">
                            <h6 class="info-label"><i class="fas fa-calendar-check me-2"></i>تاريخ التحديث</h6>
                            <p class="info-value">
                                @if($type->updated_at->eq($type->created_at))
                                    <span class="text-muted">لم يتم التعديل</span>
                                @else
                                    {{ $type->updated_at->format('Y/m/d') }}
                                    <small class="text-muted">({{ $type->updated_at->format('h:i A') }})</small>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($type->trims_count > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">
                            <i class="fas fa-list me-2"></i>الطرازات المرتبطة
                        </h5>
                        <div class="list-group">
                            @foreach($type->trims as $trim)
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $trim->name }}</h6>
                                    <small>{{ $trim->code ?? 'بدون كود' }}</small>
                                </div>
                                <small class="text-muted">تم الإنشاء: {{ $trim->created_at->format('Y/m/d') }}</small>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #dee2e6;
}

.info-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0;
}

.type-icon .icon-wrapper {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #dee2e6;
}
</style>
@endpush