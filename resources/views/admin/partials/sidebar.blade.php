{{-- <div class="card border-0 shadow-lg">
    <div class="card-header bg-gradient-primary text-white border-0 py-4">
        <h5 class="mb-0 text-center">
            <i class="fas fa-bars me-2"></i>القائمة
        </h5>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('admin.dashboard') }}" 
           class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-primary text-white' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
        </a>
        <a href="{{ route('admin.users.index') }}" 
           class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'bg-gradient-primary text-white' : '' }}">
            <i class="fas fa-users me-2"></i>إدارة المستخدمين
        </a>
        <a href="{{ route('admin.calculations.index') }}" 
           class="list-group-item list-group-item-action {{ request()->routeIs('admin.calculations.*') ? 'bg-gradient-primary text-white' : '' }}">
            <i class="fas fa-calculator me-2"></i>العمليات الحسابية
        </a>
        <a href="{{ route('admin.stats.index') }}" 
           class="list-group-item list-group-item-action {{ request()->routeIs('admin.stats.*') ? 'bg-gradient-primary text-white' : '' }}">
            <i class="fas fa-chart-bar me-2"></i>التقارير والإحصائيات
        </a>
        <a href="{{ route('admin.settings.index') }}" 
           class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-primary text-white' : '' }}">
            <i class="fas fa-cog me-2"></i>إعدادات النظام
        </a>
        <a href="{{ route('profile.index') }}" 
           class="list-group-item list-group-item-action">
            <i class="fas fa-user-circle me-2"></i>ملفي الشخصي
        </a>
    </div>
</div> --}}