<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - نظام حاسبة التمويل</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #e6f0ff;
            --accent-color: #ff6b35;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --success-color: #28a745;
            --border-radius: 12px;
            --box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-color);
        }
        
        .register-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .register-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .register-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a3a7a 100%);
            color: white;
            padding: 30px 25px;
            text-align: center;
            border-bottom: none;
        }
        
        .card-header h2 {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .card-header p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .card-body {
            padding: 35px 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-control {
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            padding: 14px 15px;
            font-size: 16px;
            transition: var(--transition);
            background-color: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25);
            background-color: white;
        }
        
        .input-group-text {
            background-color: #f1f5f9;
            border: 2px solid #e1e8ed;
            border-right: none;
            color: var(--primary-color);
        }
        
        .password-toggle {
            background-color: transparent;
            border: 2px solid #e1e8ed;
            border-left: none;
            cursor: pointer;
            color: #6c757d;
            transition: var(--transition);
        }
        
        .password-toggle:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--success-color) 0%, #1e7e34 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #1e7e34 0%, var(--success-color) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .password-requirements {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-right: 4px solid var(--primary-color);
        }
        
        .password-requirements h6 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
            font-size: 14px;
            color: #666;
        }
        
        .requirement i {
            font-size: 12px;
            color: #ccc;
            transition: var(--transition);
        }
        
        .requirement.valid i {
            color: var(--success-color);
        }
        
        .requirement.invalid i {
            color: #dc3545;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
            display: none;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 5px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e1e8ed;
        }
        
        .divider::before {
            margin-left: 15px;
        }
        
        .divider::after {
            margin-right: 15px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #555;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .login-link a:hover {
            color: #1a3a7a;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .phone-validation-message {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .register-container {
                padding: 10px;
            }
            
            .card-header {
                padding: 25px 20px;
            }
            
            .card-body {
                padding: 25px 20px;
            }
            
            .card-header h2 {
                font-size: 24px;
            }
        }
        
        /* Animation for form elements */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        
        /* Custom styling for validation */
        .is-valid {
            border-color: var(--success-color) !important;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .valid-feedback,
        .invalid-feedback {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="card-header">
                <h2>
                    <i class="fas fa-user-plus"></i>
                    إنشاء حساب جديد
                </h2>
                <p>انضم إلى نظام حاسبة تمويل السيارات</p>
            </div>
            
            <!-- Body -->
            <div class="card-body">
                <!-- Display Errors -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>خطأ!</strong> برجاء مراجعة البيانات المدخلة.
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="form-group mb-4">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            الاسم الكامل
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input 
                                id="name" 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autocomplete="name" 
                                autofocus
                                placeholder="أدخل اسمك الكامل"
                            >
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            البريد الإلكتروني
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                            <input 
                                id="email" 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email"
                                placeholder="مثال: example@email.com"
                            >
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Phone Field -->
                    <div class="form-group mb-4">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i>
                            رقم الهاتف
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-mobile-alt"></i>
                            </span>
                            <input 
                                id="phone" 
                                type="tel" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required 
                                autocomplete="phone"
                                placeholder="مثال: 0512345678"
                            >
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            كلمة المرور
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="أدخل كلمة مرور قوية"
                            >
                            <button class="btn password-toggle" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="passwordStrength">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <h6><i class="fas fa-info-circle"></i> متطلبات كلمة المرور</h6>
                            <div class="requirement" id="reqLength">
                                <i class="fas fa-circle"></i>
                                <span>8 أحرف على الأقل</span>
                            </div>
                            <div class="requirement" id="reqUppercase">
                                <i class="fas fa-circle"></i>
                                <span>حرف كبير واحد على الأقل</span>
                            </div>
                            <div class="requirement" id="reqLowercase">
                                <i class="fas fa-circle"></i>
                                <span>حرف صغير واحد على الأقل</span>
                            </div>
                            <div class="requirement" id="reqNumber">
                                <i class="fas fa-circle"></i>
                                <span>رقم واحد على الأقل</span>
                            </div>
                            <div class="requirement" id="reqSpecial">
                                <i class="fas fa-circle"></i>
                                <span>رمز خاص واحد على الأقل (!@#$%^&*)</span>
                            </div>
                        </div>
                        
                        @error('password')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password Field -->
                    <div class="form-group mb-4">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-lock"></i>
                            تأكيد كلمة المرور
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input 
                                id="password-confirm" 
                                type="password" 
                                class="form-control" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="أعد إدخال كلمة المرور"
                            >
                            <button class="btn password-toggle" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatchMessage" class="mt-2"></div>
                    </div>
                    
                    <!-- Terms Agreement -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            أوافق على 
                            <a href="#" class="text-primary">شروط الاستخدام</a>
                            و
                            <a href="#" class="text-primary">سياسة الخصوصية</a>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-register" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        إنشاء حساب
                    </button>
                    
                    <!-- Login Link -->
                    <div class="divider">أو</div>
                    <div class="login-link">
                        لديك حساب بالفعل؟
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('password-confirm');
            const icon = this.querySelector('i');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password-confirm');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrength = document.getElementById('passwordStrength');
        
        const requirements = {
            length: document.getElementById('reqLength'),
            uppercase: document.getElementById('reqUppercase'),
            lowercase: document.getElementById('reqLowercase'),
            number: document.getElementById('reqNumber'),
            special: document.getElementById('reqSpecial')
        };
        
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Check length
            if (password.length >= 8) {
                strength += 20;
                requirements.length.classList.add('valid');
                requirements.length.classList.remove('invalid');
            } else {
                requirements.length.classList.add('invalid');
                requirements.length.classList.remove('valid');
            }
            
            // Check uppercase letters
            if (/[A-Z]/.test(password)) {
                strength += 20;
                requirements.uppercase.classList.add('valid');
                requirements.uppercase.classList.remove('invalid');
            } else {
                requirements.uppercase.classList.add('invalid');
                requirements.uppercase.classList.remove('valid');
            }
            
            // Check lowercase letters
            if (/[a-z]/.test(password)) {
                strength += 20;
                requirements.lowercase.classList.add('valid');
                requirements.lowercase.classList.remove('invalid');
            } else {
                requirements.lowercase.classList.add('invalid');
                requirements.lowercase.classList.remove('valid');
            }
            
            // Check numbers
            if (/[0-9]/.test(password)) {
                strength += 20;
                requirements.number.classList.add('valid');
                requirements.number.classList.remove('invalid');
            } else {
                requirements.number.classList.add('invalid');
                requirements.number.classList.remove('valid');
            }
            
            // Check special characters
            if (/[!@#$%^&*]/.test(password)) {
                strength += 20;
                requirements.special.classList.add('valid');
                requirements.special.classList.remove('invalid');
            } else {
                requirements.special.classList.add('invalid');
                requirements.special.classList.remove('valid');
            }
            
            // Update strength bar
            passwordStrength.style.display = 'block';
            passwordStrengthBar.style.width = strength + '%';
            
            // Change color based on strength
            if (strength < 40) {
                passwordStrengthBar.style.backgroundColor = '#dc3545'; // Red
            } else if (strength < 80) {
                passwordStrengthBar.style.backgroundColor = '#ffc107'; // Yellow
            } else {
                passwordStrengthBar.style.backgroundColor = '#28a745'; // Green
            }
        }
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const messageElement = document.getElementById('passwordMatchMessage');
            
            if (confirmPassword === '') {
                messageElement.innerHTML = '';
                messageElement.className = '';
                return;
            }
            
            if (password === confirmPassword) {
                messageElement.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> كلمات المرور متطابقة';
                messageElement.className = 'text-success';
                confirmPasswordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.add('is-valid');
            } else {
                messageElement.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> كلمات المرور غير متطابقة';
                messageElement.className = 'text-danger';
                confirmPasswordInput.classList.remove('is-valid');
                confirmPasswordInput.classList.add('is-invalid');
            }
        }
        
        // Phone validation
        function validatePhone(phone) {
            // Accept formats: 05xxxxxxxx, 5xxxxxxxx, +9665xxxxxxxx, 9665xxxxxxxx
            const phoneRegex = /^(05\d{8}|5\d{8}|\+9665\d{8}|9665\d{8})$/;
            return phoneRegex.test(phone);
        }
        
        // Phone validation on blur
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('blur', function() {
            const phone = this.value.trim();
            const messageElement = this.nextElementSibling;
            
            if (phone === '') {
                return;
            }
            
            if (!validatePhone(phone)) {
                if (messageElement && messageElement.classList.contains('phone-validation-message')) {
                    messageElement.innerHTML = '<i class="fas fa-exclamation-triangle text-warning me-1"></i> يرجى إدخال رقم هاتف سعودي صحيح (مثال: 0512345678)';
                    messageElement.className = 'phone-validation-message text-warning';
                } else {
                    const newMessage = document.createElement('div');
                    newMessage.innerHTML = '<i class="fas fa-exclamation-triangle text-warning me-1"></i> يرجى إدخال رقم هاتف سعودي صحيح (مثال: 0512345678)';
                    newMessage.className = 'phone-validation-message text-warning mt-2';
                    this.parentNode.parentNode.appendChild(newMessage);
                }
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                if (messageElement && messageElement.classList.contains('phone-validation-message')) {
                    messageElement.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> رقم الهاتف صحيح';
                    messageElement.className = 'phone-validation-message text-success';
                }
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        // Event listeners for password validation
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        // Form validation and submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const phone = phoneInput.value.trim();
            const termsCheckbox = document.getElementById('terms');
            
            // Check if passwords match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('كلمات المرور غير متطابقة. يرجى التأكد من تطابق كلمتي المرور.');
                confirmPasswordInput.focus();
                return;
            }
            
            // Validate phone
            if (!validatePhone(phone)) {
                e.preventDefault();
                alert('يرجى إدخال رقم هاتف سعودي صحيح (مثال: 0512345678)');
                phoneInput.focus();
                return;
            }
            
            // Check if terms are accepted
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('يجب الموافقة على شروط الاستخدام وسياسة الخصوصية.');
                termsCheckbox.focus();
                return;
            }
            
            // Add loading animation
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري إنشاء الحساب...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds if form submission fails
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Auto-focus name field on page load
        document.addEventListener('DOMContentLoaded', function() {
            const nameField = document.getElementById('name');
            if (nameField && !nameField.value) {
                nameField.focus();
            }
            
            // Add animation to form on load
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.animationDelay = `${(index + 1) * 0.1}s`;
                group.style.opacity = '1';
                group.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>