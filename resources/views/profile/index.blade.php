@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <x-navbar />
    
    <div class="container position-relative z-index-2">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <div class="hero-badge mb-4 animate__animated animate__bounceIn">
                        <span class="badge bg-gradient-primary rounded-pill px-4 py-2">
                            <i class="fas fa-user-circle me-2"></i>الملف الشخصي
                        </span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp">
                        إدارة <span class="">الملف الشخصي</span>
                    </h1>
                </div>
                
                <p class="lead text-white mb-5 animate__animated animate__fadeInUp animate__delay-1s opacity-75">
                    يمكنك من هنا إدارة معلوماتك الشخصية وتغيير كلمة المرور
                </p>
            </div>
        </div>
    </div>
    
    <!-- خلفية متحركة -->
    <div class="hero-background">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
</section>

<!-- Main Content -->
<section class="profile-section py-5">
    <div class="container">
        <div class="row">
            <!-- معلومات المستخدم -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h5 class="mb-0 text-center">
                            <i class="fas fa-user-circle me-2"></i>معلومات الحساب
                        </h5>
                    </div>
                    <div class="card-body text-center p-4">
                        <!-- صورة الملف الشخصي -->
                        <div class="position-relative d-inline-block mb-4">
                            <img src="{{ $user->avatar_url }}" 
                                 alt="صورة الملف الشخصي" 
                                 class="rounded-circle shadow-lg border border-4 border-primary"
                                 width="150" 
                                 height="150"
                                 style="object-fit: cover;">
                            <button class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle shadow"
                                    data-bs-toggle="modal"
                                    data-bs-target="#avatarModal"
                                    style="width: 40px; height: 40px;">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        
                        <h4 class="fw-bold mb-2 text-white">{{ $user->name }}</h4>
                        <p class="text-white mb-2">
                            <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                        </p>
                        <p class="text-white mb-3">
                            <i class="fas fa-phone me-2"></i>{{ $user->phone }}
                        </p>
                        
                        @if($user->isAdmin())
                            <span class="badge bg-gradient-primary mb-3 px-3 py-2">
                                <i class="fas fa-crown me-1"></i> مدير النظام
                            </span>
                        @else
                            <span class="badge bg-gradient-success mb-3 px-3 py-2">
                                <i class="fas fa-user me-1"></i> مستخدم
                            </span>
                        @endif
                        
                        <div class="mt-4">
                            <p class="text-white mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>
                                تاريخ التسجيل: {{ $user->created_at->format('Y/m/d') }}
                            </p>
                            <p class="text-white mb-0">
                                <i class="fas fa-clock me-2"></i>
                                آخر تحديث: {{ $user->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="col-lg-8">
                <!-- عرض الرسائل -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- تعديل المعلومات الشخصية -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            تعديل المعلومات الشخصية
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-white">الاسم الكامل</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white border-primary">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control border-primary text-white"
                                               value="{{ old('name', $user->name) }}"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-white">البريد الإلكتروني</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white border-primary">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control border-primary text-white"
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-white">رقم الهاتف</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control border-primary text-white"
                                           value="{{ old('phone', $user->phone) }}"
                                           required>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>حفظ التغييرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- تغيير كلمة المرور -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient-primary text-white border-0 py-4">
                        <h5 class="mb-0">
                            <i class="fas fa-lock me-2"></i>
                            تغيير كلمة المرور
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-white">كلمة المرور الحالية</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white border-primary">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <input type="password" 
                                               name="current_password" 
                                               class="form-control border-primary text-white"
                                               required>
                                        <button type="button" class="btn btn-outline-primary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-white">كلمة المرور الجديدة</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white border-primary">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               name="new_password" 
                                               class="form-control border-primary text-white"
                                               required>
                                        <button type="button" class="btn btn-outline-primary toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-white">تأكيد كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           name="new_password_confirmation" 
                                           class="form-control border-primary"
                                           required>
                                    <button type="button" class="btn btn-outline-primary toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-key me-2"></i>تغيير كلمة المرور
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal لتغيير الصورة الشخصية -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera me-2"></i>
                    تغيير الصورة الشخصية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <div class="avatar-lg mx-auto bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-3"
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <p class="text-white mb-3">
                            الصيغ المدعومة: JPG, PNG, GIF<br>
                            الحد الأقصى للحجم: 2MB
                        </p>
                    </div>
                    
                    <div class="input-group">
                        <input type="file" 
                               name="avatar" 
                               class="form-control" 
                               accept="image/*"
                               required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-upload me-2"></i>رفع الصورة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    color: white;
    min-height: 50vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.text-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-background .shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.hero-background .shape-1 {
    width: 400px;
    height: 400px;
    background: var(--primary-color);
    top: -200px;
    right: -100px;
    animation: float 20s infinite ease-in-out;
}

.hero-background .shape-2 {
    width: 300px;
    height: 300px;
    background: #ff6b6b;
    bottom: -150px;
    left: -50px;
    animation: float 25s infinite ease-in-out reverse;
}

.hero-background .shape-3 {
    width: 200px;
    height: 200px;
    background: #4cc9f0;
    top: 30%;
    left: 10%;
    animation: float 30s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%) !important;
}

/* Card Styling */
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
}

/* Input Group */
.input-group .btn-outline-primary {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.input-group-text {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
}

/* Badge Customization */
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
    border-radius: 50px;
}

/* Image Avatar */
.rounded-circle {
    object-fit: cover;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 40vh;
        padding: 80px 0 40px;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Animation Delays */
.animate__delay-1s { animation-delay: 1s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إظهار/إخفاء كلمة المرور
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // معاينة الصورة قبل الرفع
    const avatarInput = document.querySelector('input[name="avatar"]');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'img-fluid rounded-circle';
                    preview.style.width = '100px';
                    preview.style.height = '100px';
                    preview.style.objectFit = 'cover';
                    
                    const previewContainer = document.querySelector('#avatarModal .avatar-lg');
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush