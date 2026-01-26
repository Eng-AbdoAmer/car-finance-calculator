// ملف app.js مبسط
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة أدوات Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // تهيئة Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // تهيئة Tabs
    var tabTriggerList = [].slice.call(document.querySelectorAll('button[data-bs-toggle="tab"]'));
    tabTriggerList.forEach(function (tabTriggerEl) {
        tabTriggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            var target = document.querySelector(this.getAttribute('data-bs-target'));
            if (target) {
                // إخفاء جميع التبويبات
                document.querySelectorAll('.tab-pane').forEach(function(pane) {
                    pane.classList.remove('show', 'active');
                });
                // إزالة active من جميع الأزرار
                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                // إظهار التبويب المحدد
                target.classList.add('show', 'active');
                this.classList.add('active');
            }
        });
    });

    // تهيئة Modals
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        });
    });
});