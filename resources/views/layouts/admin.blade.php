<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - نظام إدارة المبيعات</title>
        <!-- Favicon (شعار التبويب) -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #06d6a0;
            --info-color: #118ab2;
            --warning-color: #ffd166;
            --danger-color: #ef476f;
            --dark-color: #073b4c;
            --light-color: #f8f9fa;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
        }

        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: #333;
            font-size: 0.95rem;
        }

        /* تخصيص سكرولبار */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* تنسيقات عامة */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-title {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 0;
        }

        /* Layout الرئيسي */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo span {
            font-size: 1rem;
            opacity: 0.9;
        }

        .sidebar-body {
            padding: 20px 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .nav-sidebar {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-right-color: white;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .nav-link span {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed~.main-content {
            margin-right: var(--sidebar-collapsed-width);
        }

        /* Header */
        .main-header {
            height: var(--header-height);
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left,
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        /* Content Area */
        .content-wrapper {
            padding: 1.5rem;
        }

        /* Stats Cards */
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
            position: absolute;
            left: 20px;
            bottom: 20px;
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

        .stat-change {
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Tables */
        .table th {
            font-weight: 600;
            color: var(--dark-color);
            border-top: none;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-right: -var(--sidebar-width);
            }

            .sidebar.show {
                margin-right: 0;
            }

            .main-content {
                margin-right: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                    <i class="fas fa-chart-line"></i>
                    <span class="brand-text">لوحة التحكم</span>
                </a>
                <button class="toggle-sidebar d-none d-lg-block" id="toggleSidebar">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="sidebar-body">
                <ul class="nav-sidebar">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>لوحة التحكم</span>
                        </a>
                    </li>
                    <!-- في القائمة الجانبية -->
                    <li class="nav-item">
                        <a href="{{ route('admin.calculations.index') }}"
                            class="nav-link {{ request()->routeIs('admin.calculations.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator me-2"></i>
                            <span>جميع الحسابات</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.cash-sales.index') }}"
                            class="nav-link {{ request()->routeIs('admin.cash-sales.*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>المبيعات النقدية</span>
                        </a>
                    </li> --}}

                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.financing-requests.index') }}"
                            class="nav-link {{ request()->routeIs('admin.financing-requests.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>طلبات التمويل</span>
                        </a>
                    </li> --}}

                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.finance-calculations.index') }}"
                            class="nav-link {{ request()->routeIs('admin.finance-calculations.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator"></i>
                            <span>الحسابات التمويلية</span>
                        </a>
                    </li> --}}

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>المستخدمين</span>
                        </a>
                    </li>
                        <li class="nav-item">
                        <a href="{{ route('admin.banks.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                             <i class="fas fa-university"></i>
                            <span>البنوك</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#carsMenu">
                            <i class="fas fa-car"></i>
                            <span>السيارات</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="carsMenu">
                            <ul class="nav flex-column ps-4">
                                <li class="nav-item">
                                    <a href="{{ route('admin.car-brands.index') }}" class="nav-link">الماركات</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.car-models.index') }}" class="nav-link">الموديلات</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                   
                    {{-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#settingsMenu">
                            <i class="fas fa-cog"></i>
                            <span>الإعدادات</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="settingsMenu">
                            <ul class="nav flex-column ps-4">
                                <li class="nav-item">
                                    <a href="{{ route('admin.insurance-rates.index') }}" class="nav-link">أسعار
                                        التأمين</a>
                                </li>
                            </ul>
                        </div>
                    </li> --}}

                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>التقارير والإحصائيات</span>
                        </a>
                    </li> --}}
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="d-flex align-items-center">
                        <img src="{{ auth()->user()->avatar_url }}" alt="User" class="user-avatar me-2">
                        <div>
                            <h6 class="mb-0 text-white">{{ auth()->user()->name }}</h6>
                            <small
                                class="text-white-50">{{ auth()->user()->type == 'admin' ? 'مدير النظام' : 'مستخدم' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button class="toggle-sidebar d-lg-none" id="mobileToggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'لوحة التحكم')</h1>
                </div>

                <div class="header-right">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">الإشعارات</h6>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">تمت معالجة طلب تمويل جديد</p>
                                        <small class="text-muted">منذ 5 دقائق</small>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0">مستحقات قيد الانتظار</p>
                                        <small class="text-muted">منذ ساعة</small>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="#">عرض جميع الإشعارات</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" alt="User" class="user-avatar">
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">

                              <a class="dropdown-item" href="{{ url('/') }}">
                                <i class="fas fa-home me-1"></i> الرئيسية
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-user me-2"></i>ملفي الشخصي
                            </a>
                            
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>الإعدادات
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>

    <script>
        // إدارة حالة السايدبار
        const sidebar = document.getElementById('sidebar');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const mobileToggleSidebar = document.getElementById('mobileToggleSidebar');

        // التحقق من الحالة المحفوظة
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });

        mobileToggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // إغلاق السايدبار عند النقر خارجها على الشاشات الصغيرة
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 && !sidebar.contains(event.target) &&
                !mobileToggleSidebar.contains(event.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // تهيئة Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                dir: "rtl",
                language: "ar"
            });

            // تهيئة DataTables
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                pageLength: 25,
                order: [
                    [0, 'desc']
                ]
            });

            // تهيئة التقويم
            flatpickr('.datepicker', {
                locale: "ar",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });

        // رسائل التأكيد قبل الحذف
        function confirmDelete(message = 'هل أنت متأكد من الحذف؟') {
            return confirm(message);
        }
    </script>

    @stack('scripts')
</body>

</html>
