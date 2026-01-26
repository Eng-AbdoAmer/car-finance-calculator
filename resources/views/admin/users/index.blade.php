@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('breadcrumb')
<li class="breadcrumb-item active">إدارة المستخدمين</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>قائمة المستخدمين
                    </h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>إضافة مستخدم جديد
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- فلتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <select name="type" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع المستخدمين</option>
                                    <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>المسؤولين</option>
                                    <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>المستخدمين</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="ابحث بالاسم أو رقم الهاتف..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->hasAny(['type', 'search']))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active">
                                <i class="fas fa-list"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- بطاقات الإحصائيات -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['total'] }}</h2>
                                    <h6 class="stat-label">إجمالي المستخدمين</h6>
                                </div>
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['admins'] }}</h2>
                                    <h6 class="stat-label">مسؤولين</h6>
                                </div>
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['users'] }}</h2>
                                    <h6 class="stat-label">مستخدمين عاديين</h6>
                                </div>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #ffd166 0%, #ff9e00 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="stat-number">{{ $stats['active_today'] }}</h2>
                                    <h6 class="stat-label">جدد اليوم</h6>
                                </div>
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول المستخدمين -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>النوع</th>
                                <th>تاريخ التسجيل</th>
                                {{-- <th>الحالة</th> --}}
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar_url }}" 
                                             alt="{{ $user->name }}"
                                             class="rounded-circle me-3"
                                             width="40"
                                             height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-gradient-primary text-black">
                                            <i class="fas fa-crown me-1"></i>مسؤول
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-success text-black">
                                            <i class="fas fa-user me-1"></i>مستخدم
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $user->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </td>
                                {{-- <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    
                                    <!-- زر تغيير الصلاحية -->
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.update.type', $user->id) }}" 
                                          method="POST"
                                          class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="user" {{ $user->type == 'user' ? 'selected' : '' }}>مستخدم</option>
                                                <option value="admin" {{ $user->type == 'admin' ? 'selected' : '' }}>مسؤول</option>
                                            </select>
                                        </div>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>لا يوجد مستخدمين</h5>
                                        <p class="text-muted">لم يتم إضافة أي مستخدمين بعد.</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>إضافة مستخدم جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لتغيير الصلاحية -->
<div class="modal fade" id="changeRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير صلاحية المستخدم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changeRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">اختر الصلاحية</label>
                        <select name="type" id="role" class="form-select">
                            <option value="user">مستخدم عادي</option>
                            <option value="admin">مسؤول</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        المسؤولون لديهم صلاحية الوصول إلى لوحة التحكم وإدارة النظام.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تغيير الصلاحية</button>
                </div>
            </form>
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
}
</style>
@endpush

@push('scripts')
<script>
// تفعيل أدوات التلميح
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // دالة فتح modal تغيير الصلاحية
    window.openChangeRoleModal = function(userId, currentRole) {
        const form = document.getElementById('changeRoleForm');
        form.action = `/admin/users/${userId}/type`;
        document.getElementById('role').value = currentRole;
        const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
        modal.show();
    };
});
</script>
@endpush