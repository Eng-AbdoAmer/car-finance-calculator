@extends('layouts.admin')

@section('title', 'تعديل سنة الموديل')

@section('page-title', 'تعديل سنة الموديل')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-models.index') }}">سنوات الموديلات</a></li>
<li class="breadcrumb-item active">تعديل سنة الموديل</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-edit me-2"></i>تعديل سنة الموديل: {{ $model->model_year }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-models.update', $model->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="model_year" class="form-label">سنة الموديل <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('model_year') is-invalid @enderror" 
                               id="model_year" 
                               name="model_year" 
                               value="{{ old('model_year', $model->model_year) }}"
                               placeholder="أدخل سنة الموديل (مثال: 2024)"
                               min="1900"
                               max="{{ $currentYear + 10 }}"
                               required
                               autofocus>
                        <div class="form-text">
                            أدخل سنة الموديل (بين 1900 و {{ $currentYear + 10 }}).
                        </div>
                        @error('model_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ التغييرات
                        </button>
                        <a href="{{ route('admin.car-models.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- معلومات إضافية -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>معلومات السنة
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>تاريخ الإنشاء:</strong>
                            <span class="text-muted ms-2">{{ $model->created_at->format('Y/m/d - h:i A') }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>آخر تحديث:</strong>
                            <span class="text-muted ms-2">
                                @if($model->updated_at->eq($model->created_at))
                                    لم يتم التعديل
                                @else
                                    {{ $model->updated_at->format('Y/m/d - h:i A') }}
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
                <div class="alert alert-info mt-2">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    إذا تم تعديل سنة الموديل، سيتم تحديث جميع السجلات المرتبطة بها تلقائياً.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection