{{-- <div class="card bg-dark border-secondary">
    <div class="card-header bg-dark border-secondary">
        <div class="d-flex align-items-center">
            <i class="fas fa-shield-alt me-2 text-primary"></i>
            <h5 class="mb-0">جدول معدلات التأمين</h5>
        </div>
        <p class="text-muted mb-0 small">البيانات من ملف Excel (Insurance rate)</p>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="ins_lookup_gender" class="form-label">الجنس</label>
                    <select class="form-select bg-dark text-light border-secondary" id="ins_lookup_gender">
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="ins_lookup_age" class="form-label">الفئة العمرية</label>
                    <select class="form-select bg-dark text-light border-secondary" id="ins_lookup_age">
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
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="ins_lookup_category" class="form-label">فئة السيارة</label>
                    <select class="form-select bg-dark text-light border-secondary" id="ins_lookup_category">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C" selected>C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button id="ins_lookup_btn" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>
                    <span>بحث</span>
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border border-secondary rounded bg-dark p-3 h-100">
                    <h6 class="text-light mb-3">معدلات التأمين للذكور</h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>العمر</th>
                                    <th>الفئة</th>
                                    <th>المعدل</th>
                                </tr>
                            </thead>
                            <tbody id="male_table">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border border-secondary rounded bg-dark p-3 h-100">
                    <h6 class="text-light mb-3">معدلات التأمين للإناث</h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>العمر</th>
                                    <th>الفئة</th>
                                    <th>المعدل</th>
                                </tr>
                            </thead>
                            <tbody id="female_table">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-secondary rounded bg-dark p-3">
            <h6 class="text-light mb-3">فئات السيارات حسب الماركة</h6>
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>الماركة</th>
                            <th>الفئة</th>
                            <th>الماركة</th>
                            <th>الفئة</th>
                        </tr>
                    </thead>
                    <tbody id="brands_table">
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div id="toast" class="toast align-items-center text-white bg-primary border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
</div> --}}