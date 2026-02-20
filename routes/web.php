<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FinanceCalculatorController;
use App\Http\Controllers\CarFinancingController;
use App\Http\Controllers\CashSaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\CarBrandController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CalculationAdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarTrimController;
use App\Http\Controllers\Admin\CarCategoryController;
use App\Http\Controllers\Admin\CarStatusController;
use App\Http\Controllers\Admin\TransmissionTypeController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\DriveTypeController;
use App\Http\Controllers\Admin\CarImageController;
use App\Http\Controllers\Admin\CarTypeController;
use App\Http\Controllers\Admin\CarFinancingRequestController;
// ========== الصفحة الرئيسية (/) هي صفحة تسجيل الدخول ==========
Route::get('/login', function () {
    return redirect()->route('login');
})->name('login');

// ========== مسارات المصادقة ==========
Auth::routes();

// ========== المسارات العامة ==========
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
// ========== المسارات المحمية (تتطلب تسجيل دخول) ==========
Route::middleware('auth')->group(function () {
    
    // بنك الراجحي طرق حاسبة التمويل
    Route::prefix('finance')->group(function () {
        Route::get('/calculator', [FinanceCalculatorController::class, 'showCalculator'])->name('finance.calculator');
        Route::post('/calculate', [FinanceCalculatorController::class, 'calculate'])->name('finance.calculate');
        Route::get('/history', [FinanceCalculatorController::class, 'history'])->name('finance.history');
        Route::get('/show/{id}', [FinanceCalculatorController::class, 'show'])->name('finance.show');
        Route::delete('/{id}', [FinanceCalculatorController::class, 'destroy'])
            ->name('finance.delete');
        
        Route::post('/{id}/update-status', [FinanceCalculatorController::class, 'updateStatus'])
            ->name('finance.updateStatus');
        
        Route::get('/{id}/status', [FinanceCalculatorController::class, 'getStatus'])
            ->name('finance.getStatus');
        Route::get('/send-whatsapp/{id}', [FinanceCalculatorController::class, 'sendWhatsAppMessage'])->name('finance.send.whatsapp');
    });
    
    // حساب التمويل للبنوك المتعددة
    Route::prefix('financing')->group(function () {
        Route::get('/', [CarFinancingController::class, 'index'])->name('financing.index');
        Route::post('/calculate', [CarFinancingController::class, 'calculate'])->name('financing.calculate');
        Route::get('/history', [CarFinancingController::class, 'history'])->name('financing.history');
        Route::get('/show/{id}', [CarFinancingController::class, 'show'])->name('financing.show');
        Route::get('/result/{id}', [CarFinancingController::class, 'showResult'])->name('financing.result.view');
        Route::get('/send-whatsapp/{id}', [CarFinancingController::class, 'sendWhatsAppMessage'])->name('financing.send.whatsapp');
        Route::get('/models/{brandId}', [CarFinancingController::class, 'getModels']);
        Route::get('/bank-rates/{bankId}', [CarFinancingController::class, 'getBankRates']);
        
        // تحديث الحالة
        Route::post('/{id}/update-status', [CarFinancingController::class, 'updateStatus'])
            ->name('financing.update.status');
        
        // الحصول على بيانات الحالة
        Route::get('/{id}/status', [CarFinancingController::class, 'getStatus'])
            ->name('financing.get.status');
        
        // حذف الطلب
        Route::delete('/{id}', [CarFinancingController::class, 'destroy'])
            ->name('financing.delete');
    });
});

// ========== مسارات API ==========
Route::get('/api/car-models/{brand}', [FinanceCalculatorController::class, 'getCarModelsByBrand'])
    ->name('api.car-models.by-brand');


