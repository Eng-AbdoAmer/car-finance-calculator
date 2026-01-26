@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="contact-hero-section position-relative overflow-hidden">
        <x-navbar />

        <div class="container position-relative z-index-2">
            <div class="row align-items-center min-vh-70 py-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="mb-4">
                        <div class="hero-badge mb-4 animate__animated animate__bounceIn">
                            <span class="badge bg-gradient-primary rounded-pill px-4 py-2">
                                <i class="fas fa-headset me-2"></i>دعم واتصال
                            </span>
                        </div>

                        <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                            تواصل مع <span class="">العربه الفريده</span>
                        </h1>
                    </div>

                    <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                        نحن هنا لخدمتك على مدار الساعة، تواصل مع فريقنا المختص للحصول على أفضل عروض السيارات
                    </p>
                </div>
            </div>
        </div>

        <!-- خلفية متحركة -->
        <div class="hero-background">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="particles" id="particles-js"></div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-info-section py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="badge bg-light text-primary rounded-pill px-4 py-2 mb-3">
                        <i class="fas fa-info-circle me-2"></i>معلومات التواصل
                    </span>
                    <h2 class="fw-bold mb-3 text-white">شركة العربة الفريدة للسيارات</h2>
                    <p class="text-white lead">خميس مشيط - حي المعارض - أمام معارض السيارات</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <!-- العنوان -->
                <div class="col-lg-4 col-md-6">
                    <div class="info-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp">
                        <div class="card-body p-4 text-center">
                            <div class="info-icon mb-4">
                                <div
                                    class="icon-wrapper bg-gradient-primary rounded-circle p-3 animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3 text-white">العنوان</h4>
                            <p class="text-white mb-0">
                                خميس مشيط<br>
                                حي المعارض<br>
                                أمام معارض السيارات الرئيسية
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ساعات العمل -->
                <div class="col-lg-4 col-md-6">
                    <div
                        class="info-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="card-body p-4 text-center">
                            <div class="info-icon mb-4">
                                <div
                                    class="icon-wrapper bg-gradient-success rounded-circle p-3 animate__animated animate__pulse animate__infinite animate__delay-1s">
                                    <i class="fas fa-clock fa-2x text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3 text-white">ساعات العمل</h4>
                            <p class="text-white mb-0">
                                السبت - الخميس: 8 صباحاً - 8 مساءً<br>
                                الجمعة : 3 مساءً - 8 مساءً<br>
                                خدمة 24/7 عبر الهاتف
                            </p>
                        </div>
                    </div>
                </div>

                <!-- وسائل التواصل -->
                <div class="col-lg-4 col-md-6">
                    <div
                        class="info-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="card-body p-4 text-center">
                            <div class="info-icon mb-4">
                                <div
                                    class="icon-wrapper bg-gradient-warning rounded-circle p-3 animate__animated animate__pulse animate__infinite animate__delay-2s">
                                    <i class="fas fa-comments fa-2x text-white"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3 text-white">وسائل التواصل</h4>
                            <p class="text-white mb-0">
                                البريد الإلكتروني: info@alarabah.com<br>
                                {{-- الهاتف: 800-123-4567<br>
                            فاكس: 017-123-4567 --}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أرقام المبيعات -->
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="section-header mb-4">
                        <h3 class="fw-bold text-white mb-4 text-center">
                            <i class="fas fa-phone-volume me-2"></i>أرقام المبيعات المباشرة
                        </h3>
                    </div>

                    <div class="row g-4">
                        @php
                            $salesNumbers = [
                                [
                                    'name' => 'رمضان',
                                    'number' => '0530577333',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'primary',
                                ],
                                [
                                    'name' => 'أبو تولين',
                                    'number' => '0532115333',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'success',
                                ],
                                [
                                    'name' => 'أبو حنين',
                                    'number' => '0530272333',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'warning',
                                ],
                                [
                                    'name' => 'أبو ياسين',
                                    'number' => '0559053330',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'danger',
                                ],
                                [
                                    'name' => 'سفر',
                                    'number' => '0505859595',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'info',
                                ],
                                [
                                    'name' => 'الرقم الثابت',
                                    'number' => '0172337777',
                                    'icon' => 'fas fa-user-tie',
                                    'color' => 'primary',
                                ],
                            ];
                        @endphp

                        @foreach ($salesNumbers as $index => $sales)
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="sales-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-{{ $index * 0.2 }}s">
                                    <div class="card-body p-4 text-center">
                                        <div class="sales-icon mb-3">
                                            <div class="icon-wrapper bg-gradient-{{ $sales['color'] }} rounded-circle p-3">
                                                <i class="{{ $sales['icon'] }} fa-2x text-white"></i>
                                            </div>
                                        </div>
                                        <h5 class="fw-bold mb-2 text-white">{{ $sales['name'] }}</h5>
                                        <div class="phone-number mb-3">
                                            <a href="tel:{{ $sales['number'] }}" class="text-decoration-none h1">
                                                <h4 class="fw-bold text-{{ $sales['color'] }} mb-0">{{ $sales['number'] }}
                                                </h4>
                                            </a>
                                        </div>
                                        <button class="btn btn-outline-light btn-call"
                                            onclick="window.location.href='tel:{{ $sales['number'] }}'">
                                            <i class="fas fa-phone me-2"></i>اتصل الآن
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- وسائل التواصل الاجتماعي -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-header mb-4">
                        <h3 class="fw-bold text-white mb-4 text-center">
                            <i class="fas fa-share-alt me-2"></i>وسائل التواصل الاجتماعي
                        </h3>
                    </div>

                    <div class="social-media-wrapper">
                        <div class="row justify-content-center g-4">
                            @php
                                $socialMedia = [
                                    [
                                        'platform' => 'TikTok',
                                        'username' => '@alearab2',
                                        'icon' => 'fab fa-tiktok',
                                        'color' => '#000000',
                                        'url' => 'https://www.tiktok.com/@alearab2',
                                    ],
                                    [
                                        'platform' => 'Snapchat',
                                        'username' => 'alarabah999',
                                        'icon' => 'fab fa-snapchat-ghost',
                                        'color' => '#FFFC00',
                                        'url' => 'https://www.snapchat.com/add/alarabah999',
                                    ],
                                    [
                                        'platform' => 'Haraj',
                                        'username' => '@alaeraba',
                                        'icon' => 'fas fa-shopping-cart',
                                        'color' => '#FF4500',
                                        'url' => 'https://haraj.com.sa/@alaeraba',
                                    ],
                                    [
                                        'platform' => 'Facebook',
                                        'username' => 'AlArabahMotor',
                                        'icon' => 'fab fa-facebook',
                                        'color' => '#1877F2',
                                        'url' => 'https://www.facebook.com/profile.php?id=61577379869899',
                                    ],
                                    [
                                        'platform' => 'Instagram',
                                        'username' => '@alarabamotor',
                                        'icon' => 'fab fa-instagram',
                                        'color' => '#E4405F',
                                        'url' => 'https://www.instagram.com/alarabamotor/',
                                    ],
                                    [
                                        'platform' => 'WhatsApp',
                                        'username' => 'تواصل مباشر',
                                        'icon' => 'fab fa-whatsapp',
                                        'color' => '#25D366',
                                        'url' => 'https://wa.me/966530577333',
                                    ],
                                ];
                            @endphp

                            @foreach ($socialMedia as $index => $social)
                                <div class="col-lg-4 col-md-6">
                                    <a href="{{ $social['url'] }}" target="_blank" class="text-decoration-none">
                                        <div
                                            class="social-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-{{ $index * 0.2 }}s hover-lift">
                                            <div class="card-body p-4 text-center">
                                                <div class="social-icon mb-3">
                                                    <div class="icon-wrapper rounded-circle p-3"
                                                        style="background: {{ $social['color'] }}">
                                                        <i class="{{ $social['icon'] }} fa-2x text-white"></i>
                                                    </div>
                                                </div>
                                                <h5 class="fw-bold mb-2 text-white">{{ $social['platform'] }}</h5>
                                                <p class="text-white-50 mb-0">{{ $social['username'] }}</p>
                                                <div class="mt-3">
                                                    <span class="btn btn-sm btn-outline-light">زيارة الصفحة</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
                        <i class="fas fa-map-marked-alt me-2"></i>موقعنا على الخريطة
                    </span>
                    <h2 class="fw-bold mb-3">زيارة المعرض</h2>
                    <p class="text-black">خميس مشيط - حي المعارض - أمام معارض السيارات الرئيسية</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="map-container rounded-4 overflow-hidden shadow-lg animate__animated animate__fadeInUp">
                        <!-- يمكنك إضافة خريطة جوجل هنا -->
                        <div class="map-placeholder bg-gradient-primary text-black d-flex align-items-center justify-content-center"
                            style="height: 250px;">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-4x mb-3"></i>
                                <h4 class="mb-2">خميس مشيط - حي المعارض</h4>
                                <p class="mb-0">شارع الملك فهد، بجوار معارض السيارات</p>
                            </div>
                        </div>

                        <!-- كود خريطة جوجل الحقيقي (إزالة التعليق وإضافة API Key الخاص بك) -->

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2871.0417734754797!2d42.67557893079623!3d18.28504820735192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15fb5763deefe445%3A0xcc297b1b953a5c7f!2z2LTYsdmD2Kkg2KfZhNi52LHYqNipINin2YTZgdix2YrYr9ipINmE2YTYs9mK2KfYsdin2Ko!5e0!3m2!1sar!2ssa!4v1769243371558!5m2!1sar!2ssa"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Contact Form -->
    <section class="contact-form-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form-card card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-gradient-primary p-5 text-white d-flex align-items-center">
                                <div>
                                    <h3 class="fw-bold mb-3">رسالة سريعة</h3>
                                    <p class="mb-4 opacity-75">أرسل لنا رسالة وسنتواصل معك في أسرع وقت</p>
                                    <div class="contact-info">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-envelope me-3 fs-5"></i>
                                            <span>info@alarabah.com</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-phone me-3 fs-5"></i>
                                            <span>800-123-4567</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock me-3 fs-5"></i>
                                            <span>من الأحد إلى الخميس 8 ص - 10 م</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 bg-white p-5">
                                <form id="contactForm">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label text-black">الاسم الكامل</label>
                                                <input type="text" class="form-control" placeholder="أدخل اسمك"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label text-black">رقم الجوال</label>
                                                <input type="tel" class="form-control" placeholder="05X XXX XXXX"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label text-black">البريد الإلكتروني</label>
                                                <input type="email" class="form-control"
                                                    placeholder="example@email.com" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label text-black">الرسالة</label>
                                                <textarea class="form-control" rows="4" placeholder="اكتب رسالتك هنا..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-gradient-primary w-100 py-3">
                                                <i class="fas fa-paper-plane me-2"></i>إرسال الرسالة
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating WhatsApp Button -->
    <div class="floating-buttons">
        <a href="https://wa.me/966530577333" target="_blank"
            class="floating-btn whatsapp-btn animate__animated animate__bounceIn animate__delay-3s">
            <i class="fab fa-whatsapp"></i>
            <span class="tooltip">واتساب مباشر</span>
        </a>

        <a href="tel:8001234567" class="floating-btn phone-btn animate__animated animate__bounceIn animate__delay-4s">
            <i class="fas fa-phone"></i>
            <span class="tooltip">اتصل الآن</span>
        </a>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --gradient-success: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
            --gradient-warning: linear-gradient(135deg, #ffd166 0%, #ff9e00 100%);
            --gradient-danger: linear-gradient(135deg, #ef476f 0%, #ff0054 100%);
            --gradient-info: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
        }

        /* Hero Section */
        .contact-hero-section {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            color: white;
            min-height: 70vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Info Cards */
        .info-card {
            transition: all 0.3s ease;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .info-icon .icon-wrapper {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation-duration: 2s;
        }

        /* Sales Cards */
        .sales-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sales-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .sales-icon .icon-wrapper {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .phone-number a:hover h4 {
            text-decoration: underline;
        }

        .btn-call {
            transition: all 0.3s ease;
        }

        .btn-call:hover {
            transform: scale(1.05);
        }

        /* Social Media Cards */
        .social-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .social-icon .icon-wrapper {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .social-card:hover .icon-wrapper {
            transform: rotate(360deg);
        }

        .hover-lift:hover {
            transform: translateY(-10px);
        }

        /* Map Section */
        .map-container {
            transition: all 0.3s ease;
        }

        .map-container:hover {
            transform: translateY(-5px);
        }

        .map-placeholder {
            animation: gradientShift 3s ease infinite;
            background-size: 400% 400%;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Contact Form */
        .contact-form-card {
            transition: all 0.3s ease;
        }

        .contact-form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1) !important;
        }

        /* Floating Buttons */
        .floating-buttons {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
        }

        .floating-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 15px;
        }

        .whatsapp-btn {
            background: #25D366;
        }

        .phone-btn {
            background: var(--primary-color);
        }

        .floating-btn:hover {
            transform: scale(1.1);
            color: white;
        }

        .floating-btn .tooltip {
            position: absolute;
            right: 70px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .floating-btn:hover .tooltip {
            opacity: 1;
            visibility: visible;
            right: 75px;
        }

        /* Animations */
        .animate__pulse {
            animation-duration: 2s;
        }

        .animate__delay-0\.2s {
            animation-delay: 0.2s;
        }

        .animate__delay-0\.4s {
            animation-delay: 0.4s;
        }

        .animate__delay-0\.6s {
            animation-delay: 0.6s;
        }

        .animate__delay-0\.8s {
            animation-delay: 0.8s;
        }

        .animate__delay-1s {
            animation-delay: 1s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-hero-section {
                min-height: 50vh;
                padding: 100px 0 50px;
            }

            .display-4 {
                font-size: 2.5rem;
            }

            .floating-buttons {
                bottom: 20px;
                left: 20px;
            }

            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .floating-btn .tooltip {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .display-4 {
                font-size: 2rem;
            }

            .contact-form-card .row.g-0 {
                flex-direction: column;
            }

            .contact-form-card .col-lg-5,
            .contact-form-card .col-lg-7 {
                width: 100%;
            }
        }

        /* Typography Enhancements */
        .text-gradient-primary {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
          color: white !important;
            /* -webkit-background-clip: text; */
            /* -webkit-text-fill-color: transparent; */
            /* background-clip: text; */
        }

        /* Background Patterns */
        .contact-info-section {
            background: linear-gradient(135deg, #0c2461 0%, #1e3799 100%);
        }

        .contact-form-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Form Styling */
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Button Styling */
        .btn-gradient-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }

        /* Card Hover Effects */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تهيئة تأثيرات الجسيمات
            if (document.getElementById('particles-js')) {
                particlesJS('particles-js', {
                    particles: {
                        number: {
                            value: 60,
                            density: {
                                enable: true,
                                value_area: 800
                            }
                        },
                        color: {
                            value: "#ffffff"
                        },
                        shape: {
                            type: "circle"
                        },
                        opacity: {
                            value: 0.3,
                            random: true
                        },
                        size: {
                            value: 3,
                            random: true
                        },
                        line_linked: {
                            enable: true,
                            distance: 150,
                            color: "#ffffff",
                            opacity: 0.1,
                            width: 1
                        },
                        move: {
                            enable: true,
                            speed: 1.5,
                            direction: "none",
                            random: true,
                            straight: false,
                            out_mode: "out"
                        }
                    },
                    interactivity: {
                        detect_on: "canvas",
                        events: {
                            onhover: {
                                enable: true,
                                mode: "repulse"
                            }
                        }
                    }
                });
            }

            // تأثيرات للكروت عند التمرير
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__fadeInUp');
                    }
                });
            }, observerOptions);

            // مراقبة جميع الكروت
            document.querySelectorAll('.info-card, .sales-card, .social-card, .map-container').forEach(card => {
                observer.observe(card);
            });

            // إرسال نموذج الاتصال
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // هنا يمكنك إضافة كود إرسال النموذج إلى السيرفر
                    alert('شكراً لك! تم استلام رسالتك وسنتواصل معك قريباً.');
                    contactForm.reset();
                });
            }

            // تأثيرات للزر العائم عند التمرير
            window.addEventListener('scroll', function() {
                const floatingBtns = document.querySelectorAll('.floating-btn');
                floatingBtns.forEach(btn => {
                    if (window.scrollY > 100) {
                        btn.classList.remove('animate__bounceIn');
                        btn.style.opacity = '1';
                    } else {
                        btn.style.opacity = '0.8';
                    }
                });
            });

            // تأثيرات للارقام عند المرور عليها
            const phoneNumbers = document.querySelectorAll('.phone-number a');
            phoneNumbers.forEach(number => {
                number.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });

                number.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endpush
