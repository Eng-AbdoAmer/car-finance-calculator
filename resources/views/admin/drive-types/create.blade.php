@extends('layouts.admin')

@section('title', 'إضافة نوع دفع جديد')

@section('page-title', 'إضافة نوع دفع جديد')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.drive-types.index') }}">أنواع الدفع</a></li>
<li class="breadcrumb-item active">إضافة نوع جديد</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus me-2"></i>إضافة نوع دفع جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.drive-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم نوع الدفع <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="مثال: دفع أمامي، دفع خلفي، دفع رباعي"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">اسم نوع الدفع يجب أن يكون فريداً</small>
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">الكود <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="مثال: FWD, RWD, AWD"
                                   required
                                   maxlength="10">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">كود مختصر يجب أن يكون فريداً (10 حروف كحد أقصى)</small>
                        </div>

                        <div class="col-12">
                            <div class="info-section bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-info-circle me-2"></i>أنواع الدفع الشائعة:</h6>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <ul class="mb-0">
                                            <li><strong>FWD</strong> - دفع أمامي (Front Wheel Drive)</li>
                                            <li><strong>RWD</strong> - دفع خلفي (Rear Wheel Drive)</li>
                                            <li><strong>AWD</strong> - دفع رباعي (All Wheel Drive)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="mb-0">
                                            <li><strong>4WD</strong> - دفع رباعي كامل (4 Wheel Drive)</li>
                                            <li><strong>4x2</strong> - دفع ثنائي</li>
                                            <li><strong>4x4</strong> - دفع رباعي</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.drive-types.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> رجوع للقائمة
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> حفظ النوع
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-section {
    border-left: 4px solid #4361ee;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
}
</style>
@endpush