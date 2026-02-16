@extends('layouts.admin')

@section('title', 'تفاصيل فئة السيارة')

@section('page-title', 'تفاصيل فئة السيارة')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.car-trims.index') }}">فئات السيارات</a></li>
<li class="breadcrumb-item active">تفاصيل الفئة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-eye me-2"></i>تفاصيل فئة السيارة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">رقم الفئة</th>
                                <td>{{ $trim->id }}</td>
                            </tr>
                            <tr>
                                <th>الكود</th>
                                <td>{{ $trim->code ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>الاسم</th>
                                <td>{{ $trim->name }}</td>
                            </tr>
                            <tr>
                                <th>العلامة التجارية</th>
                                <td>{{ $trim->brand->name }}</td>
                            </tr>
                            <tr>
                                <th>عدد السيارات</th>
                                <td>
                                    <span class="badge bg-info">{{ $trim->cars_count }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>تاريخ الإنشاء</th>
                                <td>{{ $trim->created_at->format('Y/m/d h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ التحديث</th>
                                <td>
                                    @if($trim->updated_at->eq($trim->created_at))
                                        لم يتم التعديل
                                    @else
                                        {{ $trim->updated_at->format('Y/m/d h:i A') }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- قائمة السيارات -->
                @if($trim->cars->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-car me-2"></i>السيارات من هذه الفئة</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>الكود</th>
                                        <th>اللون</th>
                                        <th>السنة</th>
                                        <th>الحالة</th>
                                        <th>السعر</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trim->cars as $car)
                                    <tr>
                                        <td>{{ $car->code }}</td>
                                        <td>{{ $car->color }}</td>
                                        <td>{{ $car->model_year }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $car->status->color }}">
                                                {{ $car->status->name }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($car->selling_price) }} ر.س</td>
                                        <td>
                                            <a href="{{ route('admin.cars.show', $car->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group mt-4">
                    <a href="{{ route('admin.car-trims.edit', $trim->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>تعديل
                    </a>
                    <a href="{{ route('admin.car-trims.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection