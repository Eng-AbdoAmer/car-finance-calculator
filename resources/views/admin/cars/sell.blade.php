{{-- resources/views/admin/cars/sell.blade.php --}}
@extends('layouts.admin')

@section('title', 'بيع السيارة - ' . $car->code)
@section('page-title', 'بيع السيارة')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-shopping-cart me-2"></i>بيع السيارة: {{ $car->full_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cars.process-sale', $car->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sold_price">سعر البيع الفعلي <span class="text-danger">*</span></label>
                                    <input type="number" name="sold_price" id="sold_price" class="form-control"
                                        value="{{ old('sold_price', $car->selling_price) }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sale_date">تاريخ البيع <span class="text-danger">*</span></label>
                                    <input type="date" name="sale_date" id="sale_date" class="form-control"
                                        value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="buyer_name">اسم المشتري<span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_name" id="buyer_name" class="form-control"
                                        value="{{ old('buyer_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="buyer_phone">رقم الجوال <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_phone" id="buyer_phone" class="form-control"
                                        value="{{ old('buyer_phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="buyer_email">البريد الإلكتروني</label>
                                    <input type="email" name="buyer_email" id="buyer_email" class="form-control"
                                        value="{{ old('buyer_email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="buyer_address">العنوان</label>
                                    <input type="text" name="buyer_address" id="buyer_address" class="form-control"
                                        value="{{ old('buyer_address') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="car_status_id">تحديث حالة السيارة بعد البيع <span
                                            class="text-danger">*</span></label>
                                    <select name="car_status_id" id="car_status_id" class="form-control select2" required>
                                        <option value=""></option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                {{ old('car_status_id', $status->is_default ?? false) ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="notes">ملاحظات</label>
                                    <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success btn-lg"><i
                                    class="fas fa-check-circle me-1"></i>تأكيد البيع</button>
                            <a href="{{ route('admin.cars.show', $car->id) }}" class="btn btn-secondary btn-lg"><i
                                    class="fas fa-arrow-left me-1"></i>رجوع</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
