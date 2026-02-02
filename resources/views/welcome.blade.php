@extends('layouts.app')
@section('title', ' الرئيسية')
@section('content')

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <x-navbar /> 
    <div class="container position-relative z-index-2">
        <div class="row align-items-center min-vh-90 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <div class="hero-badge mb-4 animate__animated animate__bounceIn">
                        <span class="badge bg-gradient-primary rounded-pill px-4 py-2">
                            <i class="fas fa-bolt me-2"></i>منصة متكاملة
                        </span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                        {{-- نظام <span class="text-gradient-primary">تمويل السيارات</span> الذكي --}}
                        نظام تمويل السيارات الذكي
                    </h1>
                </div>
                
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                    حلول تمويلية متكاملة مع نظام تأمين ذكي يحسب الأقساط بدقة عالية
                    ويوفر تقارير تفصيلية لمساعدتك في اتخاذ القرار المثالي
                </p>
                
                <!-- إحصائيات سريعة -->
                <div class="row g-4 mb-5 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="col-md-3 col-6">
                        <div class="stat-box">
                            <h3 class="fw-bold text-white mb-1">٩٩٪</h3>
                            <p class="small text-white-50 mb-0">دقة الحساب</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-box">
                            <h3 class="fw-bold text-white mb-1">+٥٠</h3>
                            <p class="small text-white-50 mb-0">مؤسسة بنكية</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-box">
                            <h3 class="fw-bold text-white mb-1">١٥</h3>
                            <p class="small text-white-50 mb-0">عام خبرة</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-box">
                            <h3 class="fw-bold text-white mb-1">٢٤/٧</h3>
                            <p class="small text-white-50 mb-0">دعم فني</p>
                        </div>
                    </div>
                </div>
                
                <!-- أزرار النظام -->
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <a href="{{ route('finance.calculator') }}" class="btn btn-lg btn-gradient-primary px-5 py-3 shadow-lg">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-university me-3 fs-4"></i>
                            <div class="text-start">
                                <div class="fw-bold">نظام بنك الراجحي</div>
                                <small class="">تمويل متوافق مع الشريعة</small>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('financing.index') }}" class="btn btn-lg btn-outline-light px-5 py-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-landmark me-3 fs-4"></i>
                            <div class="text-start">
                                <div class="fw-bold">نظام البنوك المتعددة</div>
                                <small class="opacity-75">مقارنة بين أفضل العروض</small>
                            </div>
                        </div>
                    </a>
                        <a href="{{ route('cash-sales.index') }}" class="btn btn-lg btn-outline-light px-5 py-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-landmark me-3 fs-4"></i>
                            <div class="text-start">
                                <div class="fw-bold">نظام الكاش</div>
                                <small class="opacity-75">أقل الاسعر للكاش</small>
                            </div>
                        </div>
                    </a>
                </div>
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

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-light text-primary rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-star me-2"></i>مميزات النظام
                </span>
                <h2 class="fw-bold mb-3 text-white">كل ما تحتاجه في منصة واحدة</h2>
                <p class="text-white lead">نوفر لك أدوات متكاملة لتسهيل عملية تمويل سيارتك</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-4">
                            <div class="icon-wrapper bg-gradient-primary rounded-circle p-3">
                                <i class="fas fa-calculator fa-2x text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">حاسبة ذكية</h4>
                        <p class="text-white mb-4">
                            حساب دقيق للأقساط الشهرية مع مراعاة جميع العوامل المالية
                            والفوائد والرسوم الإضافية
                        </p>
                        <ul class="list-unstyled mb-0 text-white">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>دقة حسابية 100%</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>تحديث لحظي للنتائج</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>مقارنة متعددة</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-4">
                            <div class="icon-wrapper bg-gradient-success rounded-circle p-3">
                                <i class="fas fa-shield-alt fa-2x text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">تأمين ذكي</h4>
                        <p class="text-white mb-4">
                            نظام تأمين متطور يحسب التكلفة بناءً على الجنس، العمر، 
                            نوع السيارة، وسجل القيادة
                        </p>
                        <ul class="list-unstyled mb-0 text-white">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>حساب حسب الملف الشخصي</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>أسعار تنافسية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تغطية شاملة</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card card border-0 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s ">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-4 ">
                            <div class="icon-wrapper bg-gradient-warning rounded-circle p-3">
                                <i class="fas fa-chart-line fa-2x text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">تقارير متقدمة</h4>
                        <p class="text-white mb-4">
                            تقارير تفصيلية وجداول أقساط كاملة مع إمكانية 
                            التصدير والطباعة والمشاركة
                        </p>
                        <ul class="list-unstyled mb-0 text-white">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>جداول تفصيلية</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>مخططات بيانية</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>تصدير PDF</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works bg-light py-5">
    <div class="container text-black">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-play-circle me-2"></i>كيف يعمل
                </span>
                <h2 class="fw-bold mb-3">خطوات سهلة للحصول على التمويل</h2>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">١</span>
                    </div>
                    <h5 class="fw-bold mb-3">اختر النظام</h5>
                    <p class="text-black">حدد بين نظام بنك الراجحي أو نظام البنوك المتعددة</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">٢</span>
                    </div>
                    <h5 class="fw-bold mb-3">أدخل البيانات</h5>
                    <p class="text-black">أدخل بيانات السيارة والمعلومات الشخصية</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">٣</span>
                    </div>
                    <h5 class="fw-bold mb-3">احصل على النتائج</h5>
                    <p class="text-black">استلم تقرير مفصل بالأقساط والتكاليف</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">٤</span>
                    </div>
                    <h5 class="fw-bold mb-3">قدم الطلب</h5>
                    <p class="text-black">قدم طلب التمويل للبنك المختار</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-card rounded-4 overflow-hidden shadow-lg">
            <div class="row g-0 ">
                <div class="col-lg-8 p-5">
                    <h2 class="fw-bold text-white mb-3">جاهز لتمويل سيارتك الجديدة؟</h2>
                    <p class="text-white mb-4 opacity-75">
                        ابدأ الآن في حساب تمويل سيارتك واحصل على أفضل العروض من البنوك السعودية
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('finance.calculator') }}" class="btn btn-light px-4">
                            <i class="fas fa-rocket me-2"></i>ابدأ الآن
                        </a>
                        <a href="#" class="btn btn-outline-light px-4">
                            <i class="fas fa-play-circle me-2"></i>شاهد فيديو توضيحي
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 bg-white p-5 d-flex align-items-center">
                    <div>
                        <h5 class="fw-bold mb-3 text-primary">تواصل معنا</h5>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <span class="text-black">٠٥٠١٢٣٤٥٦٧</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <span class="text-black">info@example.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-3"></i>
                            <span class="text-black">٢٤/٧ على مدار الأسبوع</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    color: white;
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.hero-background .shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.hero-background .shape-1 {
    width: 600px;
    height: 600px;
    background: var(--primary-color);
    top: -300px;
    right: -200px;
    animation: float 20s infinite ease-in-out;
}

