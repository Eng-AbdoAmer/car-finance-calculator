<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>معلومات إضافية</h6>
    </div>
    <div class="card-body">
        {{-- الوصف --}}
        <div class="form-group mb-4">
            <label for="description">وصف السيارة <span class="text-danger">*</span></label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $car->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- ملاحظات داخلية --}}
        {{-- <div class="form-group mb-4">
            <label for="notes">ملاحظات داخلية</label>
            <textarea name="notes" id="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $car->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div> --}}

        {{-- خيارات إضافية --}}
        {{-- <div class="row">
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_featured" id="is_featured"
                        value="1" {{ old('is_featured', $car->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">سيارة مميزة</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_negotiable"
                        id="is_negotiable" value="1"
                        {{ old('is_negotiable', $car->is_negotiable ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_negotiable">قابل للتفاوض</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_financeable"
                        id="is_financeable" value="1"
                        {{ old('is_financeable', $car->is_financeable ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_financeable">تمويل متاح</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_ownership"
                        id="has_ownership" value="1"
                        {{ old('has_ownership', $car->has_ownership ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_ownership">ملكية واضحة</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_insurance"
                        id="has_insurance" value="1"
                        {{ old('has_insurance', $car->has_insurance ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_insurance">تأمين ساري</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_registration"
                        id="has_registration" value="1"
                        {{ old('has_registration', $car->has_registration ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_registration">استمارة سارية</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_maintenance_record"
                        id="has_maintenance_record" value="1"
                        {{ old('has_maintenance_record', $car->has_maintenance_record ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_maintenance_record">سجل صيانة</label>
                </div>
            </div>
        </div>
    </div>
</div> --}}
