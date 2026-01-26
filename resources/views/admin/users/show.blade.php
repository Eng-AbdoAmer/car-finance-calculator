@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">المستخدمين</a></li>
<li class="breadcrumb-item active">تفاصيل المستخدم</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- بطاقة معلومات المستخدم -->
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" 
                     alt="{{ $user->name }}"
                     class="rounded-circle mb-3"
                     width="120"
                     height="120">
                
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                
                @if($user->isAdmin())
                    <span class="badge bg-gradient-primary mb-3">
                        <i class="fas fa-crown me-1"></i>مسؤول
                    </span>
                @else
                    <span class="badge bg-gradient-success mb-3">
                        <i class="fas fa-user me-1"></i>مستخدم عادي
                    </span>
                @endif
                
                <div class="list-group list-group-flush mt-3">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>رقم الهاتف</span>
                        <strong>{{ $user->phone }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>تاريخ التسجيل</span>
                        <strong>{{ $user->created_at->format('Y/m/d') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>آخر تحديث</span>
                        <strong>{{ $user->updated_at->format('Y/m/d') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>الحالة</span>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">مفعل</span>
                        @else
                            <span class="badge bg-warning">قيد الانتظار</span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i>تعديل
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>حذف
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- إحصائيات المستخدم -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إحصائيات</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h3 class="text-primary mb-0">{{ $userStats['calculations_count'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">الحسابات</p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h3 class="text-success mb-0">0</h3>
                            <p class="mb-0 text-muted">المبيعات</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- معلومات إضافية -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>بيانات الاتصال</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <strong>البريد:</strong> {{ $user->email }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                <strong>الهاتف:</strong> {{ $user->phone }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>معلومات الحساب</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-user-tag me-2 text-primary"></i>
                                <strong>النوع:</strong> 
                                @if($user->isAdmin())
                                    مسؤول النظام
                                @else
                                    مستخدم عادي
                                @endif
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                <strong>تاريخ الإنشاء:</strong> 
                                {{ $user->created_at->format('Y/m/d h:i A') }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-history me-2 text-primary"></i>
                                <strong>آخر تحديث:</strong> 
                                {{ $user->updated_at->format('Y/m/d h:i A') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- آخر النشاطات -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>آخر النشاطات</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
            </div>
            <div class="card-body">
                @if(isset($userStats['last_calculation']) && $userStats['last_calculation'])
                <div class="activity-item">
                    <div class="d-flex">
                        <div class="activity-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">عملية حسابية جديدة</h6>
                            <p class="mb-1 text-muted">تم إجراء عملية حسابية بقيمة {{ number_format($userStats['last_calculation']->car_price, 2) }} ر.س</p>
                            <small class="text-muted">{{ $userStats['last_calculation']->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">لا توجد نشاطات مسجلة</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.activity-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
}

.list-group-item {
    border: none;
    padding: 12px 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>
@endpush