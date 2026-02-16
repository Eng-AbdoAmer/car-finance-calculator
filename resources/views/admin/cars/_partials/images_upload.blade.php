{{-- resources/views/admin/cars/_partials/images_upload.blade.php --}}
<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-images me-2"></i>صور السيارة</h6>
    </div>
    <div class="card-body">
        {{-- الصورة الرئيسية --}}
        <div class="form-group mb-4">
            <label for="main_image">الصورة الرئيسية</label>
            <input type="file" name="main_image" id="main_image"
                class="form-control @error('main_image') is-invalid @enderror"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
            @error('main_image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">الصيغ المسموحة: jpeg, png, jpg, gif, webp | الحجم الأقصى: 100MB</small>
        </div>

        {{-- الصور المتعددة --}}
        <div class="form-group">
            <label>صور إضافية</label>
            <div id="drop-zone" class="text-center p-4 border rounded"
                style="border:2px dashed #ccc; cursor:pointer; background:#f9f9f9; transition:.3s;">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                <p class="mt-2 mb-0">اسحب وأفلت الصور هنا أو انقر للاختيار</p>
                <small class="text-muted">الصيغ المسموحة: jpeg, png, jpg, gif, webp | الحد الأقصى 100MB لكل صورة</small>
                <input type="file" name="images[]" id="images" class="d-none" multiple accept="image/*"
                    enctype="multipart/form-data">
            </div>
            @error('images')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('images.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            {{-- معاينة --}}
            <div id="preview-container" class="row mt-3"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            const dropZone = $('#drop-zone');
            const fileInput = $('#images');
            const previewContainer = $('#preview-container');
            let selectedFiles = new DataTransfer();

            // منع السلوك الافتراضي
            $(document).on('dragover drop dragenter dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            dropZone.on('click', function() {
                fileInput.click();
            });

            dropZone.on('dragover', function() {
                dropZone.css({
                    'border-color': '#28a745',
                    'background-color': '#f0fff0'
                });
            });
            dropZone.on('dragleave drop', function() {
                dropZone.css({
                    'border-color': '#ccc',
                    'background-color': '#f9f9f9'
                });
            });

            dropZone.on('drop', function(e) {
                let files = e.originalEvent.dataTransfer.files;
                for (let i = 0; i < files.length; i++) selectedFiles.items.add(files[i]);
                fileInput[0].files = selectedFiles.files;
                previewImages(selectedFiles.files);
            });

            fileInput.on('change', function() {
                let files = this.files;
                for (let i = 0; i < files.length; i++) selectedFiles.items.add(files[i]);
                this.files = selectedFiles.files;
                previewImages(selectedFiles.files);
            });

            function previewImages(files) {
                previewContainer.empty();
                Array.from(files).forEach((file, index) => {
                    if (file.size > 100 * 1024 * 1024) {
                        previewContainer.append(`
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-danger">
                            <div class="card-body text-center text-danger p-2">
                                <p class="small mb-1">${file.name}</p>
                                <small>حجم كبير (${(file.size/1024/1024).toFixed(2)} MB)</small>
                            </div>
                        </div>
                    </div>
                `);
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.append(`
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height:120px; object-fit:cover;">
                            <div class="card-body p-2 text-center">
                                <p class="small text-truncate mb-1">${file.name}</p>
                                <small class="text-muted">${(file.size/1024/1024).toFixed(2)} MB</small>
                            </div>
                        </div>
                    </div>
                `);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
