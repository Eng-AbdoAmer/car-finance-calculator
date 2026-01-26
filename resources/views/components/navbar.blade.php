{{-- resources/views/components/navbar.blade.php --}}

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
    <div class="container">
        <!-- Logo/Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            <div class="logo-icon me-2">
                <i class="fas fa-car"></i>
            </div>
            <span class="d-none d-md-inline">نظام تمويل السيارات</span>
            <span class="d-md-none">التمويل الذكي</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home me-1"></i>الرئيسية
                    </a>
                </li>

                @if ($isAuthenticated)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calculator me-1"></i>الخدمات
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('finance.calculator') }}">
                                    <i class="fas fa-university me-2"></i>بنك الراجحي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('financing.index') }}">
                                    <i class="fas fa-landmark me-2"></i>البنوك المتعددة
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cash-sales.index') }}">
                                    <i class="fas fa-landmark me-2"></i> مبيعات الكاش
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('finance.history') }}">
                                    <i class="fas fa-history me-2"></i>سجل حسابات بنك الراجحي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('financing.history') }}">
                                    <i class="fas fa-history me-2"></i>سجل حسابات البنوك المتعددة
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="#features">
                        <i class="fas fa-star me-1"></i>المميزات
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#how-it-works">
                        <i class="fas fa-play-circle me-1"></i>كيف يعمل
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">
                        <i class="fas fa-phone me-1"></i>اتصل بنا
                    </a>
                </li>
            </ul>

            <!-- User Actions -->
            <div class="d-flex align-items-center gap-2">
                @if ($isAuthenticated)
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button"
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="d-none d-md-inline">{{ $userName }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('finance.history') }}">
                                    <i class="fas fa-history me-2"></i>سجل الحسابات
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user-cog me-2"></i>الملف الشخصي
                                </a>
                            </li>
                            @if (Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                                    </a>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                    @csrf
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <a href="{{ route('login') }}" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        <span class="d-none d-md-inline">تسجيل الدخول</span>
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light">
                            <i class="fas fa-user-plus me-1"></i>
                            <span class="d-none d-md-inline">إنشاء حساب</span>
                        </a>
                    @endif
                @endif

                <!-- Language Selector (Optional) -->
            </div>
        </div>
    </div>
</nav>
