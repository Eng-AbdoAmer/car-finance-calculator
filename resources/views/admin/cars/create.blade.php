{{-- resources/views/admin/cars/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'إضافة سيارة جديدة')
@section('page-title', 'إضافة سيارة')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item active">إضافة سيارة</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-plus-circle me-2"></i>إضافة سيارة جديدة
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>خطأ!</strong> لم يتم حفظ البيانات. يرجى مراجعة الأخطاء أدناه.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data"
                        id="carForm">
                        @csrf

                        {{-- كود السيارة (تلقائي) --}}
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 fa-2x"></i>
                                <div>
                                    <h6 class="mb-1">كود السيارة</h6>
                                    <p class="mb-0">سيتم إنشاء كود تلقائي: <strong>{{ $defaultCode }}</strong></p>
                                    <small class="text-muted">يمكنك تغييره لاحقاً من صفحة التعديل</small>
                                </div>
                            </div>
                        </div>

                        {{-- المعلومات الفريدة --}}
                        @include('admin.cars._partials.unique_info')

                        {{-- المعلومات الأساسية --}}
                        @include('admin.cars._partials.basic_info')

                        {{-- المواصفات الفنية --}}
                        @include('admin.cars._partials.technical_specs')

                        {{-- الأسعار --}}
                        @include('admin.cars._partials.pricing')

                        {{-- رفع الصور --}}
                        @include('admin.cars._partials.images_upload')

                        {{-- معلومات إضافية --}}
                        @include('admin.cars._partials.additional_info')

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-save me-1"></i>حفظ السيارة
                            </button>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                language: 'ar',
                dir: 'rtl',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // بيانات الأنواع والفئات من المتحكم
            var allTypes = @json($types); // CarType
            var allTrims = @json($trims); // CarTrim

            // تصفية الأنواع حسب العلامة التجارية
            function filterTypes(brandId) {
                var options = '<option value=""></option>';
                if (brandId) {
                    allTypes.forEach(function(type) {
                        if (type.car_brand_id == brandId) {
                            options += `<option value="${type.id}">${type.name}</option>`;
                        }
                    });
                }
                $('#car_type_id').html(options).trigger('change.select2');
            }

            // تصفية الفئات حسب النوع
            function filterTrims(typeId) {
                var options = '<option value=""></option>';
                if (typeId) {
                    allTrims.forEach(function(trim) {
                        if (trim.car_type_id == typeId) {
                            options += `<option value="${trim.id}">${trim.name}</option>`;
                        }
                    });
                }
                $('#car_trim_id').html(options).trigger('change.select2');
            }

            // ربط الأحداث
            $('#car_brand_id').on('change', function() {
                var brandId = $(this).val();
                filterTypes(brandId);
                $('#car_trim_id').html('<option value=""></option>').trigger('change.select2');
            });

            $('#car_type_id').on('change', function() {
                var typeId = $(this).val();
                filterTrims(typeId);
            });

            // استعادة القيم بعد التحقق (validation)
            @if (old('car_brand_id'))
                $('#car_brand_id').val('{{ old('car_brand_id') }}').trigger('change');
                setTimeout(function() {
                    $('#car_type_id').val('{{ old('car_type_id') }}').trigger('change');
                    setTimeout(function() {
                        $('#car_trim_id').val('{{ old('car_trim_id') }}').trigger('change');
                    }, 200);
                }, 300);
            @endif
        });
    </script>
@endpush