// مبيعات الكاش
Route::prefix('cash-sales')->name('cash-sales.')->middleware('auth')->group(function () {
    Route::get('/', [CashSaleController::class, 'index'])->name('index');
    Route::get('/create', [CashSaleController::class, 'create'])->name('create');
    Route::post('/', [CashSaleController::class, 'store'])->name('store');
    Route::get('/{id}', [CashSaleController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [CashSaleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CashSaleController::class, 'update'])->name('update');
    Route::delete('/{id}', [CashSaleController::class, 'destroy'])->name('destroy');
      Route::post('/{id}/add-payment', [CashSaleController::class, 'addPayment'])->name('add-payment');
    Route::delete('/{id}/payment/{paymentId}', [CashSaleController::class, 'deletePayment'])->name('delete-payment');
    Route::post('/{id}/update-status', [CashSaleController::class, 'updateStatus'])->name('update-status');
    Route::get('/export', [CashSaleController::class, 'export'])->name('export');
}); 
Route::get('/api/car-models/{brandId}', [CashSaleController::class, 'getModelsByBrand']);

// routes/web.php


Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
    });
});






// لوحة التحكم للمسؤولين
// لوحة التحكم للمسؤولين
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    
    // API للإحصائيات
    Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
    Route::get('/charts', [DashboardController::class, 'getChartsData'])->name('charts');
    
    // إدارة المبيعات النقدية
    Route::prefix('cash-sales')->name('cash-sales.')->group(function () {
        Route::get('/', [CashSaleController::class, 'index'])->name('index');
        Route::get('/create', [CashSaleController::class, 'create'])->name('create');
        Route::post('/', [CashSaleController::class, 'store'])->name('store');
        Route::get('/{id}', [CashSaleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CashSaleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CashSaleController::class, 'update'])->name('update');
        Route::delete('/{id}', [CashSaleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/add-payment', [CashSaleController::class, 'addPayment'])->name('add-payment');
        Route::post('/{id}/change-status', [CashSaleController::class, 'changeStatus'])->name('change-status');
        Route::get('/{id}/print', [CashSaleController::class, 'print'])->name('print');
    });
    
    // // إدارة طلبات التمويل
    // Route::prefix('financing-requests')->name('financing-requests.')->group(function () {
    //     Route::get('/', [CarFinancingRequestController::class, 'index'])->name('index');
    //     Route::get('/create', [CarFinancingRequestController::class, 'create'])->name('create');
    //     Route::post('/', [CarFinancingRequestController::class, 'store'])->name('store');
    //     Route::get('/{id}', [CarFinancingRequestController::class, 'show'])->name('show');
    //     Route::get('/{id}/edit', [CarFinancingRequestController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [CarFinancingRequestController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [CarFinancingRequestController::class, 'destroy'])->name('destroy');
    //     Route::post('/{id}/change-status', [CarFinancingRequestController::class, 'changeStatus'])->name('change-status');
    //     Route::post('/{id}/calculate', [CarFinancingRequestController::class, 'calculate'])->name('calculate');
    // });
    
    // إدارة الحسابات التمويلية
    // Route::prefix('finance-calculations')->name('finance-calculations.')->group(function () {
    //     Route::get('/', [FinanceCalculationController::class, 'index'])->name('index');
    //     Route::get('/create', [FinanceCalculationController::class, 'create'])->name('create');
    //     Route::post('/', [FinanceCalculationController::class, 'store'])->name('store');
    //     Route::get('/{id}', [FinanceCalculationController::class, 'show'])->name('show');
    //     Route::get('/{id}/edit', [FinanceCalculationController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [FinanceCalculationController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [FinanceCalculationController::class, 'destroy'])->name('destroy');
    //     Route::get('/{id}/installments', [FinanceCalculationController::class, 'installments'])->name('installments');
    //     Route::get('/{id}/print', [FinanceCalculationController::class, 'print'])->name('print');
    // });
    
  // إدارة المستخدمين
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserAdminController::class, 'index'])->name('index');
        Route::get('/create', [UserAdminController::class, 'create'])->name('create');
        Route::post('/', [UserAdminController::class, 'store'])->name('store');
        Route::get('/{id}', [UserAdminController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserAdminController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/type', [UserAdminController::class, 'updateType'])->name('update.type');
    });
    // إدارة الماركات
    Route::prefix('car-brands')->name('car-brands.')->group(function () {
        Route::get('/', [CarBrandController::class, 'index'])->name('index');
        Route::get('/create', [CarBrandController::class, 'create'])->name('create');
        Route::post('/', [CarBrandController::class, 'store'])->name('store');
        Route::get('/{id}', [CarBrandController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CarBrandController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CarBrandController::class, 'update'])->name('update');
        Route::delete('/{id}', [CarBrandController::class, 'destroy'])->name('destroy');
    });
    
    // إدارة الموديلات
   Route::prefix('car-models')->name('car-models.')->group(function () {
    Route::get('/', [CarModelController::class, 'index'])->name('index');
    Route::get('/create', [CarModelController::class, 'create'])->name('create');
    Route::post('/', [CarModelController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CarModelController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CarModelController::class, 'update'])->name('update');
    Route::delete('/{id}', [CarModelController::class, 'destroy'])->name('destroy');
    
    // إضافة سنة تلقائية
    Route::post('/add-next-year', [CarModelController::class, 'addNextYear'])->name('add-next-year');
    
    // إضافة مجموعة سنوات
    Route::post('/bulk-create', [CarModelController::class, 'bulkCreate'])->name('bulk-create');
        });
    
    // إدارة البنوك
      Route::prefix('banks')->name('banks.')->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::get('/create', [BankController::class, 'create'])->name('create');
        Route::post('/', [BankController::class, 'store'])->name('store');
        Route::get('/{id}', [BankController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BankController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BankController::class, 'update'])->name('update');
        Route::delete('/{id}', [BankController::class, 'destroy'])->name('destroy');
    });
    
    // إدارة أسعار التأمين
    // Route::prefix('insurance-rates')->name('insurance-rates.')->group(function () {
    //     Route::get('/', [InsuranceRateController::class, 'index'])->name('index');
    //     Route::get('/create', [InsuranceRateController::class, 'create'])->name('create');
    //     Route::post('/', [InsuranceRateController::class, 'store'])->name('store');
    //     Route::get('/{id}/edit', [InsuranceRateController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [InsuranceRateController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [InsuranceRateController::class, 'destroy'])->name('destroy');
    // });
    
    // التقارير والإحصائيات
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'index'])->name('index');
    //     Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
    //     Route::get('/financing', [ReportController::class, 'financingReport'])->name('financing');
    //     Route::get('/customers', [ReportController::class, 'customersReport'])->name('customers');
    //     Route::get('/financial', [ReportController::class, 'financialReport'])->name('financial');
    //     Route::post('/export', [ReportController::class, 'export'])->name('export');
    // });
    // إدارة جميع الحسابات