.hero-background .shape-2 {
    width: 400px;
    height: 400px;
    background: #ff6b6b;
    bottom: -200px;
    left: -100px;
    animation: float 25s infinite ease-in-out reverse;
}

.hero-background .shape-3 {
    width: 300px;
    height: 300px;
    background: #4cc9f0;
    top: 50%;
    left: 10%;
    animation: float 30s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Typography */
.text-gradient-primary {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-badge .badge {
    font-size: 1.1rem;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

/* Buttons */
.btn-gradient-primary {
    background: var(--gradient-primary);
    color: white;
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    color: white;
}

.btn-gradient-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-gradient-primary:hover::before {
    left: 100%;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Feature Cards */
.feature-card {
    transition: all 0.3s ease;
    border-radius: 20px;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.feature-icon .icon-wrapper {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.feature-card:hover .icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.bg-gradient-primary {
    background: var(--gradient-primary);
}

.bg-gradient-success {
    background: var(--gradient-success);
}

.bg-gradient-warning {
    background: var(--gradient-warning);
}

/* Step Cards */
.step-card {
    background: white;
    border-radius: 15px;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.step-number span {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #283593 0%, #3949ab 100%);
}

.cta-card {
    background: var(--gradient-primary);
    color: white;
}

/* Stat Boxes */
.stat-box {
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stat-box:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-5px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: auto;
        padding: 100px 0 50px;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }
    
    .stat-box {
        padding: 10px;
    }
}

@media (max-width: 576px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Animations */
.animate__animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}

.animate__delay-1s {
    animation-delay: 0.5s;
}

.animate__delay-2s {
    animation-delay: 1s;
}

.animate__delay-3s {
    animation-delay: 1.5s;
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
</style>
@endpush

@push('scripts')
<!-- يمكن إضافة مكتبة Particles.js إذا أردت تأثيرات إضافية -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة تأثيرات الجسيمات (اختياري)
    if (document.getElementById('particles-js')) {
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#ffffff" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.2, width: 1 },
                move: { enable: true, speed: 2, direction: "none", random: true, straight: false, out_mode: "out" }
            },
            interactivity: {
                detect_on: "canvas",
                events: { onhover: { enable: true, mode: "repulse" } }
            }
        });
    }
    
    // إضافة تأثيرات للكروت عند التمرير
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
    
    // مراقبة العناصر لإضافة تأثيرات التمرير
    document.querySelectorAll('.feature-card, .step-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush