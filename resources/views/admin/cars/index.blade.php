@extends('layouts.admin')

@section('title', 'إدارة السيارات')
@section('page-title', 'إدارة السيارات')
@section('breadcrumb')
    <li class="breadcrumb-item active">السيارات</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-car me-2"></i>قائمة السيارات
                        </h5>
                        <a href="{{ route('admin.cars.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة سيارة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- فلتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.cars.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="ابحث بالكود أو اللون..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="brand_id" class="form-control">
                                        <option value="">جميع العلامات</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status_id" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="condition" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        @foreach ($conditions as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ request('condition') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i> بحث
                                        </button>
                                        @if (request()->anyFilled(['search', 'brand_id', 'status_id', 'condition']))
                                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
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
                                        <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                        <h6 class="stat-label">إجمالي السيارات</h6>
                                    </div>
                                    <i class="fas fa-car"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $stats['available'] }}</h2>
                                        <h6 class="stat-label">متاحة للبيع</h6>
                                    </div>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $stats['sold'] }}</h2>
                                        <h6 class="stat-label">مباعة</h6>
                                    </div>
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="stat-number">{{ $stats['reserved'] }}</h2>
                                        <h6 class="stat-label">محجوزة</h6>
                                    </div>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول السيارات -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>الصورة</th>
                                    <th>السيارة</th>
                                    <th>الموديل</th>
                                    <th>الحالة</th>
                                    <th>السعر</th>
                                    <th>تاريخ الإضافة</th>
                                    <th width="200">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cars as $car)
                                    <tr>
                                        <td>{{ $loop->iteration + ($cars->currentPage() - 1) * $cars->perPage() }}</td>
                                        <td>
                                            @if ($car->mainImage)
                                                <img src="{{ asset('storage/' . $car->mainImage->image_path) }}"
                                                    alt="{{ $car->brand->name ?? '' }} {{ $car->type->name ?? '' }}"
                                                    style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <div class="no-image"
                                                    style="width: 60px; height: 40px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-car text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $car->brand->name ?? '' }}
                                                    {{ $car->type->name ?? '' }}</h6>
                                                <small class="text-muted">
                                                    {{ $car->code }} | {{ $car->color }}
                                                    @if ($car->trim)
                                                        | {{ $car->trim->name }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>{{ $car->model_year }}</td>
                                        <td>
                                            <span class="badge"
                                                style="background-color: {{ $car->status->color ?? '#6c757d' }}">
                                                {{ $car->status->name ?? 'غير محدد' }}
                                            </span>
                                            <br>
                                            <small
                                                class="text-muted">{{ $conditions[$car->condition] ?? $car->condition }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($car->selling_price) }} ر.س</strong>
                                            @if ($car->is_negotiable)
                                                <br><small class="text-success">قابل للتفاوض</small>
                                            @endif
                                            @if ($car->is_featured)
                                                <br><small class="text-warning"><i class="fas fa-star"></i> مميزة</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $car->created_at->format('Y/m/d') }}</div>
                                            <small class="text-muted">{{ $car->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.cars.show', $car->id) }}"
                                                    class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.cars.edit', $car->id) }}"
                                                    class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                    title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($car->availability == 'available')
                                                    {{-- زر البيع --}}
                                                    <a href="{{ route('admin.cars.mark-as-sold', $car->id) }}"
                                                        class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                                        title="بيع">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                    {{-- زر الحجز --}}
                                                    <form action="{{ route('admin.cars.mark-as-reserved', $car->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                            data-bs-toggle="tooltip" title="حجز">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    </form>
                                                @elseif($car->availability == 'reserved')
                                                    {{-- زر إلغاء الحجز --}}
                                                    <form action="{{ route('admin.cars.mark-as-available', $car->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info"
                                                            data-bs-toggle="tooltip" title="إلغاء الحجز">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- زر الحذف --}}
                                                <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه السيارة؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                                <h5>لا يوجد سيارات</h5>
                                                <p class="text-muted">لم يتم إضافة أي سيارات بعد.</p>
                                                <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-1"></i>إضافة سيارة جديدة
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم -->
                    @if ($cars->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $cars->links() }}
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

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            border-radius: 5px;
            margin: 0 2px;
        }

        /* تخصيص الترقيم */
        .pagination .page-link {
            border-radius: 5px;
            margin: 0 3px;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        /* تحسين الشكل على الأجهزة الصغيرة */
        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .btn-group {
                flex-wrap: wrap;
                justify-content: center;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .table td,
            .table th {
                padding: 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
