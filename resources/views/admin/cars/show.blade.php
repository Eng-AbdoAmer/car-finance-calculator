{{-- resources/views/admin/cars/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'تفاصيل السيارة: ' . $car->code)
@section('page-title', 'تفاصيل السيارة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item active">تفاصيل السيارة: {{ $car->code }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-car me-2"></i>تفاصيل السيارة: {{ $car->code }}
                    </h5>
                    <div>
                        <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                        <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>رجوع
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- حالة السيارة وأزرار الإجراءات السريعة -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span
                                    class="badge fs-6 px-3 py-2
                                @if ($car->availability == 'available') bg-success
                                @elseif($car->availability == 'sold') bg-danger
                                @elseif($car->availability == 'reserved') bg-warning
                                @else bg-secondary @endif">
                                    {{ $car->availability_name }}
                                </span>

                                @if ($car->is_featured)
                                    <span class="badge bg-info fs-6 px-3 py-2">مميزة</span>
                                @endif
                                @if ($car->is_negotiable)
                                    <span class="badge bg-secondary fs-6 px-3 py-2">قابلة للتفاوض</span>
                                @endif
                                @if ($car->is_financeable)
                                    <span class="badge bg-success fs-6 px-3 py-2">قابلة للتمويل</span>
                                @endif

                                <!-- أزرار الإجراءات السريعة -->
                                <div class="ms-auto">
                                    @if ($car->availability == 'available')
                                        <form action="{{ route('admin.cars.mark-as-reserved', $car->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-clock me-1"></i>حجز
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.cars.mark-as-sold', $car->id) }}"
                                            class="btn btn-danger btn-sm">
                                            <i class="fas fa-dollar-sign me-1"></i>بيع
                                        </a>
                                    @elseif($car->availability == 'reserved')
                                        <form action="{{ route('admin.cars.mark-as-available', $car->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm">
                                                <i class="fas fa-check-circle me-1"></i>إلغاء الحجز
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- عمود الصور والمعلومات الأساسية -->
                        <div class="col-md-4">
                            <!-- معرض الصور -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-images me-2"></i>صور السيارة</h6>
                                </div>
                                <div class="card-body">
                                    @if ($car->images->count() > 0)
                                        <div id="carImages" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach ($car->images as $index => $image)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                            class="d-block w-100 rounded" alt="صورة السيارة"
                                                            style="height: 300px; object-fit: cover;">
                                                        @if ($image->is_main)
                                                            <span
                                                                class="position-absolute top-0 start-0 badge bg-primary m-2">رئيسية</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if ($car->images->count() > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carImages" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">السابق</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carImages" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">التالي</span>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">{{ $car->images->count() }} صورة</small>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد صور للسيارة</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- المعلومات الأساسية -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات أساسية</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">الكود:</th>
                                            <td>{{ $car->code }}</td>
                                        </tr>
                                        <tr>
                                            <th>رقم الهيكل:</th>
                                            <td>{{ $car->chassis_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>رقم اللوحة:</th>
                                            <td>{{ $car->plate_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>العلامة التجارية:</th>
                                            <td>{{ $car->brand->name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>الموديل:</th>
                                            <td>{{ $car->type->name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>الفئة:</th>
                                            <td>{{ $car->trim->name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>التصنيف:</th>
                                            <td>{{ $car->category->name ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>سنة الموديل:</th>
                                            <td>{{ $car->model_year }}</td>
                                        </tr>
                                        <tr>
                                            <th>اللون:</th>
                                            <td>
                                                <span class="d-inline-block rounded-circle me-2"
                                                    style="width: 15px; height: 15px; background-color: {{ $car->color }}; border: 1px solid #ddd;"></span>
                                                {{ $car->color }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>الحالة (جديد/مستعمل):</th>
                                            <td>{{ $car->condition_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>الممشي:</th>
                                            <td>{{ number_format($car->mileage) }} كم</td>
                                        </tr>
                                        <tr>
                                            <th>تاريخ الإضافة:</th>
                                            <td>{{ $car->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                        @if ($car->soldBy)
                                            <tr>
                                                <th>تم البيع بواسطة:</th>
                                                <td>{{ $car->soldBy->name }}</td>
                                            </tr>
                                        @endif
                                        @if ($car->reservedBy)
                                            <tr>
                                                <th>تم الحجز بواسطة:</th>
                                                <td>{{ $car->reservedBy->name }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- عمود التفاصيل الرئيسية -->
                        <div class="col-md-8">
                            <!-- المواصفات الفنية -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>المواصفات الفنية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><strong>ناقل
                                                الحركة:</strong><br>{{ $car->transmission->name ?? 'غير محدد' }}</div>
                                        <div class="col-md-4 mb-3"><strong>نوع
                                                الوقود:</strong><br>{{ $car->fuelType->name ?? 'غير محدد' }}</div>
                                        <div class="col-md-4 mb-3"><strong>نوع
                                                الدفع:</strong><br>{{ $car->driveType->name ?? 'غير محدد' }}</div>
                                        <div class="col-md-4 mb-3"><strong>سعة
                                                المحرك:</strong><br>{{ $car->engine_capacity ? number_format($car->engine_capacity) . ' سي سي' : 'غير محدد' }}
                                        </div>
                                        <div class="col-md-4 mb-3"><strong>القوة
                                                الحصانية:</strong><br>{{ $car->horse_power ? number_format($car->horse_power) . ' حصان' : 'غير محدد' }}
                                        </div>
                                        <div class="col-md-4 mb-3"><strong>عدد
                                                الأسطوانات:</strong><br>{{ $car->cylinders ?? 'غير محدد' }}</div>
                                        <div class="col-md-4 mb-3"><strong>عدد
                                                الأبواب:</strong><br>{{ $car->doors ?? 'غير محدد' }}</div>
                                        <div class="col-md-4 mb-3"><strong>عدد
                                                المقاعد:</strong><br>{{ $car->seats ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- المعلومات المالية -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>المعلومات المالية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><strong>سعر
                                                الشراء:</strong><br>{{ number_format($car->purchase_price, 2) }} ر.س</div>
                                        <div class="col-md-3 mb-3"><strong>سعر
                                                البيع:</strong><br>{{ number_format($car->selling_price, 2) }} ر.س</div>
                                        @if ($car->sold_price)
                                            <div class="col-md-3 mb-3"><strong>سعر البيع الفعلي:</strong><br
                                                    class="text-danger">{{ number_format($car->sold_price, 2) }} ر.س</div>
                                        @endif
                                        @if ($car->profit)
                                            <div class="col-md-3 mb-3"><strong>الربح:</strong><br
                                                    class="{{ $car->profit > 0 ? 'text-success' : 'text-danger' }}">{{ number_format($car->profit, 2) }}
                                                ر.س ({{ $car->profit_percentage }}%)</div>
                                        @endif
                                        @if ($car->days_in_stock)
                                            <div class="col-md-3 mb-3"><strong>أيام في
                                                    المخزون:</strong><br>{{ (int) $car->days_in_stock }} يوم</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- التواريخ -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>التواريخ</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><strong>تاريخ
                                                الشراء:</strong><br>{{ $car->purchase_date ? $car->purchase_date->format('Y-m-d') : 'غير محدد' }}
                                        </div>
                                        <div class="col-md-3 mb-3"><strong>تاريخ
                                                البيع:</strong><br>{{ $car->sale_date ? $car->sale_date->format('Y-m-d') : 'غير محدد' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الوصف -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>الوصف</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $car->description ?? 'لا يوجد وصف' }}</p>
                                </div>
                            </div>

                            <!-- سجل الصيانة إن وجد -->
                            @if ($car->maintenanceRecords->count() > 0)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-tools me-2"></i>سجل الصيانة</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>التاريخ</th>
                                                        <th>النوع</th>
                                                        <th>التكلفة</th>
                                                        <th>الوصف</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($car->maintenanceRecords as $record)
                                                        <tr>
                                                            <td>{{ $record->maintenance_date->format('Y-m-d') }}</td>
                                                            <td>{{ $record->maintenance_type }}</td>
                                                            <td>{{ number_format($record->cost, 2) }} ر.س</td>
                                                            <td>{{ $record->description }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- سجل تغيير الأسعار -->
                            @if ($car->priceHistory->count() > 0)
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-light d-flex align-items-center">
                                        <i class="fas fa-history text-primary me-2"></i>
                                        <h6 class="mb-0 fw-bold">سجل تغيير الأسعار</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="py-3 px-3">التاريخ</th>
                                                        <th class="py-3 px-3">النوع</th>
                                                        <th class="py-3 px-3">السعر القديم</th>
                                                        <th class="py-3 px-3">السعر الجديد</th>
                                                        <th class="py-3 px-3">السبب</th>
                                                        <th class="py-3 px-3">تم بواسطة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($car->priceHistory as $history)
                                                        <tr>
                                                            <td class="px-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="far fa-calendar-alt text-secondary ms-2"></i>
                                                                    <span>{{ $history->created_at->format('Y-m-d H:i') }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-3">
                                                                @php
                                                                    $badgeClass = match ($history->price_type) {
                                                                        'purchase' => 'bg-info',
                                                                        'selling' => 'bg-success',
                                                                        'sold' => 'bg-danger',
                                                                        default => 'bg-secondary',
                                                                    };
                                                                    $typeLabel = match ($history->price_type) {
                                                                        'purchase' => 'شراء',
                                                                        'selling' => 'بيع',
                                                                        'sold' => 'بيع فعلي',
                                                                        default => $history->price_type,
                                                                    };
                                                                @endphp
                                                                <span
                                                                    class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                                                    <i
                                                                        class="fas 
                                        @if ($history->price_type == 'purchase') fa-cart-plus
                                        @elseif($history->price_type == 'selling') fa-tag
                                        @elseif($history->price_type == 'sold') fa-check-circle
                                        @else fa-history @endif me-1">
                                                                    </i>
                                                                    {{ $typeLabel }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3">
                                                                @if ($history->old_price)
                                                                    <span class="text-muted text-decoration-line-through">
                                                                        {{ number_format($history->old_price, 2) }} ر.س
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">—</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-3 fw-bold text-primary">
                                                                {{ number_format($history->new_price, 2) }} ر.س
                                                            </td>
                                                            <td class="px-3">{{ $history->reason ?: '—' }}</td>
                                                            <td class="px-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-user-circle text-secondary ms-2"></i>
                                                                    <span>{{ $history->changedBy->name ?? 'النظام' }}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center py-5">
                                                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                                                <h6 class="text-muted">لا يوجد سجل لتغيير الأسعار</h6>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- footer الأزرار -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>تعديل
                            </a>
                            @if ($car->availability == 'available')
                                <a href="{{ route('admin.cars.mark-as-sold', $car->id) }}" class="btn btn-danger">
                                    <i class="fas fa-dollar-sign me-1"></i>تسجيل بيع
                                </a>
                            @endif
                        </div>
                        <div>
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذه السيارة؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i>حذف
                                </button>
                            </form>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>رجوع للقائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
        }

        .badge {
            font-size: 0.9rem;
        }

        .table-sm th {
            width: 40%;
        }
    </style>
@endpush
