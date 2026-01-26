// الملف الكامل بعد التصحيح
class FinanceCalculator {
    constructor() {
        this.insuranceRates = window.insuranceRates || {};
        this.carCategories = window.carCategories || {};
        this.defaults = window.defaults || {};
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        this.initElements();
        this.bindEvents();
        this.initialize();
    }

    initElements() {
        // المدخلات
        this.elements = {
            car_price: document.getElementById('car_price'),
            down_payment_percent: document.getElementById('down_payment_percent'),
            down_payment_amount: document.getElementById('down_payment_amount'),
            finance_amount: document.getElementById('finance_amount'),
            loan_term: document.getElementById('loan_term'),
            profit_margin: document.getElementById('profit_margin'),
            admin_fee_percent: document.getElementById('admin_fee_percent'),
            admin_fee_amount: document.getElementById('admin_fee_amount'),
            final_payment_percent: document.getElementById('final_payment_percent'),
            final_payment_amount: document.getElementById('final_payment_amount'),
            gender: document.getElementById('gender'),
            age: document.getElementById('age'),
            car_brand: document.getElementById('car_brand'),
            insurance_rate: document.getElementById('insurance_rate'),
            insurance_amount: document.getElementById('insurance_amount'),
            car_category_badge: document.getElementById('car_category_badge'),
            
            // النتائج
            monthly_installment: document.getElementById('monthly_installment'),
            monthly_with_insurance: document.getElementById('monthly_with_insurance'),
            flat_profit_rate: document.getElementById('flat_profit_rate'),
            total_cost_percent: document.getElementById('total_cost_percent'),
            total_fees: document.getElementById('total_fees'),
            total_insurance: document.getElementById('total_insurance'),
            total_profit: document.getElementById('total_profit'),
            remaining_value: document.getElementById('remaining_value'),
            grand_total: document.getElementById('grand_total'),
            apr_rate: document.getElementById('apr_rate'),
            irr_rate: document.getElementById('irr_rate'),
            net_profit: document.getElementById('net_profit'),
            
            // الأزرار
            calc_btn: document.getElementById('calc_btn'),
            reset_btn: document.getElementById('reset_btn'),
            help_btn: document.getElementById('help_btn')
        };
    }

