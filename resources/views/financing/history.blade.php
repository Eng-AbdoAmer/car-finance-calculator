<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سجل طلبات تمويل السيارات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }

        .history-container {
            max-width: 1400px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            font-weight: 700;
        }

        .btn-action {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn-action:hover {
            background: linear-gradient(135deg, #2980b9, #1c5a7a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-new {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table-custom thead th {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-sold {
            background-color: #d4edda;
            color: #155724;
        }

        .status-follow_up {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-not_sold {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .action-buttons .btn {
            padding: 5px 12px;
            margin: 2px;
            font-size: 0.9rem;
        }

        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .search-filter {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 2px solid #e1e8ed;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Modal Custom Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .status-option {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .status-option:hover {
            background-color: #f8f9fa;
        }

        .status-option.selected {
            border-color: #3498db;
            background-color: #e3f2fd;
        }

        .status-option.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-option.sold {
            background-color: #d4edda;
            color: #155724;
        }

        .status-option.follow_up {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-option.not_sold {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-option.cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-option i {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .history-container {
                margin: 20px;
                padding: 20px;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
      <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
       <script src="{{ asset('js/navbar.js') }}"></script>
</head>
<body>
    <x-navbar />
    <div class="container mt-4">
        <div class="history-container">
            <!-- رأس الصفحة -->
            <div class="header">
                <h1><i class="fas fa-history"></i> سجل طلبات تمويل السيارات</h1>
                 <h1>البنوك المتعددة</h1>
                <p class="text-muted">عرض جميع طلبات تمويل السيارات الخاصة بك</p>
                <div class="mt-3">
                    <a href="{{ route('financing.index') }}" class="btn btn-action btn-new">
                        <i class="fas fa-calculator"></i> طلب جديد
                    </a>
                </div>
            </div>

            <!-- فلترة البحث -->
            <div class="search-filter">
                <form method="GET" action="{{ route('financing.history') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>تم البيع</option>
                            <option value="follow_up" {{ request('status') == 'follow_up' ? 'selected' : '' }}>متابعة</option>
                            <option value="not_sold" {{ request('status') == 'not_sold' ? 'selected' : '' }}>لم يتم البيع</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-action w-100">
                            <i class="fas fa-filter"></i> تصفية
                        </button>
                    </div>
                </form>
            </div>

            <!-- جدول الطلبات -->
            @if ($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>البنك</th>
                                <th>السيارة</th>
                                <th>السعر</th>
                                <th>القسط الشهري</th>
                                <th>المدة</th>
                                <th>الحالة</th>
                                <th>الهاتف</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $request->bank->name ?? 'غير محدد' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $request->brand->name ?? 'غير محدد' }}</div>
                                        <small class="text-muted">{{ $request->model->model_year ?? 'غير محدد' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ number_format(floatval($request->car_price), 0) }}</span>
                                        <div class="text-muted small">ريال</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ number_format(floatval($request->monthly_installment), 0) }}</span>
                                        <div class="text-muted small">ريال/شهر</div>
                                    </td>
                                    <td>{{ $request->duration_months ?? 0 }} شهر</td>
                                    <td id="status-cell-{{ $request->id }}">
                                        @if ($request->status == 'pending')
                                            <span class="status-badge status-pending">قيد الانتظار</span>
                                        @elseif($request->status == 'sold')
                                            <span class="status-badge status-sold">تم البيع</span>
                                        @elseif($request->status == 'follow_up')
                                            <span class="status-badge status-follow_up">متابعة</span>
                                        @elseif($request->status == 'not_sold')
                                            <span class="status-badge status-not_sold">لم يتم البيع</span>
                                        @elseif($request->status == 'cancelled')
                                            <span class="status-badge status-cancelled">ملغي</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->phone }}</td>
                                    <td>
                                        <div>{{ $request->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('financing.result.view', $request->id) }}" 
                                           class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- زر تعديل الحالة -->
                                        <button type="button" class="btn btn-sm btn-warning edit-status-btn"
                                            title="تعديل الحالة" data-id="{{ $request->id }}"
                                            data-current-status="{{ $request->status }}"
                                            data-bank="{{ $request->bank->name ?? 'غير محدد' }}"
                                            data-car-brand="{{ $request->brand->name ?? 'غير محدد' }}"
                                            data-car-model="{{ $request->model->model_year ?? 'غير محدد' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('financing.delete', $request->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        {{ $requests->links() }}
                    </nav>
                </div>

                <!-- إحصائيات -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            إجمالي النتائج: <strong>{{ $requests->total() }}</strong> طلب |
                            الصفحة {{ $requests->currentPage() }} من {{ $requests->lastPage() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-file-invoice"></i>
                    <h4>لا توجد طلبات حتى الآن</h4>
                    <p class="text-muted">ابدأ بطلب جديد لرؤية سجل الطلبات هنا</p>
                    <a href="{{ route('financing.index') }}" class="btn btn-action btn-new mt-3">
                        <i class="fas fa-calculator"></i> إنشاء طلب جديد
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal لتعديل الحالة -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">
                        <i class="fas fa-edit"></i> تحديث حالة الطلب
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="calculation_id" name="calculation_id">
                        
                        <div class="mb-3">
                            <p class="mb-1"><strong>البنك:</strong> <span id="modalBankInfo"></span></p>
                            <p class="mb-1"><strong>السيارة:</strong> <span id="modalCarInfo"></span></p>
                            <p class="mb-0"><strong>الحالة الحالية:</strong> <span id="modalCurrentStatus"></span></p>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">اختر الحالة الجديدة:</label>
                            <div class="status-options">
                                <div class="status-option pending" data-value="pending">
                                    <i class="fas fa-clock"></i> قيد الانتظار
                                </div>
                                <div class="status-option sold" data-value="sold">
                                    <i class="fas fa-check-circle"></i> تم البيع
                                </div>
                                <div class="status-option follow_up" data-value="follow_up">
                                    <i class="fas fa-phone"></i> متابعة
                                </div>
                                <div class="status-option not_sold" data-value="not_sold">
                                    <i class="fas fa-times-circle"></i> لم يتم البيع
                                </div>
                                <div class="status-option cancelled" data-value="cancelled">
                                    <i class="fas fa-ban"></i> ملغي
                                </div>
                            </div>
                            <input type="hidden" id="selected_status" name="status" value="" required>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">ملاحظات (اختياري):</label>
                            <textarea id="notes" name="notes" class="form-control" rows="2"
                                placeholder="أضف ملاحظات عن سبب تغيير الحالة..."></textarea>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast للإشعارات -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">تم التحديث</strong>
                <small>الآن</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                تم تحديث الحالة بنجاح
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-status-btn');
            const editModal = new bootstrap.Modal(document.getElementById('editStatusModal'));
            const statusOptions = document.querySelectorAll('.status-option');
            const statusInput = document.getElementById('selected_status');
            const updateForm = document.getElementById('updateStatusForm');
            const toastEl = document.getElementById('statusToast');
            const toast = new bootstrap.Toast(toastEl);
            let currentRequestId = null;

            // تعريف خيارات الحالة
            const statusLabels = {
                'pending': 'قيد الانتظار',
                'sold': 'تم البيع',
                'follow_up': 'متابعة',
                'not_sold': 'لم يتم البيع',
                'cancelled': 'ملغي'
            };

            // إعداد التواريخ الافتراضية للفلترة
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');

            if (!startDate.value) {
                const oneMonthAgo = new Date();
                oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
                startDate.value = oneMonthAgo.toISOString().split('T')[0];
            }

            if (!endDate.value) {
                endDate.value = new Date().toISOString().split('T')[0];
            }

            // فتح Modal لتعديل الحالة
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    const bank = this.getAttribute('data-bank');
                    const carBrand = this.getAttribute('data-car-brand');
                    const carModel = this.getAttribute('data-car-model');
                    
                    currentRequestId = id;
                    
                    // تعبئة بيانات الـ Modal
                    document.getElementById('calculation_id').value = id;
                    document.getElementById('modalBankInfo').textContent = bank;
                    document.getElementById('modalCarInfo').textContent = `${carBrand} (${carModel})`;
                    document.getElementById('modalCurrentStatus').textContent = statusLabels[currentStatus];
                    
                    // تعيين الحالة المختارة
                    statusInput.value = currentStatus;
                    
                    // إزالة التحديد من جميع الخيارات
                    statusOptions.forEach(option => {
                        option.classList.remove('selected');
                        if (option.getAttribute('data-value') === currentStatus) {
                            option.classList.add('selected');
                        }
                    });
                    
                    // فتح الـ Modal
                    editModal.show();
                });
            });

            // اختيار حالة جديدة
            statusOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    
                    // إزالة التحديد من جميع الخيارات
                    statusOptions.forEach(opt => opt.classList.remove('selected'));
                    
                    // تحديد الخيار الحالي
                    this.classList.add('selected');
                    statusInput.value = value;
                });
            });

            // إرسال النموذج لتحديث الحالة
            updateForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const calculationId = formData.get('calculation_id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                try {
                    const response = await fetch(`/financing/${calculationId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // تحديث الحالة في الجدول
                        updateStatusInTable(calculationId, result.data.status, result.data.status_text);
                        
                        // إظهار رسالة النجاح
                        document.getElementById('toastMessage').textContent = result.message;
                        toast.show();
                        
                        // إغلاق الـ Modal بعد ثانية
                        setTimeout(() => {
                            editModal.hide();
                        }, 1500);
                        
                    } else {
                        alert('حدث خطأ: ' + (result.message || 'يرجى المحاولة مرة أخرى'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال بالخادم: ' + error.message);
                }
            });

            // دالة لتحديث الحالة في الجدول
            function updateStatusInTable(id, newStatus, statusText) {
                const statusCell = document.getElementById(`status-cell-${id}`);
                if (statusCell) {
                    // إزالة جميع كلاسات الحالة
                    statusCell.innerHTML = '';
                    
                    // إضافة الحالة الجديدة
                    let badgeClass = '';
                    let badgeText = '';
                    
                    switch (newStatus) {
                        case 'pending':
                            badgeClass = 'status-pending';
                            badgeText = 'قيد الانتظار';
                            break;
                        case 'sold':
                            badgeClass = 'status-sold';
                            badgeText = 'تم البيع';
                            break;
                        case 'follow_up':
                            badgeClass = 'status-follow_up';
                            badgeText = 'متابعة';
                            break;
                        case 'not_sold':
                            badgeClass = 'status-not_sold';
                            badgeText = 'لم يتم البيع';
                            break;
                        case 'cancelled':
                            badgeClass = 'status-cancelled';
                            badgeText = 'ملغي';
                            break;
                    }
                    
                    // استخدام النص المرسل من السيرفر
                    if (statusText) {
                        badgeText = statusText;
                    }
                    
                    const newBadge = document.createElement('span');
                    newBadge.className = `status-badge ${badgeClass}`;
                    newBadge.textContent = badgeText;
                    statusCell.appendChild(newBadge);
                }
            }

            // إضافة CSRF token للرؤوس
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                fetch.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
        });
    </script>
</body>
</html>