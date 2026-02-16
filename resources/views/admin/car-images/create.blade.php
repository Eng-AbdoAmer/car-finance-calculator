@extends('layouts.admin')

@section('title', 'إضافة صور جديدة')

@section('page-title', 'إضافة صور جديدة')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.car-images.index') }}">صور السيارات</a>
    </li>
    <li class="breadcrumb-item active">إضافة صور</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-upload me-2"></i>رفع صور جديدة
                        </h5>
                        <a href="{{ route('admin.car-images.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.car-images.store') }}" method="POST" enctype="multipart/form-data"
                        id="uploadForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="car_id" class="form-label">السيارة <span
                                            class="text-danger">*</span></label>
                                    <select name="car_id" id="car_id"
                                        class="form-select @error('car_id') is-invalid @enderror" required>
                                        <option value="">اختر السيارة</option>
                                        @foreach ($cars as $car)
                                            <option value="{{ $car->id }}"
                                                {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                                @if ($car->brand)
                                                    {{ $car->brand->name }}
                                                    @if ($car->model)
                                                        - {{ $car->model->name }}
                                                    @endif
                                                @else
                                                    السيارة #{{ $car->id }}
                                                @endif
                                                @if ($car->model_year)
                                                    ({{ $car->model_year }})
                                                @endif
                                                - {{ $car->code ?? 'بدون كود' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('car_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image_type" class="form-label">نوع الصورة <span
                                            class="text-danger">*</span></label>
                                    <select name="image_type" id="image_type"
                                        class="form-select @error('image_type') is-invalid @enderror" required>
                                        <option value="">اختر نوع الصورة</option>
                                        <option value="exterior" {{ old('image_type') == 'exterior' ? 'selected' : '' }}>
                                            خارجي</option>
                                        <option value="interior" {{ old('image_type') == 'interior' ? 'selected' : '' }}>
                                            داخلي</option>
                                        <option value="engine" {{ old('image_type') == 'engine' ? 'selected' : '' }}>محرك
                                        </option>
                                        <option value="document" {{ old('image_type') == 'document' ? 'selected' : '' }}>
                                            مستند</option>
                                    </select>
                                    @error('image_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف الصورة</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="3" placeholder="أدخل وصفاً للصورة...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- منطقة السحب والإفلات -->
                        <div class="mb-4">
                            <label class="form-label">رفع الصور <span class="text-danger">*</span></label>

                            <div class="upload-area @error('image_path') border-danger @enderror" id="dropArea"
                                onclick="document.getElementById('fileInput').click()">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h5>اسحب وأفلت الصور هنا</h5>
                                    <p class="text-muted">أو انقر لاختيار الملفات</p>
                                    <p class="text-muted small">الحد الأقصى لحجم الملف: 2MB - الصيغ المسموحة: JPG, PNG, GIF,
                                        WEBP</p>
                                </div>
                                <input type="file" name="image_path" id="fileInput" class="d-none" accept="image/*"
                                    required onchange="handleFileSelect(event)">
                                @error('image_path')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- معاينة الصور -->
                            <div id="previewContainer" class="row mt-3 d-none">
                                <div class="col-12">
                                    <h6>معاينة الصور المحددة:</h6>
                                    <div id="imagePreviews" class="row"></div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الرفع -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-upload me-1"></i>رفع الصور
                            </button>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="add_more" id="add_more">
                                <label class="form-check-label" for="add_more">
                                    إضافة صور أخرى بعد الانتهاء
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 3rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: #4361ee;
            background: #f0f4ff;
        }

        .upload-content i {
            transition: transform 0.3s ease;
        }

        .upload-area:hover .upload-content i {
            transform: translateY(-5px);
        }

        .preview-image {
            position: relative;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .preview-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .preview-info {
            padding: 10px;
            background: white;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #dc3545;
            transform: scale(1.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // عناصر DOM
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreviews = document.getElementById('imagePreviews');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');

        // منع السلوك الافتراضي لمنطقة السحب
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // إضافة تأثيرات السحب
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-primary');
            dropArea.style.backgroundColor = '#f0f4ff';
        }

        function unhighlight() {
            dropArea.classList.remove('border-primary');
            dropArea.style.backgroundColor = '#f8f9fa';
        }

        // معالجة الإفلات
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // معالجة اختيار الملفات
        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            if (files.length === 0) return;

            // إظهار حاوية المعاينة
            previewContainer.classList.remove('d-none');
            imagePreviews.innerHTML = '';

            // التحقق من حجم الملفات
            let validFiles = true;
            Array.from(files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert(`الملف ${file.name} أكبر من 2MB`);
                    validFiles = false;
                    return;
                }

                if (!file.type.match('image.*')) {
                    alert(`الملف ${file.name} ليس صورة`);
                    validFiles = false;
                    return;
                }
            });

            if (!validFiles) return;

            // إنشاء معاينات للصور
            Array.from(files).forEach(file => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = createPreview(file, e.target.result);
                    imagePreviews.appendChild(preview);
                };

                reader.readAsDataURL(file);
            });
        }

        function createPreview(file, imageSrc) {
            const col = document.createElement('div');
            col.className = 'col-xl-3 col-lg-4 col-md-6';

            const previewDiv = document.createElement('div');
            previewDiv.className = 'preview-image';

            const img = document.createElement('img');
            img.src = imageSrc;
            img.alt = file.name;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                col.remove();
                if (imagePreviews.children.length === 0) {
                    previewContainer.classList.add('d-none');
                    fileInput.value = '';
                }
            };

            const infoDiv = document.createElement('div');
            infoDiv.className = 'preview-info';
            infoDiv.innerHTML = `
        <small class="d-block text-truncate">${file.name}</small>
        <small class="text-muted">${formatBytes(file.size)}</small>
    `;

            previewDiv.appendChild(img);
            previewDiv.appendChild(removeBtn);
            previewDiv.appendChild(infoDiv);
            col.appendChild(previewDiv);

            return col;
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // إرسال النموذج
        uploadForm.addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('الرجاء اختيار صورة واحدة على الأقل');
                return;
            }

            // تعطيل الزر أثناء الرفع
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الرفع...';
        });
    </script>
@endpush
