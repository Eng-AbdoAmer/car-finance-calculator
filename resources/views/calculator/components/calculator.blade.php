{{-- <div class="card bg-dark border-secondary mb-4">
    <div class="card-header bg-dark border-secondary">
        <div class="d-flex align-items-center">
            <i class="fas fa-sliders-h me-2 text-primary"></i>
            <h5 class="mb-0">بيانات التمويل</h5>
        </div>
        <p class="text-muted mb-0 small">أدخل القيم وسيتم التحديث تلقائياً</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="car_price" class="form-label">سعر السيارة (ريال)</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="car_price" min="0" step="1000" value="131000">
                </div>
                <div class="mb-3">
                    <label for="down_payment_percent" class="form-label">نسبة الدفعة الأولى (%)</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="down_payment_percent" min="0" max="100" step="0.1" value="0">
                </div>
                <div class="mb-3">
                    <label for="down_payment_amount" class="form-label">الدفعة الأولى (ريال)</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary" id="down_payment_amount" readonly>
                </div>
                <div class="mb-3">
                    <label for="finance_amount" class="form-label">مبلغ التمويل (ريال)</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary" id="finance_amount" readonly>
                </div>
                <div class="mb-3">
                    <label for="loan_term" class="form-label">المدة بالأشهر</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="loan_term" min="1" max="120" step="1" value="60">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="profit_margin" class="form-label">نسبة هامش الربح السنوي (%)</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="profit_margin" min="0" max="50" step="0.01" value="6.54">
                </div>
                <div class="mb-3">
                    <label for="admin_fee_percent" class="form-label">الرسوم الإدارية (%)</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="admin_fee_percent" min="0" max="10" step="0.1" value="1">
                    <div class="form-text text-muted">الحد الأقصى 5000 ريال</div>
                </div>
                <div class="mb-3">
                    <label for="admin_fee_amount" class="form-label">الرسوم الإدارية (ريال)</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary" id="admin_fee_amount" readonly>
                </div>
                <div class="mb-3">
                    <label for="final_payment_percent" class="form-label">نسبة الدفعة الأخيرة (%)</label>
                    <input type="number" class="form-control bg-dark text-light border-secondary" id="final_payment_percent" min="0" max="100" step="0.1" value="45">
                </div>
                <div class="mb-3">
                    <label for="final_payment_amount" class="form-label">الدفعة الأخيرة (ريال)</label>
                    <input type="text" class="form-control bg-dark text-light border-secondary" id="final_payment_amount" readonly>
                </div>
            </div>
        </div>

        <div class="mt-4 p-3 border border-secondary rounded bg-dark">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-check me-2 text-primary"></i>
                    <h5 class="mb-0">بيانات التأمين</h5>
                </div>
                <span class="badge bg-primary" id="car_category_badge">الفئة: -</span>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gender" class="form-label">الجنس</label>
                        <select class="form-select bg-dark text-light border-secondary" id="gender">
                            <option value="ذكر" selected>ذكر</option>
                            <option value="انثى">أنثى</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="age" class="form-label">الفئة العمرية</label>
                        <select class="form-select bg-dark text-light border-secondary" id="age">
                            <option value="18 to 24">18 إلى 24</option>
                            <option value="25 to 30" selected>25 إلى 30</option>
                            <option value="31 to 35">31 إلى 35</option>
                            <option value="36 to 40">36 إلى 40</option>
                            <option value="41 to 50">41 إلى 50</option>
                            <option value="51 to 60">51 إلى 60</option>
                            <option value="61 & above">61 فما فوق</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="car_brand" class="form-label">نوع السيارة</label>
                        <select class="form-select bg-dark text-light border-secondary" id="car_brand">
                            
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="insurance_rate" class="form-label">معدل التأمين (%)</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" id="insurance_rate" readonly>
                        <div class="form-text text-muted">يتم تحديده من الجدول حسب الجنس/العمر/فئة السيارة</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="insurance_amount" class="form-label">مبلغ التأمين (ريال)</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" id="insurance_amount" readonly>
                        <div class="form-text text-muted">محسوب بمنطق Excel: Year1..Year5 ثم CEILING حسب مدة التمويل</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}