Route::prefix('calculations')->name('calculations.')->group(function () {
    Route::get('/', [CalculationAdminController::class, 'index'])->name('index');
    Route::get('/user/{userId}', [CalculationAdminController::class, 'getUserCalculations'])->name('user');
    Route::get('/export', [CalculationAdminController::class, 'export'])->name('export');
    Route::get('/stats', [CalculationAdminController::class, 'getStats'])->name('stats');
});

//     Route::resource('cars', CarController::class);
//    Route::get('/get-types/{brandId}', [CarController::class, 'getTypesByBrand'])->name('get-types');
//     Route::get('/get-trims/{brandId}/{typeId}', [CarController::class, 'getTrimsByType'])->name('get-trims');
    
//     // Image upload routes
//     Route::post('/upload-temp-image', [CarController::class, 'uploadTempImage'])->name('upload-temp-image');
//     Route::post('/delete-temp-image', [CarController::class, 'deleteTempImage'])->name('delete-temp-image');
    
//     // Other routes...
//     Route::post('/{id}/upload-images', [CarController::class, 'uploadImages'])->name('upload-images');
//     Route::post('/{id}/update-price', [CarController::class, 'updatePrice'])->name('update-price');
//     Route::post('/{id}/change-availability', [CarController::class, 'changeAvailability'])->name('change-availability');
//     Route::post('/{carId}/set-main-image/{imageId}', [CarController::class, 'setMainImage'])->name('set-main-image');
//     Route::delete('/{carId}/delete-image/{imageId}', [CarController::class, 'deleteImage'])->name('delete-image');
//     Route::get('/{id}/mark-as-sold', [CarController::class, 'markAsSold'])->name('mark-as-sold');
//     Route::post('/{id}/process-sale', [CarController::class, 'processSale'])->name('process-sale');
//     Route::get('/export', [CarController::class, 'export'])->name('export');
//     Route::get('/{id}/duplicate', [CarController::class, 'duplicate'])->name('duplicate');
//     Route::get('/{id}/details', [CarController::class, 'getCarDetails'])->name('details');
    //فئات السيارات
    Route::resource('car-trims', CarTrimController::class);
    //انواع السيارات
    Route::resource('car-types', CarTypeController::class);
    
    // تصنيفات السيارات
    Route::resource('car-categories', CarCategoryController::class);
    
    // حالات السيارات
    Route::resource('car-statuses', CarStatusController::class);
    
    // أنواع الجير
    Route::resource('transmission-types', TransmissionTypeController::class);
    
    // أنواع الوقود
    Route::resource('fuel-types', FuelTypeController::class);
    
    // أنواع الدفع
    Route::resource('drive-types', DriveTypeController::class);
    
    // صور السيارات
  // صور السيارات