    bindEvents() {
        // أحداث المدخلات
        const inputIds = [
            'car_price', 'down_payment_percent', 'loan_term', 'profit_margin',
            'admin_fee_percent', 'final_payment_percent', 'gender', 'age', 'car_brand'
        ];
        
        inputIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', () => this.updateCalculations());
                el.addEventListener('change', () => this.updateCalculations());
            }
        });

        // أحداث الأزرار
        if (this.elements.calc_btn) {
            this.elements.calc_btn.addEventListener('click', () => this.calculate());
        }
        
        if (this.elements.reset_btn) {
            this.elements.reset_btn.addEventListener('click', () => this.reset());
        }
        
        if (this.elements.help_btn) {
            this.elements.help_btn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('helpModal'));
                modal.show();
            });
        }

        // زر البحث في جدول التأمين
        const insLookupBtn = document.getElementById('ins_lookup_btn');
        if (insLookupBtn) {
            insLookupBtn.addEventListener('click', () => {
                const g = document.getElementById('ins_lookup_gender').value;
                const age = document.getElementById('ins_lookup_age').value;
                const c = document.getElementById('ins_lookup_category').value;
                const r = this.insuranceRates[g]?.[age]?.[c] ?? 0;
                this.showToast(`معدل التأمين: ${this.formatPercentPlain(r * 100, 3)}`);
            });
        }

        // زر المساعد
        const assistantSend = document.getElementById('assistant_send');
        if (assistantSend) {
            assistantSend.addEventListener('click', () => this.sendToAssistant());
        }

        const assistantFill = document.getElementById('assistant_fill');
        if (assistantFill) {
            assistantFill.addEventListener('click', () => {
                document.getElementById('assistant_prompt').value = "اشرح لي كيف تم حساب مبلغ التأمين خطوة بخطوة حسب المدة (12/24/36/48/60 شهر).";
            });
        }
    }

    initialize() {
        // تعبئة قائمة الماركات
        this.populateBrands();
        
        // تعيين القيم الافتراضية
        this.setDefaults();
        
        // حساب أولي
        this.updateCalculations();

        // تعبئة جداول التأمين
        this.populateInsuranceTables();
    }

    populateBrands() {
        const select = this.elements.car_brand;
        if (!select) return;
        
        const brands = Object.keys(this.carCategories).sort((a, b) => a.localeCompare(b));
        select.innerHTML = brands.map(brand => 
            `<option value="${brand}">${brand}</option>`
        ).join('');
        
        if (brands.includes('Toyota')) {
            select.value = 'Toyota';
        }
    }

    setDefaults() {
        Object.keys(this.defaults).forEach(key => {
            const el = this.elements[key];
            if (el) {
                el.value = this.defaults[key];
            }
        });
    }

    reset() {
        this.setDefaults();
        this.updateCalculations();
        this.showToast('تم إعادة التعيين إلى القيم الافتراضية');
    }

    async updateCalculations() {
        try {
            // تحديث القيم المحسوبة على الفور
            this.updateDerivedValues();
            
            // إرسال طلب للحساب الكامل
            await this.calculate();
        } catch (error) {
            console.error('خطأ في الحساب:', error);
            this.showToast('حدث خطأ في التحديث', 'error');
        }
    }

    updateDerivedValues() {
        // حساب القيم المشتقة
        const carPrice = parseFloat(this.elements.car_price.value) || 0;
        const downPaymentPercent = parseFloat(this.elements.down_payment_percent.value) || 0;
        const adminFeePercent = parseFloat(this.elements.admin_fee_percent.value) || 0;
        const finalPaymentPercent = parseFloat(this.elements.final_payment_percent.value) || 0;

        const downPaymentAmount = carPrice * (downPaymentPercent / 100);
        const financeAmount = Math.max(0, carPrice - downPaymentAmount);
        const adminFeeAmount = Math.min(5000, financeAmount * (adminFeePercent / 100));
        const finalPaymentAmount = carPrice * (finalPaymentPercent / 100);

        // تحديث الحقول
        this.elements.down_payment_amount.value = this.formatCurrency(downPaymentAmount);
        this.elements.finance_amount.value = this.formatCurrency(financeAmount);
        this.elements.admin_fee_amount.value = this.formatCurrency(adminFeeAmount);
        this.elements.final_payment_amount.value = this.formatCurrency(finalPaymentAmount);

        // تحديث فئة السيارة
        const brand = this.elements.car_brand.value;
        const category = this.carCategories[brand] || 'C';
        this.elements.car_category_badge.textContent = `الفئة: ${category}`;

        // تحديث معدل التأمين
        this.updateInsuranceRate();
    }

    updateInsuranceRate() {
        const gender = this.elements.gender.value;
        const age = this.elements.age.value;
        const brand = this.elements.car_brand.value;
        const category = this.carCategories[brand] || 'C';
        
        const genderKey = gender === 'ذكر' ? 'male' : 'female';
        const rate = this.insuranceRates[genderKey]?.[age]?.[category] || 0;
        
        this.elements.insurance_rate.value = this.formatPercentPlain(rate * 100, 3);
    }

    async calculate() {
        try {
            this.showLoading();
            
            const data = this.getFormData();
            
            console.log('إرسال البيانات:', data);
            
            const response = await fetch('/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('خطأ في الاستجابة:', errorText);
                throw new Error(`خطأ في الخادم: ${response.status}`);
            }
            
            const result = await response.json();
            console.log('النتيجة:', result);
            
            if (result.error) {
                throw new Error(result.error);
            }
            
            this.displayResults(result);
            this.showToast('تم الحساب بنجاح');
            
        } catch (error) {
            console.error('خطأ:', error);
            this.showToast('حدث خطأ في الحساب: ' + error.message, 'error');
        } finally {
            this.hideLoading();
        }
    }

    getFormData() {
        return {
            car_price: this.elements.car_price.value,
            down_payment_percent: this.elements.down_payment_percent.value,
            loan_term: this.elements.loan_term.value,
            profit_margin: this.elements.profit_margin.value,
            admin_fee_percent: this.elements.admin_fee_percent.value,
            final_payment_percent: this.elements.final_payment_percent.value,
            gender: this.elements.gender.value,
            age: this.elements.age.value,
            car_brand: this.elements.car_brand.value
        };
    }

    displayResults(result) {
        // عرض النتائج الرئيسية
        this.elements.monthly_installment.textContent = this.formatCurrency(result.results.installment || 0);
        this.elements.monthly_with_insurance.textContent = this.formatCurrency(result.results.monthlyWithInsurance || 0);
        this.elements.flat_profit_rate.textContent = this.formatPercent((result.results.flatProfitRate || 0) * 100);
        this.elements.total_cost_percent.textContent = this.formatPercent((result.results.totalCostPercent || 0) * 100);
        this.elements.total_fees.textContent = this.formatCurrency(result.results.totalFees || 0);
        this.elements.total_insurance.textContent = this.formatCurrency(result.results.totalInsurance || 0);
        this.elements.total_profit.textContent = this.formatCurrency(result.results.totalProfit || 0);
        this.elements.remaining_value.textContent = this.formatCurrency(result.results.remainingValue || 0);
        this.elements.grand_total.textContent = this.formatCurrency(result.results.grandTotal || 0);
        
        // معدلات النسبة المئوية
        this.elements.apr_rate.textContent = isFinite(result.results.aprAnnual) ? 
            this.formatPercent((result.results.aprAnnual || 0) * 100) : '—';
        this.elements.irr_rate.textContent = isFinite(result.results.irrAnnual) ? 
            this.formatPercent((result.results.irrAnnual || 0) * 100) : '—';
        
        // صافي الربح
        this.elements.net_profit.textContent = this.formatCurrency(result.results.netProfit || 0);
        
        // تحديث جدول الأقساط
        this.updateScheduleTable(result.schedule || []);
        
        // تحديث مبلغ التأمين
        this.elements.insurance_amount.value = this.formatCurrency(result.insurance?.insuranceAmount || 0);
    }

    updateScheduleTable(schedule) {
        const body = document.getElementById('schedule_body');
        if (!body) return;
        
        let rowsHtml = '';
        
        if (schedule.rows && Array.isArray(schedule.rows)) {
            schedule.rows.forEach(row => {
                rowsHtml += `
                <tr>
                    <td>${row.installmentNo}</td>
                    <td>${row.year}</td>
                    <td>${this.formatNumber(row.outstanding, 2)}</td>
                    <td>${this.formatNumber(row.profit, 2)}</td>
                    <td>${this.formatNumber(row.principal, 2)}</td>
                    <td>${this.formatNumber(row.insurance, 2)}</td>
                    <td>${this.formatNumber(row.cashFlow, 2)}</td>
                    <td>${this.formatNumber(row.cfPercent, 2)}%</td>
                    <td>${this.formatNumber(row.ftp, 5)}</td>
                </tr>`;
            });
        }
        
        body.innerHTML = rowsHtml;

        // تحديث المجاميع
        if (schedule.totals) {
            document.getElementById('schedule_total_outstanding').textContent = this.formatNumber(schedule.totals.outstanding || 0, 2);
            document.getElementById('schedule_total_profit').textContent = this.formatNumber(schedule.totals.totalProfit || 0, 2);
            document.getElementById('schedule_total_principal').textContent = this.formatNumber(schedule.totals.totalPrincipal || 0, 2);
            document.getElementById('schedule_total_insurance').textContent = this.formatNumber(schedule.totals.totalInsurance || 0, 2);
            document.getElementById('schedule_total_cashflow').textContent = this.formatNumber(schedule.totals.totalCashFlow || 0, 2);
            document.getElementById('schedule_weighted_ftp').textContent = this.formatNumber(schedule.totals.weightedFTP || 0, 5);
        }

        if (schedule.summary) {
            document.getElementById('schedule_total_installments').textContent = this.formatCurrency(schedule.summary.totalInstallments || 0);
            document.getElementById('schedule_count').textContent = schedule.summary.count || 0;
        }
    }

    populateInsuranceTables() {
        const ages = ["18 to 24", "25 to 30", "31 to 35", "36 to 40", "41 to 50", "51 to 60", "61 & above"];
        const cats = ["A", "B", "C", "D", "E", "F", "G"];

        const maleBody = document.getElementById('male_table');
        const femaleBody = document.getElementById('female_table');

        if (maleBody && femaleBody) {
            maleBody.innerHTML = '';
            femaleBody.innerHTML = '';

            const buildRows = (genderKey, tbody) => {
                const rows = [];
                for (const age of ages) {
                    for (const c of cats) {
                        const r = this.insuranceRates[genderKey]?.[age]?.[c] ?? 0;
                        rows.push(`<tr><td>${age}</td><td>${c}</td><td>${this.formatPercentPlain(r * 100, 3)}</td></tr>`);
                    }
                }
                tbody.innerHTML = rows.join("");
            };

            buildRows("male", maleBody);
            buildRows("female", femaleBody);

            const brands = Object.keys(this.carCategories).sort((a, b) => a.localeCompare(b));
            const bt = document.getElementById('brands_table');
            if (bt) {
                const rows = [];
                for (let i = 0; i < brands.length; i += 2) {
                    const b1 = brands[i];
                    const b2 = brands[i + 1] || "";
                    rows.push(
                        `<tr><td>${b1}</td><td>${this.carCategories[b1] || ""}</td><td>${b2}</td><td>${b2 ? this.carCategories[b2] || "" : ""}</td></tr>`
                    );
                }
                bt.innerHTML = rows.join("");
            }
        }
    }

    async sendToAssistant() {
        const prompt = document.getElementById('assistant_prompt')?.value.trim();
        if (!prompt) {
            this.showToast('يرجى كتابة سؤال', 'error');
            return;
        }

        const assistantStatus = document.getElementById('assistant_status');
        const assistantAnswer = document.getElementById('assistant_answer');

        assistantStatus.textContent = "يتم الإرسال…";
        assistantAnswer.innerHTML = "";

        try {
            // حساب النتائج الحالية
            const latest = await this.getCurrentResults();
            
            const ctx = [
                `سعر السيارة: ${this.formatCurrency(latest.inputs.carPrice)}`,
                `مبلغ التمويل: ${this.formatCurrency(latest.derived.financeAmount)}`,
                `المدة: ${latest.inputs.loanTerm} شهر`,
                `هامش الربح السنوي: ${this.formatPercent(latest.inputs.profitMarginPercent)}`,
                `الدفعة الأخيرة: ${this.formatCurrency(latest.derived.finalPaymentAmount)}`,
                `فئة السيارة: ${latest.insurance.carCategory}`,
                `معدل التأمين: ${this.formatPercent(latest.insurance.insuranceRateDecimal)}`,
                `مبلغ التأمين: ${this.formatCurrency(latest.insurance.insuranceAmount)}`,
                `القسط الشهري: ${this.formatCurrency(latest.results.installment)}`,
                `القسط مع التأمين: ${this.formatCurrency(latest.results.monthlyWithInsurance)}`
            ].join("\n");

            const response = await fetch('/assistant', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    prompt: prompt,
                    context: ctx
                })
            });

            if (!response.ok) {
                throw new Error('خطأ في الاتصال بالمساعد');
            }

            const data = await response.json();
            assistantAnswer.innerHTML = data.answer || "لم يتم استلام رد.";
            assistantStatus.textContent = "تم";

        } catch (e) {
            assistantStatus.textContent = "خطأ";
            assistantAnswer.textContent = String(e?.message || e);
            this.showToast('حدث خطأ في الاتصال بالمساعد', 'error');
        }
    }

    async getCurrentResults() {
        // إرسال طلب للحصول على النتائج الحالية
        const data = this.getFormData();
        
        const response = await fetch('/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error('خطأ في الحصول على النتائج');
        }
        
        return await response.json();
    }

    // دوال التنسيق
    formatCurrency(value) {
        if (!isFinite(value) || value === null || value === undefined) return '—';
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: 'SAR',
            maximumFractionDigits: 2
        }).format(value);
    }

    formatNumber(value, digits = 2) {
        const v = Number(value);
        if (!isFinite(v)) return '—';
        return new Intl.NumberFormat('ar-SA', {
            maximumFractionDigits: digits,
            minimumFractionDigits: digits
        }).format(v);
    }

    formatPercent(value, digits = 2) {
        const v = Number(value);
        if (!isFinite(v)) return '—';
        return new Intl.NumberFormat('ar-SA', {
            maximumFractionDigits: digits,
            minimumFractionDigits: digits
        }).format(v) + '%';
    }

    formatPercentPlain(value, digits = 3) {
        const v = Number(value);
        if (!isFinite(v)) return '—';
        return new Intl.NumberFormat('ar-SA', {
            maximumFractionDigits: digits,
            minimumFractionDigits: digits
        }).format(v) + '%';
    }

    showLoading() {
        const btn = this.elements.calc_btn;
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحساب...';
        }
        this.setStatus('يتم الحساب...');
    }

    hideLoading() {
        const btn = this.elements.calc_btn;
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bolt me-2"></i>احسب';
        }
        this.setStatus('جاهز');
    }

    setStatus(text) {
        const statusBadge = document.getElementById('status_badge');
        if (statusBadge) {
            statusBadge.textContent = text;
        }
    }

    showToast(message, type = 'success') {
        // استخدام Bootstrap Toast
        const toastEl = document.getElementById('toast');
        if (!toastEl) {
            console.log(`${type}: ${message}`);
            return;
        }

        const toastBody = toastEl.querySelector('.toast-body');
        if (toastBody) {
            toastBody.textContent = message;
        }

        // تغيير لون Toast حسب النوع
        if (type === 'error') {
            toastEl.classList.remove('bg-primary');
            toastEl.classList.add('bg-danger');
        } else {
            toastEl.classList.remove('bg-danger');
            toastEl.classList.add('bg-primary');
        }

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}

// تهيئة الحاسبة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    window.calculator = new FinanceCalculator();
});