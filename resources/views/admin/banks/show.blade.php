@extends('layouts.admin')

@section('title', 'تفاصيل البنك')

@section('page-title', 'تفاصيل البنك')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.banks.index') }}">البنوك</a></li>
<li class="breadcrumb-item active">تفاصيل البنك</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-eye me-2"></i>تفاصيل البنك
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">رقم البنك</th>
                                <td>{{ $bank->id }}</td>
                            </tr>
                            <tr>
                                <th>اسم البنك</th>
                                <td>{{ $bank->name }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الإنشاء</th>
                                <td>{{ $bank->created_at->format('Y/m/d h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ التحديث</th>
                                <td>
                                    @if($bank->updated_at->eq($bank->created_at))
                                        لم يتم التعديل
                                    @else
                                        {{ $bank->updated_at->format('Y/m/d h:i A') }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <a href="{{ route('admin.banks.edit', $bank->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>تعديل
                    </a>
                    <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection