@extends('layouts.admin')

@section('title', 'تعديل بيانات الصورة')

@section('page-title', 'تعديل بيانات الصورة')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.car-images.index') }}">صور السيارات</a>
</li>
<li class="breadcrumb-item active">تعديل بيانات الصورة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-edit me-2"></i>تعديل بيانات الصورة
                    </h5>
                    <a href="{{ route('admin.car-images.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-images.update', $carImage->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- معاينة الصورة -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">معاينة الصورة</h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    <img src="{{ Storage::url($carImage->image_path) }}" 
                                         alt="{{ $carImage->description ?? 'صورة السيارة' }}"
                                         class="img-fluid rounded"
                                         style="max-height: 250px; object-fit: contain;">
                                    
                                    @if($carImage->is_main)
                                    <div class="mt-3">
                                        <span class="badge bg-warning">
                                            <i class="fas fa-crown me-1"></i>الصورة الرئيسية
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $carImage->created_at->format('Y/m/d') }}
                                        </small>
                                        @if($carImage->car)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-car me-1"></i>
                                            @if($carImage->car->brand && $carImage->car->model)
                                                {{ $carImage->car->brand->name }} - {{ $carImage->car->model->name }}
                                            @else
                                                السيارة #{{ $carImage->car_id }}
                                            @endif
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- بيانات النموذج -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">السيارة</label>
                                <input type="text" class="form-control" 
                                       value="@if($carImage->car && $carImage->car->brand && $carImage->car->model){{ $carImage->car->brand->name }} - {{ $carImage->car->model->name }}@elseالسيارة #{{ $carImage->car_id }}@endif ({{ $carImage->car->code ?? 'بدون كود' }})" 
                                       readonly>
                                <small class="text-muted">لا يمكن تغيير السيارة بعد الرفع</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image_type" class="form-label">نوع الصورة <span class="text-danger">*</span></label>
                                <select name="image_type" id="image_type" class="form-select @error('image_type') is-invalid @enderror" required>
                                    <option value="exterior" {{ old('image_type', $carImage->image_type) == 'exterior' ? 'selected' : '' }}>خارجي</option>
                                    <option value="interior" {{ old('image_type', $carImage->image_type) == 'interior' ? 'selected' : '' }}>داخلي</option>
                                    <option value="engine" {{ old('image_type', $carImage->image_type) == 'engine' ? 'selected' : '' }}>محرك</option>
                                    <option value="document" {{ old('image_type', $carImage->image_type) == 'document' ? 'selected' : '' }}>مستند</option>
                                </select>
                                @error('image_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="order" class="form-label">الترتيب <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="order" 
                                               id="order" 
                                               class="form-control @error('order') is-invalid @enderror"
                                               value="{{ old('order', $carImage->order) }}"
                                               min="1"
                                               required>
                                        @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">يحدد ترتيب عرض الصورة</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الحالة</label>
                                        <div class="form-control border-0">
                                            @if($carImage->is_main)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-crown me-1"></i>صورة رئيسية
                                            </span>
                                            @else
                                            <form action="{{ route('admin.car-images.set-main', $carImage->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-star me-1"></i>تعيين كرئيسية
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">وصف الصورة</label>
                                <textarea name="description" id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4">{{ old('description', $carImage->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- أزرار التحكم -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>حفظ التغييرات
                                    </button>
                                    <a href="{{ route('admin.car-images.show', $carImage->id) }}" class="btn btn-info ms-2">
                                        <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                    </a>
                                </div>
                                
                                <button type="button" 
                                        class="btn btn-danger" 
                                        onclick="confirmDelete({{ $carImage->id }})">
                                    <i class="fas fa-trash me-1"></i>حذف الصورة
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- نموذج حذف مخفي -->
                <form id="deleteForm{{ $carImage->id }}" 
                      action="{{ route('admin.car-images.destroy', $carImage->id) }}" 
                      method="POST" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 10px;
}

.form-control:read-only {
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف الصورة بشكل دائم ولن تتمكن من استرجاعها!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endpush