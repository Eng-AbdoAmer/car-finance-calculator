@extends('layouts.admin')

@section('title', 'إضافة سنة موديل جديدة')

@section('page-title', 'إضافة سنة موديل جديدة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-models.index') }}">سنوات الموديلات</a></li>
<li class="breadcrumb-item active">إضافة سنة جديدة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus-circle me-2"></i>إضافة سنة موديل جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-models.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="model_year" class="form-label">سنة الموديل <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('model_year') is-invalid @enderror" 
                               id="model_year" 
                               name="model_year" 
                               value="{{ old('model_year', $suggestedYear) }}"
                               placeholder="أدخل سنة الموديل (مثال: 2024)"
                               min="1900"
                               max="{{ $currentYear + 10 }}"
                               required
                               autofocus>
                        <div class="form-text">
                            أدخل سنة الموديل (بين 1900 و {{ $currentYear + 10 }}).
                            @if($suggestedYear)
                            <br>تم اقتراح السنة التالية تلقائياً: <strong>{{ $suggestedYear }}</strong>
                            @endif
                        </div>
                        @error('model_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ السنة
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
                    <i class="fas fa-info-circle me-2 text-primary"></i>معلومات مفيدة
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        يمكنك استخدام زر "إضافة سنة تلقائية" لإضافة السنة التالية مباشرة
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        يمكنك إضافة مجموعة سنوات مرة واحدة باستخدام خاصية "إضافة مجموعة"
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success me-2"></i>
                        تأكد من أن سنة الموديل غير مكررة في النظام
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection