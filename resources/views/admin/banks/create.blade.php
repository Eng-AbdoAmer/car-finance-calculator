@extends('layouts.admin')

@section('title', 'إضافة بنك جديد')

@section('page-title', 'إضافة بنك جديد')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.banks.index') }}">البنوك</a></li>
<li class="breadcrumb-item active">إضافة بنك جديد</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-plus me-2"></i>إضافة بنك جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banks.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم البنك <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>حفظ
                        </button>
                        <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection