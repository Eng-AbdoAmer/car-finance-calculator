@extends('layouts.admin')

@section('title', 'إدارة صور السيارات')

@section('page-title', 'إدارة صور السيارات')

@section('breadcrumb')
    <li class="breadcrumb-item active">صور السيارات</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-images me-2"></i>معرض صور السيارات
                        </h5>
                        <a href="{{ route('admin.car-images.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة صور جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- فلتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('admin.car-images.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <select name="car_id" class="form-select">
                                        <option value="">جميع السيارات</option>
                                        @foreach ($cars as $car)
                                            <option value="{{ $car->id }}" {{ $carId == $car->id ? 'selected' : '' }}>
                                                @if ($car->brand)
                                                    {{ $car->brand->name }}
                                                    @if ($car->model)
                                                        - {{ $car->model->name }}
                                                    @endif
                                                    @if ($car->model_year)
                                                        ({{ $car->model_year }})
                                                    @endif
                                                @else
                                                    السيارة #{{ $car->id }}
                                                @endif
                                                - {{ $car->code ?? 'بدون كود' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="image_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        <option value="exterior"
                                            {{ request('image_type') == 'exterior' ? 'selected' : '' }}>خارجي</option>
                                        <option value="interior"
                                            {{ request('image_type') == 'interior' ? 'selected' : '' }}>داخلي</option>
                                        <option value="engine" {{ request('image_type') == 'engine' ? 'selected' : '' }}>
                                            محرك</option>
                                        <option value="document"
                                            {{ request('image_type') == 'document' ? 'selected' : '' }}>مستندات</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-filter"></i> تصفية
                                        </button>
                                        @if (request('car_id') || request('image_type'))
                                            <a href="{{ route('admin.car-images.index') }}" class="btn btn-secondary">
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
                                        <h2 class="stat-number">{{ $images->total() }}</h2>
                                        <h6 class="stat-label">إجمالي الصور</h6>
                                    </div>
                                    <i class="fas fa-images"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $images->where('image_type', 'exterior')->count() }}
                                        </h2>
                                        <h6 class="stat-label">صور خارجية</h6>
                                    </div>
                                    <i class="fas fa-car"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $images->where('image_type', 'interior')->count() }}
                                        </h2>
                                        <h6 class="stat-label">صور داخلية</h6>
                                    </div>
                                    <i class="fas fa-couch"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $images->where('is_main', true)->count() }}</h2>
                                        <h6 class="stat-label">صور رئيسية</h6>
                                    </div>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معرض الصور -->
                    @if ($images->count() > 0)
                        <div class="row gallery-grid">
                            @foreach ($images as $image)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <div class="gallery-card">
                                        <div class="gallery-item position-relative">
                                            <!-- الصورة المصغرة -->
                                            <div class="gallery-thumbnail">
                                                <img src="{{ Storage::url($image->image_path) }}"
                                                    alt="{{ $image->description ?? 'صورة السيارة' }}" class="img-fluid"
                                                    data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">

                                                <!-- علامة الصورة الرئيسية -->
                                                @if ($image->is_main)
                                                    <div class="main-badge">
                                                        <i class="fas fa-crown"></i> رئيسية
                                                    </div>
                                                @endif

                                                <!-- علامة نوع الصورة -->
                                                <div class="type-badge">
                                                    @switch($image->image_type)
                                                        @case('exterior')
                                                            <i class="fas fa-car"></i> خارجي
                                                        @break

                                                        @case('interior')
                                                            <i class="fas fa-couch"></i> داخلي
                                                        @break

                                                        @case('engine')
                                                            <i class="fas fa-cogs"></i> محرك
                                                        @break

                                                        @case('document')
                                                            <i class="fas fa-file-alt"></i> مستند
                                                        @break
                                                    @endswitch
                                                </div>
                                            </div>

                                            <!-- معلومات الصورة -->
                                            <div class="gallery-info p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">
                                                        @if ($image->car && $image->car->brand)
                                                            {{ $image->car->brand->name }}
                                                            @if ($image->car->model_year)
                                                                - {{ $image->car->model_year }}
                                                            @endif
                                                        @else
                                                            السيارة #{{ $image->car_id }}
                                                        @endif
                                                    </h6>
                                                    <span class="badge bg-secondary">#{{ $image->order }}</span>
                                                </div>

                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-hashtag"></i>
                                                        {{ $image->car->code ?? 'بدون كود' }}
                                                    </small>
                                                </div>

                                                @if ($image->description)
                                                    <p class="text-muted small mb-2">
                                                        {{ Str::limit($image->description, 50) }}</p>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        {{ $image->created_at->diffForHumans() }}
                                                    </small>
                                                    <div class="btn-group btn-group-sm">
                                                        <!-- زر تعيين كرئيسية -->
                                                        @if (!$image->is_main)
                                                            <form
                                                                action="{{ route('admin.car-images.set-main', $image->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning"
                                                                    data-bs-toggle="tooltip" title="تعيين كرئيسية">
                                                                    <i class="fas fa-star"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <!-- زر التعديل -->
                                                        <a href="{{ route('admin.car-images.edit', $image->id) }}"
                                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                            title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- زر الحذف -->
                                                        <form action="{{ route('admin.car-images.destroy', $image->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="tooltip" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- مودال عرض الصورة الكبيرة -->
                                <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    @if ($image->car && $image->car->brand)
                                                        {{ $image->car->brand->name }}
                                                        @if ($image->car->model_year)
                                                            - {{ $image->car->model_year }}
                                                        @endif
                                                    @else
                                                        السيارة #{{ $image->car_id }}
                                                    @endif
                                                    -
                                                    @switch($image->image_type)
                                                        @case('exterior')
                                                            صورة خارجية
                                                        @break

                                                        @case('interior')
                                                            صورة داخلية
                                                        @break

                                                        @case('engine')
                                                            صورة المحرك
                                                        @break

                                                        @case('document')
                                                            مستند
                                                        @break
                                                    @endswitch
                                                </h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ Storage::url($image->image_path) }}"
                                                    alt="{{ $image->description ?? 'صورة السيارة' }}"
                                                    class="img-fluid rounded">

                                                @if ($image->description)
                                                    <div class="mt-3">
                                                        <p class="lead">{{ $image->description }}</p>
                                                    </div>
                                                @endif

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="info-box">
                                                            <i class="fas fa-car me-2"></i>
                                                            <strong>السيارة:</strong>
                                                            @if ($image->car && $image->car->brand)
                                                                {{ $image->car->brand->name }}
                                                                @if ($image->car->model_year)
                                                                    - {{ $image->car->model_year }}
                                                                @endif
                                                            @else
                                                                السيارة #{{ $image->car_id }}
                                                            @endif
                                                            <br>
                                                            <small class="text-muted">كود:
                                                                {{ $image->car->code ?? 'بدون كود' }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-box">
                                                            <i class="fas fa-calendar me-2"></i>
                                                            <strong>تاريخ الإضافة:</strong>
                                                            {{ $image->created_at->format('Y/m/d') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ Storage::url($image->image_path) }}" class="btn btn-primary"
                                                    download>
                                                    <i class="fas fa-download me-1"></i>تحميل الصورة
                                                </a>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- حالة عدم وجود صور -->
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                                <h5>لا توجد صور</h5>
                                <p class="text-muted mb-4">لم يتم إضافة أي صور للسيارات بعد.</p>
                                <a href="{{ route('admin.car-images.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>إضافة صور جديدة
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- الترقيم -->
                    @if ($images->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    @php
                                        $current = $images->currentPage();
                                        $last = $images->lastPage();
                                        $start = max($current - 1, 1);
                                        $end = min($current + 1, $last);
                                    @endphp

                                    {{-- Previous Page Link --}}
                                    @if ($images->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $images->previousPageUrl() }}"
                                                aria-label="Previous">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $images->url(1) }}">1</a>
                                        </li>
                                        @if ($start > 2)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @for ($i = $start; $i <= $end; $i++)
                                        @if ($i == $current)
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $images->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if ($end < $last)
                                        @if ($end < $last - 1)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $images->url($last) }}">{{ $last }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($images->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $images->nextPageUrl() }}" aria-label="Next">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .empty-state {
            text-align: center;
            padding: 2rem;
        }

        .empty-state i {
            opacity: 0.5;
        }

        .stat-card {
            border-radius: 10px;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
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
        }

        /* معرض الصور */
        .gallery-grid {
            margin: -10px;
        }

        .gallery-card {
            border: 1px solid #eaeaea;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            height: 100%;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: #4361ee;
        }

        .gallery-thumbnail {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f8f9fa;
            cursor: pointer;
        }

        .gallery-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-thumbnail:hover img {
            transform: scale(1.05);
        }

        .main-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            z-index: 2;
        }

        .type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            z-index: 2;
        }

        .gallery-info {
            background: white;
            border-top: 1px solid #f1f1f1;
        }

        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        /* زر الترقيم */
        .pagination .page-link {
            border-radius: 5px;
            margin: 0 2px;
            min-width: 35px;
            text-align: center;
            color: #4361ee;
        }

        .pagination .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .gallery-grid {
                margin: -5px;
            }

            .gallery-thumbnail {
                height: 150px;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .card-header .d-flex {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // تفعيل أدوات التلميح
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
