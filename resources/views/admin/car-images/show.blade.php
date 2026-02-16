@extends('layouts.admin')

@section('title', 'تفاصيل الصورة')

@section('page-title', 'تفاصيل الصورة')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.car-images.index') }}">صور السيارات</a>
</li>
<li class="breadcrumb-item active">تفاصيل الصورة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-image me-2"></i>تفاصيل الصورة
                    </h5>
                    <div>
                        <a href="{{ route('admin.car-images.edit', $carImage->id) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                        <a href="{{ route('admin.car-images.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-1"></i>العودة
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- الصورة الرئيسية -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">الصورة</h6>
                            </div>
                            <div class="card-body text-center p-4">
                                <img src="{{ Storage::url($carImage->image_path) }}" 
                                     alt="{{ $carImage->description ?? 'صورة السيارة' }}"
                                     class="img-fluid rounded shadow"
                                     style="max-height: 400px; object-fit: contain; cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#fullSizeModal">
                                
                                @if($carImage->is_main)
                                <div class="mt-3">
                                    <span class="badge bg-warning fs-6 px-3 py-2">
                                        <i class="fas fa-crown me-2"></i>الصورة الرئيسية
                                    </span>
                                </div>
                                @endif
                                
                                <div class="mt-3">
                                    <a href="{{ Storage::url($carImage->image_path) }}" 
                                       class="btn btn-outline-primary" 
                                       download="car_image_{{ $carImage->id }}.jpg">
                                        <i class="fas fa-download me-1"></i>تحميل الصورة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- تفاصيل الصورة -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">معلومات الصورة</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th width="140"><i class="fas fa-hashtag text-primary me-2"></i>رقم الصورة:</th>
                                                <td>{{ $carImage->id }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-car text-primary me-2"></i>السيارة:</th>
                                                <td>
                                                    @if($carImage->car)
                                                    <a href="{{ route('admin.cars.show', $carImage->car_id) }}" class="text-decoration-none">
                                                        @if($carImage->car->brand && $carImage->car->model)
                                                            {{ $carImage->car->brand->name }} - {{ $carImage->car->model->name }}
                                                        @else
                                                            السيارة #{{ $carImage->car_id }}
                                                        @endif
                                                        ({{ $carImage->car->code ?? 'بدون كود' }})
                                                    </a>
                                                    @else
                                                    <span class="text-muted">السيارة محذوفة</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-tag text-primary me-2"></i>نوع الصورة:</th>
                                                <td>
                                                    @switch($carImage->image_type)
                                                        @case('exterior')
                                                            <span class="badge bg-info">
                                                                <i class="fas fa-car me-1"></i>خارجي
                                                            </span>
                                                            @break
                                                        @case('interior')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-couch me-1"></i>داخلي
                                                            </span>
                                                            @break
                                                        @case('engine')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-cogs me-1"></i>محرك
                                                            </span>
                                                            @break
                                                        @case('document')
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-file-alt me-1"></i>مستند
                                                            </span>
                                                            @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-sort-numeric-up text-primary me-2"></i>الترتيب:</th>
                                                <td>
                                                    <span class="badge bg-dark fs-6">{{ $carImage->order }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-star text-primary me-2"></i>الحالة:</th>
                                                <td>
                                                    @if($carImage->is_main)
                                                    <span class="badge bg-warning">صورة رئيسية</span>
                                                    @else
                                                    <span class="badge bg-secondary">صورة عادية</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-calendar text-primary me-2"></i>تاريخ الإضافة:</th>
                                                <td>
                                                    {{ $carImage->created_at->format('Y/m/d') }}
                                                    <small class="text-muted d-block">({{ $carImage->created_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-clock text-primary me-2"></i>آخر تحديث:</th>
                                                <td>
                                                    {{ $carImage->updated_at->format('Y/m/d') }}
                                                    <small class="text-muted d-block">({{ $carImage->updated_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الوصف -->
                        @if($carImage->description)
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">وصف الصورة</h6>
                            </div>
                            <div class="card-body">
                                <p class="lead">{{ $carImage->description }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- معلومات الملف -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">معلومات الملف</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-folder text-primary me-2"></i>
                                            <strong>المسار:</strong>
                                            <small class="d-block text-truncate">{{ $carImage->image_path }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-link text-primary me-2"></i>
                                            <strong>الرابط المباشر:</strong>
                                            <small class="d-block text-truncate">
                                                <a href="{{ Storage::url($carImage->image_path) }}" target="_blank">فتح في نافذة جديدة</a>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-code text-primary me-2"></i>
                                            <strong>كود الإدراج:</strong>
                                            <small class="d-block">
                                                <code>&lt;img src="{{ Storage::url($carImage->image_path) }}"&gt;</code>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-shield-alt text-primary me-2"></i>
                                            <strong>الحالة:</strong>
                                            @if(file_exists(storage_path('app/public/' . $carImage->image_path)))
                                            <span class="badge bg-success">الملف موجود</span>
                                            @else
                                            <span class="badge bg-danger">الملف مفقود</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- أزرار التحكم -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="btn-group" role="group">
                                    @if(!$carImage->is_main)
                                    <form action="{{ route('admin.car-images.set-main', $carImage->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning me-2">
                                            <i class="fas fa-crown me-1"></i>تعيين كرئيسية
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.car-images.edit', $carImage->id) }}" class="btn btn-primary me-2">
                                        <i class="fas fa-edit me-1"></i>تعديل
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            onclick="confirmDelete()">
                                        <i class="fas fa-trash me-1"></i>حذف الصورة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال الصورة كاملة -->
<div class="modal fade" id="fullSizeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($carImage->car)
                        @if($carImage->car->brand && $carImage->car->model)
                            {{ $carImage->car->brand->name }} - {{ $carImage->car->model->name }}
                        @else
                            السيارة #{{ $carImage->car_id }}
                        @endif
                    @else
                        صورة السيارة
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ Storage::url($carImage->image_path) }}" 
                     alt="{{ $carImage->description ?? 'صورة السيارة' }}"
                     class="img-fluid">
            </div>
            <div class="modal-footer">
                <a href="{{ Storage::url($carImage->image_path) }}" 
                   class="btn btn-primary" 
                   download>
                    <i class="fas fa-download me-1"></i>تحميل
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- نموذج حذف مخفي -->
<form id="deleteForm" 
      action="{{ route('admin.car-images.destroy', $carImage->id) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.card {
    border-radius: 10px;
}

.info-box {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    height: 100%;
    transition: transform 0.3s ease;
}

.info-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table-borderless td, .table-borderless th {
    padding: 12px 8px;
}

.badge.fs-6 {
    font-size: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete() {
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
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endpush