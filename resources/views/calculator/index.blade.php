{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <header class="mb-4">
        <div class="card bg-dark border-secondary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-calculator fa-2x text-primary"></i>
                            </div>
                            <div class="text-white">
                                <h1 class="h3 mb-1">حاسبة التمويل الآلي</h1>
                                <p class="text-muted mb-0">Premium • RTL • مطابق لمنطق Excel في حساب التأمين</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-start">
                        <div class="d-flex gap-2 justify-content-end">
                            <button id="help_btn" class="btn btn-outline-light">
                                <i class="fas fa-question-circle"></i>
                                <span>مساعدة</span>
                            </button>
                            <button id="reset_btn" class="btn btn-outline-light">
                                <i class="fas fa-redo"></i>
                                <span>إعادة تعيين</span>
                            </button>
                            <button id="calc_btn" class="btn btn-primary">
                                <i class="fas fa-bolt"></i>
                                <span>احسب</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    
    <nav class="mb-4">
        <ul class="nav nav-tabs" id="mainTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="calculator-tab" data-bs-toggle="tab" 
                        data-bs-target="#calculator" type="button" role="tab">
                    <i class="fas fa-car me-1"></i>
                    حاسبة التمويل
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="insurance-tab" data-bs-toggle="tab" 
                        data-bs-target="#insurance" type="button" role="tab">
                    <i class="fas fa-shield-alt me-1"></i>
                    جدول التأمين
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" 
                        data-bs-target="#schedule" type="button" role="tab">
                    <i class="fas fa-table me-1"></i>
                    جدول الأقساط
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="assistant-tab" data-bs-toggle="tab" 
                        data-bs-target="#assistant" type="button" role="tab">
                    <i class="fas fa-robot me-1"></i>
                    مساعد
                </button>
            </li>
        </ul>
    </nav>

  
    <div class="tab-content" id="mainTabsContent">
        
        <div class="tab-pane fade show active" id="calculator" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    @include('calculator.components.calculator')
                </div>
                <div class="col-lg-4">
                    @include('calculator.components.results')
                </div>
            </div>
        </div>

     
        <div class="tab-pane fade" id="insurance" role="tabpanel">
            @include('calculator.components.insurance')
        </div>

     
        <div class="tab-pane fade" id="schedule" role="tabpanel">
            @include('calculator.components.schedule')
        </div>

        
        <div class="tab-pane fade" id="assistant" role="tabpanel">
            @include('calculator.components.assistant')
        </div>
    </div>
</div>


<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle text-primary me-2"></i>
                    مساعدة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="helpContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

    window.insuranceRates = @json($insuranceRates);
    window.carCategories = @json($carCategories);
    window.defaults = @json($defaults);
    
 
    window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
</script>
<script src="{{ asset('js/calculator.js') }}" defer></script>
@endpush --}}