Route::prefix('car-images')->name('car-images.')->group(function () {
    // Routes للموارد الرئيسية
    Route::get('/', [CarImageController::class, 'index'])->name('index');
    Route::get('/create', [CarImageController::class, 'create'])->name('create');
    Route::post('/', [CarImageController::class, 'store'])->name('store');
    Route::get('/{car_image}', [CarImageController::class, 'show'])->name('show');
    Route::get('/{car_image}/edit', [CarImageController::class, 'edit'])->name('edit');
    Route::put('/{car_image}', [CarImageController::class, 'update'])->name('update');
    Route::delete('/{car_image}', [CarImageController::class, 'destroy'])->name('destroy');
    
    // Route خاص لتعيين الصورة الرئيسية
    Route::post('/{car_image}/set-main', [CarImageController::class, 'setMain'])->name('set-main');
});
    // إعدادات النظام
    // Route::prefix('settings')->name('settings.')->group(function () {
    //     Route::get('/', [SettingController::class, 'index'])->name('index');
    //     Route::put('/general', [SettingController::class, 'updateGeneral'])->name('update.general');
    //     Route::put('/financial', [SettingController::class, 'updateFinancial'])->name('update.financial');
    //     Route::put('/notifications', [SettingController::class, 'updateNotifications'])->name('update.notifications');
    // });
    Route::post('/cars/{car}/mark-as-reserved', [CarController::class, 'markAsReserved'])->name('cars.mark-as-reserved');
    Route::post('/cars/{car}/mark-as-available', [CarController::class, 'markAsAvailable'])->name('cars.mark-as-available');
    Route::post('/cars/{car}/auto-release', [CarController::class, 'autoRelease'])->name('cars.auto-release');
    Route::get('/cars/stats', [CarController::class, 'getStats'])->name('cars.stats');
    Route::get('/cars/{id}/print', [CarController::class, 'print'])->name('cars.print');

     // ========== إدارة السيارات ==========
       // ===== موارد السيارات =====
    Route::resource('cars', CarController::class)->parameters(['cars' => 'car']);

    // ===== مسارات إضافية للسيارات =====
    Route::controller(CarController::class)->prefix('cars/{car}')->name('cars.')->group(function () {

        // عمليات البيع
        Route::get('mark-as-sold', 'markAsSold')->name('mark-as-sold');      // admin.cars.mark-as-sold
        Route::post('process-sale', 'processSale')->name('process-sale');    // admin.cars.process-sale

        // عمليات الحجز
        Route::post('mark-as-reserved', 'markAsReserved')->name('mark-as-reserved');   // admin.cars.mark-as-reserved
        Route::post('mark-as-available', 'markAsAvailable')->name('mark-as-available'); // admin.cars.mark-as-available

        // تحديث السعر
        Route::post('update-price', 'updatePrice')->name('update-price');    // admin.cars.update-price

        // رفع الصور
        Route::post('upload-images', 'uploadImages')->name('upload-images'); // admin.cars.upload-images

        // إدارة الصور
        Route::post('set-main-image/{image}', 'setMainImage')->name('set-main-image'); // admin.cars.set-main-image
        Route::delete('delete-image/{image}', 'deleteImage')->name('delete-image');     // admin.cars.delete-image
    });
    });
    
    Route::post('/test-post', function() {
        dd(request()->all());
    });




