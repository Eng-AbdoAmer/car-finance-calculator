{{-- resources/views/admin/cars/_partials/pricing.blade.php --}}
<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-tag me-2"></i>الأسعار والتواريخ</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="purchase_price">سعر الشراء <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                            class="form-control @error('purchase_price') is-invalid @enderror"
                            value="{{ old('purchase_price') }}" required>
                        <span class="input-group-text">ر.س</span>
                    </div>
                    @error('purchase_price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="selling_price">سعر البيع <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="selling_price" id="selling_price"
                            class="form-control @error('selling_price') is-invalid @enderror"
                            value="{{ old('selling_price') }}" required>
                        <span class="input-group-text">ر.س</span>
                    </div>
                    @error('selling_price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="purchase_date">تاريخ الشراء</label>
                    <input type="date" name="purchase_date" id="purchase_date"
                        class="form-control @error('purchase_date') is-invalid @enderror"
                        value="{{ old('purchase_date') }}">
                    @error('purchase_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="entry_date">تاريخ الدخول للمعرض <span class="text-danger">*</span></label>
                    <input type="date" name="entry_date" id="entry_date"
                        class="form-control @error('entry_date') is-invalid @enderror" value="{{ old('entry_date') }}"
                        required>
                    @error('entry_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}
        </div>
    </div>
</div>
