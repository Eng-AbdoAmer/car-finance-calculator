@extends('layouts.admin')

@section('title', 'تفاصيل البراند')

@section('page-title', 'تفاصيل البراند')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">براندات السيارات</a></li>
<li class="breadcrumb-item active">تفاصيل البراند</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i>تفاصيل البراند
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="brand-icon-large mb-3">
                            <div class="icon-wrapper-large bg-light rounded-circle p-4 d-inline-block">
                                <i class="fas fa-car fa-3x text-primary"></i>
                            </div>
                        </div>
                        <h4>{{ $brand->name }}</h4>
                        <span class="badge bg-primary">ID: {{ $brand->id }}</span>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <h6><i class="fas fa-hashtag me-2 text-primary"></i>معرف البراند</h6>
                                    <p class="mb-0">{{ $brand->id }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <h6><i class="fas fa-calendar-plus me-2 text-primary"></i>تاريخ الإنشاء</h6>
                                    <p class="mb-0">{{ $brand->created_at->format('Y/m/d - h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <h6><i class="fas fa-calendar-check me-2 text-primary"></i>تاريخ التحديث</h6>
                                    <p class="mb-0">
                                        @if($brand->updated_at->eq($brand->created_at))
                                            لم يتم التعديل
                                        @else
                                            {{ $brand->updated_at->format('Y/m/d - h:i A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <h6><i class="fas fa-car-side me-2 text-primary"></i>عدد الموديلات</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-info">
                                            موديل
                                            {{-- {{ $brand->models->count() }} موديل --}}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.car-brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.car-brands.edit', $brand->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                        <form action="{{ route('admin.car-brands.destroy', $brand->id) }}" 
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا البراند؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-card {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-right: 4px solid #4361ee;
}

.info-card h6 {
    color: #4361ee;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.info-card p {
    font-size: 1rem;
    font-weight: 500;
}

.brand-icon-large .icon-wrapper-large {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-sm th, .table-sm td {
    padding: 0.5rem;
}

@media (max-width: 768px) {
    .brand-icon-large .icon-wrapper-large {
        width: 90px;
        height: 90px;
    }
    
    .brand-icon-large i {
        font-size: 2rem !important;
    }
}
</style>
@endpush