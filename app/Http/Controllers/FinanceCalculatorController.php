<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceCalculation;
use App\Models\Installment;
use App\Models\InsuranceRate;
use App\Models\Brand;
use App\Models\AgeBracket;
use App\Models\CarModel;
use App\Models\CarSegment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinanceCalculatorController extends Controller
{
    /**
     * قيم FTP لأسعار السيارات المختلفة (0 و 100,000)
     */
    private const FTP_RATES_CAR_PRICE_0 = [
        2.45, // V19 - FTP
        2.69, 2.69429, 2.70, 2.71286, 2.71, 2.71, 2.72, 2.72, 2.73,
        2.74, 2.75, 2.76, 2.77, 2.72, 2.68, 2.64, 2.60, 2.56, 2.52,
        2.48, 2.44, 2.40, 2.36, 2.31, 2.27, 2.27, 2.28, 2.28, 2.28,
        2.28, 2.28, 2.28, 2.28, 2.28, 2.28, 2.28, 2.29, 2.29, 2.30,
        2.30, 2.31, 2.31, 2.32, 2.33, 2.33, 2.34, 2.34, 2.35, 2.36,
        2.36, 2.37, 2.38, 2.39, 2.40, 2.41, 2.42, 2.43, 2.43, 2.44,
        2.45, 2.46
    ];

    private const FTP_RATES_CAR_PRICE_100000 = [
        2.45, // FTP
        2.69, 2.70, 2.71, 2.71, 2.71, 2.72, 2.72, 2.73, 2.74,
        2.75, 2.76, 2.77, 2.72, 2.68, 2.64, 2.60, 2.56, 2.52,
        2.48, 2.44, 2.40, 2.36, 2.31, 2.27, 2.27, 2.28, 2.28,
        2.28, 2.28, 2.28, 2.28, 2.28, 2.28, 2.28, 2.29, 2.29,
        2.30, 2.30, 2.31, 2.31, 2.32, 2.33, 2.33, 2.34, 2.34,
        2.35, 2.36, 2.36, 2.37, 2.38, 2.39, 2.40, 2.41, 2.42,
        2.43, 2.43, 2.44, 2.45, 2.46
    ];

    /**
     * دالة PMT لحساب القسط الشهري (مطابقة لـ Excel PMT)
     */
    private function pmt($rate, $nper, $pv, $fv = 0, $type = 0)
    {
        if ($rate == 0) {
            return (-$pv - $fv) / $nper;
        }
        
        $pvif = pow(1 + $rate, $nper);
        $pmt = ($rate * ($pv * $pvif + $fv)) / ($pvif - 1);
        
        if ($type == 1) {
            $pmt /= (1 + $rate);
        }
        
        return -$pmt;
    }

    /**
     * دالة RATE محسنة لحساب معدل الفائدة
     */
    private function rate($nper, $pmt, $pv, $fv = 0, $type = 0, $guess = 0.01)
    {
        // التحقق من القيم المدخلة
        if ($nper <= 0) {
            return 0;
        }
        
        if (abs($pmt) < 0.0001) {
            return 0;
        }
        
        $rate = $guess;
        $maxIter = 100;
        $precision = 0.0000001;
        
        for ($i = 0; $i < $maxIter; $i++) {
            $f = $this->fvWithRate($rate, $nper, $pmt, $pv, $type) - $fv;
            $fprime = $this->fvPrimeWithRate($rate, $nper, $pmt, $pv, $type);
            
            if (abs($fprime) < $precision) {
                break;
            }
            
            $newRate = $rate - $f / $fprime;
            
            // منع القيم غير المنطقية
            if ($newRate < -1) {
                $newRate = -0.99;
            } elseif ($newRate > 10) {
                $newRate = 0.10;
            }
            
            if (abs($newRate - $rate) < $precision) {
                return $newRate;
            }
            
            $rate = $newRate;
        }
        
        return $rate;
    }
    
    private function fvWithRate($rate, $nper, $pmt, $pv, $type)
    {
        if ($rate == 0) {
            return -$pv - $pmt * $nper;
        }
        
        $term = pow(1 + $rate, $nper);
        
        if ($type == 1) {
            // الدفعات في بداية الفترة
            return -$pv * $term - $pmt * (1 + $rate) * ($term - 1) / $rate;
        } else {
            // الدفعات في نهاية الفترة
            return -$pv * $term - $pmt * ($term - 1) / $rate;
        }
    }
    
    private function fvPrimeWithRate($rate, $nper, $pmt, $pv, $type)
    {
        if ($rate == 0) {
            return 0;
        }
        
        $term = pow(1 + $rate, $nper);
        $termPrime = $nper * pow(1 + $rate, $nper - 1);
        
        if ($type == 1) {
            $fvPrime = -$pv * $termPrime 
                      - $pmt * (($term - 1) / $rate + (1 + $rate) * ($termPrime / $rate - ($term - 1) / ($rate * $rate)));
        } else {
            $fvPrime = -$pv * $termPrime 
                      - $pmt * ($termPrime / $rate - ($term - 1) / ($rate * $rate));
        }
        
        return $fvPrime;
    }

    /**
     * دالة CEILING مشابهة لـ Excel
     */
    private function ceiling($value, $significance = 1)
    {
        if ($significance == 0) {
            return 0;
        }
        
        if ($value == 0) {
            return 0;
        }
        
        return ceil($value / $significance) * $significance;
    }

    /**
     * حساب FTP للسيارة بناءً على سعرها (بين 0 و 100,000)
     */
    private function calculateFtpForCarPrice($carPrice, $loanTermMonths)
    {
        $ftpValues = [];
        
        // حساب القيم الأساسية باستخدام الاستكمال الخطي بين سعر 0 و 100,000
        $maxIndex = min(count(self::FTP_RATES_CAR_PRICE_0), count(self::FTP_RATES_CAR_PRICE_100000));
        
        for ($i = 0; $i < $maxIndex; $i++) {
            $ftp0 = self::FTP_RATES_CAR_PRICE_0[$i];
            $ftp100k = self::FTP_RATES_CAR_PRICE_100000[$i];
            
            if ($carPrice <= 0) {
                $ftpValues[$i] = $ftp0;
            } elseif ($carPrice >= 100000) {
                $ftpValues[$i] = $ftp100k;
            } else {
                $ftpValues[$i] = $ftp0 + (($ftp100k - $ftp0) * ($carPrice / 100000));
            }
        }
        
        // حساب القيم الوسيطة بناءً على الصيغ المحددة
        $this->calculateIntermediateFtpValues($ftpValues);
        
        // إرجاع القيم المطلوبة بناءً على مدة القرض
        // نبدأ من الفهرس 2 (V21) ونأخذ عدد الأشهر المطلوبة
        $startIndex = 2; // بدءاً من V21
        $requiredValues = $loanTermMonths;
        
        // التأكد من أن لدينا ما يكفي من القيم
        if (count($ftpValues) - $startIndex < $requiredValues) {
            // إذا لم يكن لدينا ما يكفي، نملأ بالقيمة الأخيرة
            $lastValue = end($ftpValues);
            for ($i = count($ftpValues); $i < $startIndex + $requiredValues; $i++) {
                $ftpValues[$i] = $lastValue;
            }
        }
        
        return array_slice($ftpValues, $startIndex, $requiredValues);
    }

    /**
     * حساب القيم الوسيطة للـ FTP بناءً على الصيغ
     */
    private function calculateIntermediateFtpValues(&$ftpValues)
    {
        // التأكد من وجود الحد الأدنى من العناصر
        if (count($ftpValues) < 62) {
            // إذا لم يكن هناك ما يكفي، نملأ بالقيمة الأخيرة
            $lastValue = end($ftpValues);
            for ($i = count($ftpValues); $i < 62; $i++) {
                $ftpValues[$i] = $lastValue;
            }
        }
        
        // حساب V22 (الفهرس 4) بناءً على V21 (الفهرس 3) و V23 (الفهرس 5)
        if (isset($ftpValues[3]) && isset($ftpValues[4]) && isset($ftpValues[5])) {
            $ftpValues[4] = $ftpValues[3] + ($ftpValues[5] - $ftpValues[3]) * 1/2; // V22
        }
        
        // حساب V24 (الفهرس 7) و V25 (الفهرس 8) بناءً على V23 (الفهرس 5) و V26 (الفهرس 9)
        if (isset($ftpValues[5]) && isset($ftpValues[9])) {
            $ftpValues[7] = $ftpValues[5] + ($ftpValues[9] - $ftpValues[5]) * 1/3; // V24
            $ftpValues[8] = $ftpValues[5] + ($ftpValues[9] - $ftpValues[5]) * 2/3; // V25
        }
        
        // حساب V27 إلى V31 (الفهارس 10 إلى 14) بناءً على V26 (الفهرس 9) و V32 (الفهرس 15)
        if (isset($ftpValues[9]) && isset($ftpValues[15])) {
            for ($i = 10; $i <= 14; $i++) {
                $ftpValues[$i] = $ftpValues[9] + ($ftpValues[15] - $ftpValues[9]) * ($i - 9) / 6;
            }
        }
        
        // حساب V33 إلى V43 (الفهارس 16 إلى 26) بناءً على V32 (الفهرس 15) و V44 (الفهرس 27)
        if (isset($ftpValues[15]) && isset($ftpValues[27])) {
            for ($i = 16; $i <= 26; $i++) {
                $ftpValues[$i] = $ftpValues[15] + ($ftpValues[27] - $ftpValues[15]) * ($i - 15) / 12;
            }
        }
        
        // حساب V45 إلى V55 (الفهارس 28 إلى 38) بناءً على V44 (الفهرس 27) و V56 (الفهرس 39)
        if (isset($ftpValues[27]) && isset($ftpValues[39])) {
            for ($i = 28; $i <= 38; $i++) {
                $ftpValues[$i] = $ftpValues[27] + ($ftpValues[39] - $ftpValues[27]) * ($i - 27) / 12;
            }
        }
        
        // حساب V57 إلى V67 (الفهارس 40 إلى 50) بناءً على V56 (الفهرس 39) و V68 (الفهرس 51)
        if (isset($ftpValues[39]) && isset($ftpValues[51])) {
            for ($i = 40; $i <= 50; $i++) {
                $ftpValues[$i] = $ftpValues[39] + ($ftpValues[51] - $ftpValues[39]) * ($i - 39) / 12;
            }
        }
        
        // حساب V69 إلى V79 (الفهارس 52 إلى 62) بناءً على V68 (الفهرس 51) و V80 (الفهرس 63)
        if (isset($ftpValues[51]) && isset($ftpValues[63])) {
            for ($i = 52; $i <= 62; $i++) {
                $ftpValues[$i] = $ftpValues[51] + ($ftpValues[63] - $ftpValues[51]) * ($i - 51) / 12;
            }
        }
    }

    /**
     * حساب قيمة التأمين بناءً على المدة (الطريقة الجديدة حسب النموذج المقدم)
     */
  private function calculateInsuranceAmount($loanTermMonths, $carPrice, $insuranceRatePercentage)
{
    // حساب عدد السنوات الكاملة
    $fullYears = floor($loanTermMonths / 12);
    $remainingMonths = $loanTermMonths % 12;
    
    // مصفوفة لتخزين قيم التأمين السنوي
    $annualInsurance = [];
    
    // حساب قيمة السيارة لكل سنة (تخفيض 15% سنوياً)
    $currentCarValue = $carPrice;
    
    // حساب التأمين لكل سنة كاملة
    for ($year = 1; $year <= $fullYears; $year++) {
        // حساب قيمة التأمين للعام (سنوي)
        $insuranceForYear = $currentCarValue * ($insuranceRatePercentage / 100);
        
        // تخزين قيمة التأمين للعام
        $annualInsurance[$year] = [
            'car_value' => $currentCarValue,
            'insurance_rate' => $insuranceRatePercentage,
            'annual_insurance' => $insuranceForYear,
            'monthly_insurance' => $insuranceForYear / 12
        ];
        
        // تقليل قيمة السيارة بنسبة 15% للعام التالي
        $currentCarValue = $currentCarValue * 0.85;
    }
    
    // حساب التأمين للأشهر المتبقية (إذا وجدت)
    if ($remainingMonths > 0) {
        // حساب التأمين الشهري للقيمة الحالية للسيارة
        $monthlyInsuranceRemaining = $currentCarValue * ($insuranceRatePercentage / 100) / 12;
        $insuranceForRemaining = $monthlyInsuranceRemaining * $remainingMonths;
        
        $annualInsurance['remaining'] = [
            'car_value' => $currentCarValue,
            'insurance_rate' => $insuranceRatePercentage,
            'annual_insurance' => $insuranceForRemaining,
            'monthly_insurance' => $monthlyInsuranceRemaining
        ];
    }
    
    // حساب إجمالي التأمين
    $totalInsurance = 0;
    foreach ($annualInsurance as $insurance) {
        $totalInsurance += $insurance['annual_insurance'];
    }
    
    // حساب التأمين الشهري الثابت (المتوسط)
    $monthlyInsurance = $loanTermMonths > 0 ? $totalInsurance / $loanTermMonths : 0;
    
    // تحديث القيمة الشهرية في تفصيل السنوات لتعكس القيمة الثابتة
    foreach ($annualInsurance as &$insurance) {
        $insurance['monthly_insurance'] = $monthlyInsurance;
    }
    
    // إذا كان هناك أشهر متبقية، نضبط التأمين الشهري
    if (isset($annualInsurance['remaining'])) {
        $annualInsurance['remaining']['monthly_insurance'] = $monthlyInsurance;
    }
    
    return [
        'total_insurance' => $totalInsurance,
        'monthly_insurance' => $monthlyInsurance,
        'annual_insurance_breakdown' => $annualInsurance
    ];
}

    /**
     * إنشاء جدول التقسيط الكامل
     */
  private function generateCompleteInstallmentSchedule($basicCalculations, $ftpValues)
{
    $loanTermMonths = $basicCalculations['loan_term_months'];
    $financedAmount = $basicCalculations['financed_amount'];
    $finalPaymentAmount = $basicCalculations['final_payment_amount'];
    $monthlyInstallmentWithoutInsurance = $basicCalculations['monthly_installment_without_insurance'];
    $profitMarginPercentage = $basicCalculations['profit_margin_percentage'];
    $totalInsurance = $basicCalculations['total_insurance'];
    $monthlyInsurance = $basicCalculations['monthly_insurance']; // القيمة الشهرية الثابتة
    
    $installments = [];
    $T = []; // Cash Flows
    $U = []; // CF %
    
    // المتغيرات المستخدمة في الحسابات
    $C25_monthly = $profitMarginPercentage / 100 / 12;
    $monthlyInstallment = $monthlyInstallmentWithoutInsurance;
    
    // الرصيد المتبقي
    $outstandingBalance = $financedAmount;
    
    for ($month = 1; $month <= $loanTermMonths; $month++) {
        // حساب السنة
        $year = ceil($month / 12);
        
        // التأمين الشهري الثابت
        $insurance = $monthlyInsurance;
        
        // حساب الربح لهذا الشهر
        $profit = $outstandingBalance * $C25_monthly;
        
        // حساب أصل القرض لهذا الشهر
        $principal = 0;
        if ($month == $loanTermMonths) {
            // الشهر الأخير: ندفع الرصيد المتبقي بالإضافة إلى الدفعة الأخيرة
            $principal = $outstandingBalance - $finalPaymentAmount;
        } else {
            $principal = $monthlyInstallment - $profit;
        }
        
        // تحديث الرصيد المتبقي
        $outstandingBalance -= $principal;
        
        // حساب Cash Flow
        $cashFlow = $principal + $profit + $insurance;
        
        // حساب CF % (سيتم تعديله لاحقاً بعد حساب T19)
        $cfPercentage = 0;
        
        // FTP الشهري - تحقق من وجود القيمة
        $ftpMonthly = isset($ftpValues[$month - 1]) ? $ftpValues[$month - 1] : 0;
        
        // حساب Outstanding + Insurance (تقديري)
        $outstandingPlusInsurance = $outstandingBalance + ($insurance * ($loanTermMonths - $month));
        
        // حفظ بيانات الشهر
        $installments[] = [
            'installment_number' => $month,
            'year' => $year,
            'outstanding_plus_insurance' => $outstandingPlusInsurance,
            'outstanding_balance' => $outstandingBalance + $principal, // نعيد إضافة الأصل للحصول على الرصيد قبل الدفع
            'profit_amount' => $profit,
            'principal_plus_insurance' => $principal + $insurance,
            'principal_amount' => $principal,
            'insurance_amount' => $insurance,
            'cash_flow' => $cashFlow,
            'cf_percentage' => $cfPercentage,
            'ftp_monthly' => $ftpMonthly,
        ];
        
        // حفظ T و U للحسابات اللاحقة
        $T[$month - 1] = $cashFlow;
    }
    
    // إضافة الدفعة الأخيرة في الشهر الأخير
    if ($loanTermMonths > 0) {
        $installments[$loanTermMonths - 1]['outstanding_balance'] += $finalPaymentAmount;
        $installments[$loanTermMonths - 1]['principal_amount'] += $finalPaymentAmount;
        $installments[$loanTermMonths - 1]['cash_flow'] += $finalPaymentAmount;
        $T[$loanTermMonths - 1] += $finalPaymentAmount;
    }
    
    // حساب T19 (إجمالي Cash Flows)
    $T19 = array_sum($T);
    
    // حساب U (CF %)
    $U = array_map(function($t) use ($T19) {
        return $T19 > 0 ? ($t / $T19) * 100 : 0;
    }, $T);
    
    // تحديث cf_percentage في الـ installments
    foreach ($installments as $key => &$installment) {
        $installment['cf_percentage'] = $U[$key];
    }
    
    // حساب V19 (FTP النهائي) باستخدام SUMPRODUCT
    $V19 = 0;
    for ($i = 0; $i < min(count($U), count($ftpValues)); $i++) {
        $V19 += ($U[$i] * $ftpValues[$i]);
    }
    $V19 = $V19 / 100;
    
    // حساب المجاميع
    $totalProfit = array_sum(array_column($installments, 'profit_amount'));
    $totalPrincipal = array_sum(array_column($installments, 'principal_amount'));
    $totalInsurancePaid = array_sum(array_column($installments, 'insurance_amount'));
    
    return [
        'installments' => $installments,
        'T19' => $T19,
        'V19' => $V19,
        'total_profit' => $totalProfit,
        'total_principal' => $totalPrincipal,
        'total_insurance' => $totalInsurancePaid,
        'total_cash_flows' => $T19,
    ];
}

    /**
     * حساب جميع المؤشرات المالية
     */
    private function calculateAllFinancialIndicators($basicCalculations, $installmentSchedule)
    {
        $carPrice = $basicCalculations['car_price'];
        $financedAmount = $basicCalculations['financed_amount'];
        $loanTermMonths = $basicCalculations['loan_term_months'];
        $monthlyInstallmentWithoutInsurance = $basicCalculations['monthly_installment_without_insurance'];
        $monthlyInstallmentWithInsurance = $basicCalculations['monthly_installment_with_insurance'];
        $profitMarginPercentage = $basicCalculations['profit_margin_percentage'];
        $administrativeFeesAmount = $basicCalculations['administrative_fees_amount'];
        $finalPaymentAmount = $basicCalculations['final_payment_amount'];
        $totalInsurance = $basicCalculations['total_insurance'];
        $insuranceRatePercentage = $basicCalculations['insurance_rate_percentage'];
        $downPaymentAmount = $basicCalculations['down_payment_amount'];
        $insuranceBreakdown = $basicCalculations['insurance_breakdown'];
        
        // FTP النهائي
        $ftp = $installmentSchedule['V19'];
        
        // القيم الثابتة
        $cor = 1.08;  // CoR
        $opex = 0.48; // OPEX
        
        // حسابات Breakeven
        $breakeven = $ftp + $cor + $opex;
        $margin = $profitMarginPercentage - $breakeven;
        $netProfit = ($margin / 100) * $financedAmount;
        
        // معلومات التأمين
        $insuranceCostARB = $insuranceRatePercentage;
        $minInsurancePremium = 1650;
        
        // الخصم (افتراضي 0%)
        $rebatePercentage = 0.0;
        $rebateAmount = 0;
        
        // حساب IRR (باستخدام الدالة المحسنة)
        $irrMonthly = 0;
        try {
            $irrMonthly = $this->rate(
                $loanTermMonths,
                $monthlyInstallmentWithoutInsurance,
                -($financedAmount + $totalInsurance),
                $finalPaymentAmount
            );
        } catch (\Exception $e) {
            $irrMonthly = 0;
        }
        
        $irr = $irrMonthly ? round($irrMonthly * 12 * 100, 4) : 0;
        
        // تقييد IRR بين 0 و 100
        $irr = max(0, min($irr, 100));
        
        // حساب الربح الاقتصادي
        $profitEconomic = ($monthlyInstallmentWithoutInsurance * $loanTermMonths) + 
                         $finalPaymentAmount - 
                         $financedAmount - 
                         $totalInsurance;
        
        // الحد الأقصى للرسوم الإدارية
        $maxAdministrativeFees = 5000;
        
        // حساب APR
        $aprMonthly = 0;
        try {
            $aprMonthly = $this->rate(
                $loanTermMonths,
                $monthlyInstallmentWithInsurance,
                -($financedAmount + $totalInsurance - $administrativeFeesAmount),
                $finalPaymentAmount
            );
        } catch (\Exception $e) {
            $aprMonthly = 0;
        }
        
        $apr = $aprMonthly ? round((pow(1 + $aprMonthly, 12) - 1) * 100, 5) : 0;
        $apr = max(0, min($apr, 100));
        
        // حساب نسبة الربح Flat rate
        $flatProfitRate = 0;
        if ($financedAmount > 0 && $loanTermMonths > 0) {
            $flatProfitRate = ($installmentSchedule['total_profit'] / $financedAmount / $loanTermMonths) * 12 * 100;
        }
        
        // إجمالي التكلفة
        $totalCost = $profitMarginPercentage + $insuranceRatePercentage;
        
        // الإجماليات
        $totalFees = $administrativeFeesAmount;
        $totalProfit = $installmentSchedule['total_profit'];
        $totalInsurancePaid = $installmentSchedule['total_insurance'];
        $remainingCarValue = $installmentSchedule['total_principal'];
        $grandTotal = $downPaymentAmount + 
                     $finalPaymentAmount + 
                     $administrativeFeesAmount + 
                     ($monthlyInstallmentWithInsurance * $loanTermMonths);
        
        // تفصيل التأمين السنوي
        $insuranceDetails = [];
        foreach ($insuranceBreakdown as $year => $data) {
            if (is_int($year)) {
                $insuranceDetails[] = [
                    'year' => $year,
                    'car_value' => round($data['car_value'], 2),
                    'insurance_rate' => $data['insurance_rate'],
                    'annual_insurance' => round($data['annual_insurance'], 2),
                    'monthly_insurance' => round($data['monthly_insurance'], 2)
                ];
            }
        }
        
        // إذا كان هناك أشهر متبقية
        if (isset($insuranceBreakdown['remaining'])) {
            $insuranceDetails[] = [
                'year' => 'متبقي',
                'car_value' => round($insuranceBreakdown['remaining']['car_value'], 2),
                'insurance_rate' => $insuranceBreakdown['remaining']['insurance_rate'],
                'annual_insurance' => round($insuranceBreakdown['remaining']['annual_insurance'], 2),
                'monthly_insurance' => round($insuranceBreakdown['remaining']['monthly_insurance'], 2)
            ];
        }
        
        return [
            'ftp_percentage' => round($ftp, 6),
            'cor_percentage' => $cor,
            'opex_percentage' => $opex,
            'breakeven_percentage' => round($breakeven, 6),
            'margin_percentage' => round($margin, 6),
            'net_profit' => round($netProfit, 2),
            'insurance_cost_arb' => $insuranceCostARB,
            'min_insurance_premium' => $minInsurancePremium,
            'rebate_percentage' => $rebatePercentage,
            'rebate_amount' => $rebateAmount,
            'irr_percentage' => round($irr, 4),
            'profit_economic' => round($profitEconomic, 2),
            'max_administrative_fees' => $maxAdministrativeFees,
            'apr_percentage' => round($apr, 5),
            'flat_profit_rate_percentage' => round($flatProfitRate, 4),
            'total_cost_percentage' => round($totalCost, 2),
            'total_fees' => round($totalFees, 2),
            'total_profit' => round($totalProfit, 2),
            'total_insurance' => round($totalInsurancePaid, 2),
            'remaining_car_value' => round($remainingCarValue, 2),
            'grand_total' => round($grandTotal, 2),
            'insurance_details' => $insuranceDetails,
        ];
    }

    /**
     * عرض نموذج الآلة الحاسبة
     */
    public function showCalculator()
    {
        $ageBrackets = AgeBracket::all();
        $carSegments = CarSegment::all();
        $brands = Brand::all();
       $carModels = CarModel::orderBy('model_year', 'DESC')->get();
        
        return view('finance.calculator', compact('ageBrackets', 'carSegments', 'brands','carModels'));
    }

    /**
     * إجراء الحساب
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_price' => 'required|numeric|min:0',
             'car_model_id' => 'required|exists:car_models,id', 
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
            'loan_term_months' => 'required|integer|min:1|max:60',
            'profit_margin_percentage' => 'required|numeric|min:0',
            'administrative_fees_percentage' => 'required|numeric|min:0',
            'final_payment_percentage' => 'required|numeric|min:0|max:100',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'age_bracket' => 'required|string',
            'car_brand' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // الحصول على نسبة التأمين
            $insuranceRate = $this->getInsuranceRate(
                $request->gender,
                $request->age_bracket,
                $request->car_brand
            );

            // إجراء الحسابات الأساسية
            $basicCalculations = $this->performBasicCalculations($request, $insuranceRate);

            // حساب قيم FTP
            $ftpValues = $this->calculateFtpForCarPrice(
                $request->car_price,
                $request->loan_term_months
            );

            // إنشاء جدول التقسيط
            $installmentSchedule = $this->generateCompleteInstallmentSchedule($basicCalculations, $ftpValues);

            // حساب المؤشرات المالية
            $financialIndicators = $this->calculateAllFinancialIndicators($basicCalculations, $installmentSchedule);

            // دمج جميع النتائج
            $allCalculations = array_merge($basicCalculations, $financialIndicators);
            $allCalculations['gender'] = $request->gender;
            $allCalculations['phone']= $request->phone;
            $allCalculations['age_bracket'] = $request->age_bracket;
            $allCalculations['car_brand'] = $request->car_brand;
             $allCalculations['car_model_id'] = $request->car_model_id;
            $allCalculations['gender_display'] = $request->gender == 'male' ? 'ذكر' : 'أنثى';

            // حفظ الحساب في قاعدة البيانات
            $financeCalculation = $this->saveCalculation($request, $allCalculations, $insuranceRate, $installmentSchedule);

            // عرض النتائج
            return view('finance.result', [
                'financeCalculation' => $financeCalculation,
                'installments' => $installmentSchedule['installments'],
                'allCalculations' => $allCalculations,
                'financialIndicators' => $financialIndicators,
                'T19' => $installmentSchedule['T19'],
                'V19' => $installmentSchedule['V19'],
                'insurance_details' => $allCalculations['insurance_details'],
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ في الحسابات: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * الحصول على نسبة التأمين
     */
    private function getInsuranceRate($gender, $ageBracketName, $carBrandName)
    {
        $genderEn = ($gender == 'male') ? 'male' : 'female';
        
        $brand = Brand::where('name', $carBrandName)->first();
        if (!$brand) return null;

        $ageBracket = AgeBracket::where('name', $ageBracketName)->first();
        if (!$ageBracket) return null;

        return InsuranceRate::where([
            'gender' => $genderEn,
            'age_bracket_id' => $ageBracket->id,
            'car_segment_id' => $brand->car_segment_id
        ])->first();
    }

    /**
     * إجراء الحسابات الأساسية
     */
    private function performBasicCalculations(Request $request, $insuranceRate)
    {
        $carPrice = $request->car_price;
        $downPaymentPercentage = $request->down_payment_percentage;
        $finalPaymentPercentage = $request->final_payment_percentage;
        $profitMarginPercentage = $request->profit_margin_percentage;
        $administrativeFeesPercentage = $request->administrative_fees_percentage;
        $loanTermMonths = $request->loan_term_months;

        // الدفعة الأولى
        $downPaymentAmount = ($downPaymentPercentage / 100) * $carPrice;
        
        // مبلغ التمويل
        $financedAmount = $carPrice - $downPaymentAmount;
        
        // الرسوم الإدارية
        $administrativeFeesAmount = ($administrativeFeesPercentage / 100) * $financedAmount;
        $administrativeFeesAmount = round(min($administrativeFeesAmount, 5000), 0);
        
        // الدفعة الأخيرة
        $finalPaymentAmount = ($finalPaymentPercentage / 100) * $carPrice;
        
        // نسبة التأمين
        $insuranceRatePercentage = $insuranceRate ? $insuranceRate->rate : 8.30;
        
        // حساب التأمين بالطريقة الجديدة
        $insuranceCalculation = $this->calculateInsuranceAmount(
            $loanTermMonths,
            $carPrice,
            $insuranceRatePercentage
        );
        
        // استخراج قيم التأمين من الحساب
        $totalInsurance = $insuranceCalculation['total_insurance'];
        $monthlyInsurance = $insuranceCalculation['monthly_insurance'];
        $insuranceBreakdown = $insuranceCalculation['annual_insurance_breakdown'];
        
        // حساب القسط الشهري بدون تأمين
        $monthlyRate = ($profitMarginPercentage / 100) / 12;
        $monthlyInstallmentWithoutInsurance = $this->pmt(
            $monthlyRate,
            $loanTermMonths,
            -$financedAmount,
            $finalPaymentAmount
        );
        
        // القسط الشهري شامل التأمين
        $monthlyInstallmentWithInsurance = $monthlyInstallmentWithoutInsurance + $monthlyInsurance;

        return [
            'car_price' => $carPrice,
            'down_payment_percentage' => $downPaymentPercentage,
            'down_payment_amount' => $downPaymentAmount,
            'financed_amount' => $financedAmount,
            'final_payment_percentage' => $finalPaymentPercentage,
            'final_payment_amount' => $finalPaymentAmount,
            'loan_term_months' => $loanTermMonths,
            'profit_margin_percentage' => $profitMarginPercentage,
            'monthly_rate' => $monthlyRate,
            'administrative_fees_percentage' => $administrativeFeesPercentage,
            'administrative_fees_amount' => $administrativeFeesAmount,
            'insurance_rate_percentage' => $insuranceRatePercentage,
            'total_insurance' => $totalInsurance,
            'monthly_insurance' => $monthlyInsurance,
            'monthly_installment_without_insurance' => $monthlyInstallmentWithoutInsurance,
            'monthly_installment_with_insurance' => $monthlyInstallmentWithInsurance,
            'insurance_breakdown' => $insuranceBreakdown,
        ];
    }

    /**
     * حفظ الحساب في قاعدة البيانات
     */
    private function saveCalculation(Request $request, $calculations, $insuranceRate, $installmentSchedule)
    {
        DB::beginTransaction();
        try {
            $financeCalculation = FinanceCalculation::create([
                'car_price' => $calculations['car_price'],
                  'car_model_id' => $calculations['car_model_id'],
                'down_payment_percentage' => $calculations['down_payment_percentage'],
                'down_payment_amount' => $calculations['down_payment_amount'],
                'financed_amount' => $calculations['financed_amount'],
                'loan_term_months' => $calculations['loan_term_months'],
                'profit_margin_percentage' => $calculations['profit_margin_percentage'],
                'administrative_fees_percentage' => $calculations['administrative_fees_percentage'],
                'administrative_fees_amount' => $calculations['administrative_fees_amount'],
                'insurance_rate_percentage' => $calculations['insurance_rate_percentage'],
                'final_payment_percentage' => $calculations['final_payment_percentage'],
                'final_payment_amount' => $calculations['final_payment_amount'],
                'monthly_installment_without_insurance' => $calculations['monthly_installment_without_insurance'],
                'monthly_installment_with_insurance' => $calculations['monthly_installment_with_insurance'],
                'ftp_percentage' => $calculations['ftp_percentage'],
                'cor_percentage' => $calculations['cor_percentage'],
                'opex_percentage' => $calculations['opex_percentage'],
                'breakeven_percentage' => $calculations['breakeven_percentage'],
                'margin_percentage' => $calculations['margin_percentage'],
                'net_profit' => $calculations['net_profit'],
                'insurance_cost_arb' => $calculations['insurance_cost_arb'],
                'min_insurance_premium' => $calculations['min_insurance_premium'],
                'rebate_percentage' => $calculations['rebate_percentage'],
                'rebate_amount' => $calculations['rebate_amount'],
                'irr_percentage' => $calculations['irr_percentage'],
                'profit_economic' => $calculations['profit_economic'],
                'max_administrative_fees' => $calculations['max_administrative_fees'],
                'apr_percentage' => $calculations['apr_percentage'],
                'flat_profit_rate_percentage' => $calculations['flat_profit_rate_percentage'],
                'total_cost_percentage' => $calculations['total_cost_percentage'],
                'total_fees' => $calculations['total_fees'],
                'total_profit' => $calculations['total_profit'],
                'total_insurance' => $calculations['total_insurance'],
                'remaining_car_value' => $calculations['remaining_car_value'],
                'grand_total' => $calculations['grand_total'],
                'gender' => $request->gender,
                'phone' => $request->phone,
                'age_bracket' => $request->age_bracket,
                'car_brand' => $request->car_brand,
                'car_segment' => $insuranceRate ? ($insuranceRate->carSegment ? $insuranceRate->carSegment->segment : null) : null,
                'insurance_rate_id' => $insuranceRate ? $insuranceRate->id : null,
                'user_id' => Auth::id(),
                 'status' => 'pending',
            ]);

            foreach ($installmentSchedule['installments'] as $installmentData) {
                Installment::create([
                    'finance_calculation_id' => $financeCalculation->id,
                    'installment_number' => $installmentData['installment_number'],
                    'year' => $installmentData['year'],
                    'outstanding_balance' => $installmentData['outstanding_balance'],
                    'profit_amount' => $installmentData['profit_amount'],
                    'principal_amount' => $installmentData['principal_amount'],
                    'insurance_amount' => $installmentData['insurance_amount'],
                    'total_installment' => $installmentData['cash_flow'],
                    'outstanding_plus_insurance' => $installmentData['outstanding_plus_insurance'],
                    'principal_plus_insurance' => $installmentData['principal_plus_insurance'],
                    'cash_flow' => $installmentData['cash_flow'],
                    'cf_percentage' => $installmentData['cf_percentage'],
                    'ftp_monthly' => $installmentData['ftp_monthly'],
                ]);
            }

            DB::commit();
            return $financeCalculation;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * عرض سجل الحسابات
     */
   public function history(Request $request)
    {
        $query = FinanceCalculation::with(['carModel'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // فلترة حسب تاريخ البدء
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        // فلترة حسب تاريخ النهاية
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $calculations = $query->paginate(10);
        
        return view('finance.history', compact('calculations'));
    }
    public function editStatus($id)
{
    $calculation = FinanceCalculation::where('user_id', Auth::id())
        ->findOrFail($id);
    
    $statuses = [
        'pending' => 'قيد الانتظار',
        'sold' => 'تم البيع',
        'not_sold' => 'لم يتم البيع',
        'follow_up' => 'متابعة',
        'cancelled' => 'ملغي'
    ];
    
    return view('finance.edit-status', compact('calculation', 'statuses'));
}

/**
 * تحديث الحالة
 */
/**
 * تحديث الحالة باستخدام AJAX
 */
/**
 * تحديث الحالة باستخدام AJAX
 */
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,sold,not_sold,follow_up,cancelled',  
    ]);

    $calculation = FinanceCalculation::where('user_id', Auth::id())
        ->findOrFail($id);

    $calculation->update([
        'status' => $request->status,
    ]);

    // نصوص الحالة للعرض
    $statusText = [
        'pending' => 'قيد الانتظار',
        'sold' => 'تم البيع',
        'not_sold' => 'لم يتم البيع',
        'follow_up' => 'متابعة',
        'cancelled' => 'ملغي'
    ];

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث الحالة بنجاح',
        'data' => [
            'id' => $calculation->id,
            'status' => $calculation->status,
            'status_text' => $statusText[$calculation->status] ?? $calculation->status,
        ]
    ]);
}


/**
 * الحصول على بيانات الحالة (لـ AJAX)
 */
public function getStatus($id)
{
    $calculation = FinanceCalculation::where('user_id', Auth::id())
        ->findOrFail($id);
    
    $statusText = [
        'pending' => 'قيد الانتظار',
        'sold' => 'تم البيع',
        'not_sold' => 'لم يتم البيع',
        'follow_up' => 'متابعة',
        'cancelled' => 'ملغي'
    ];
    
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $calculation->id,
            'status' => $calculation->status,
            'status_text' => $statusText[$calculation->status] ?? $calculation->status,
            'notes' => $calculation->notes,
            'car_brand' => $calculation->car_brand,
            'car_model' => $calculation->carModel ? $calculation->carModel->model_year : 'غير محدد'
        ]
    ]);
}
// public function updateStatus(Request $request, $id)
// {
//     $request->validate([
//         'status' => 'required|in:pending,sold,not_sold,follow_up,cancelled',
//         'notes' => 'nullable|string|max:1000'
//     ]);
    
//     $calculation = FinanceCalculation::where('user_id', Auth::id())
//         ->findOrFail($id);
    
//     // حفظ الحالة السابقة للتاريخ
//     $oldStatus = $calculation->status;
    
//     $calculation->update([
//         'status' => $request->status,
//         'notes' => $request->notes
//     ]);
    
//     // تسجيل تغيير الحالة في جدول منفصل (اختياري)
//     $this->logStatusChange($calculation->id, $oldStatus, $request->status, $request->notes, Auth::id());
    
//     return redirect()->route('finance.history')
//         ->with('success', 'تم تحديث حالة الحساب بنجاح');
// }

    /**
     * عرض تفاصيل حساب معين
     */
   public function show($id)
{
    $calculation = FinanceCalculation::with(['installments', 'carModel'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);
    
    // إضافة نص الحالة للعرض
    $statusText = [
        'pending' => 'قيد الانتظار',
        'sold' => 'تم البيع',
        'not_sold' => 'لم يتم البيع',
        'follow_up' => 'متابعة',
        'cancelled' => 'ملغي'
    ];
    
    $calculation->status_text = $statusText[$calculation->status] ?? $calculation->status;
    
    return view('finance.show', compact('calculation'));
}

    

    /**
 * إرسال النتائج عبر واتساب
 */
public function sendWhatsAppMessage($id)
{
    // $financeCalculation = FinanceCalculation::findOrFail($id);
        $financeCalculation = FinanceCalculation::with(['carModel', 'installments'])->findOrFail($id);
    // إعداد نص الرسالة
    $message = $this->prepareWhatsAppMessage($financeCalculation);
    
    // تشفير النص لرابط واتساب
    $encodedMessage = urlencode($message);
    
    // معالجة رقم الهاتف
    $phone = $financeCalculation->phone;
    // إزالة أي رموز غير رقمية من رقم الهاتف
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    // التأكد من بدء الرقم بـ 966 (للسعودية)
    if (substr($cleanPhone, 0, 3) !== '966' && strlen($cleanPhone) == 9) {
        $cleanPhone = '966' . $cleanPhone;
    } elseif (substr($cleanPhone, 0, 4) == '+966') {
        $cleanPhone = substr($cleanPhone, 1);
    } elseif (substr($cleanPhone, 0, 1) == '0') {
        $cleanPhone = '966' . substr($cleanPhone, 1);
    }
    
    $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    
    return redirect()->away($whatsappUrl);
}

/**
 * إعداد نص رسالة واتساب
 */
// private function prepareWhatsAppMessage($financeCalculation)
// {
//     $message = "📊 *شركة العربه الفريده للسيارات*\n\n";
//    // $message .= "🕒 تاريخ الحساب: " . $financeCalculation->created_at->format('Y-m-d H:i') . "\n";
//     // $message .= "👤 العميل: " . $financeCalculation->gender_display . "\n";
//   //  $message .= "📞 الهاتف: " . $financeCalculation->phone . "\n\n";
    
//     $message .= "🚗 *معلومات السيارة:*\n";
//     $message .= " نوع السيارة: " . $financeCalculation->car_brand . "\n";
//    // $message .= "سعر السيارة: " . number_format($financeCalculation->car_price, 0) . " ر.س\n\n";
//      $message .= "الموديل: " . ($financeCalculation->carModel->name ?? 'غير محدد') . "\n";
//     $message .= "💳 *معلومات التمويل:*\n";
//     $message .= "الدفعة الأولى: " . number_format($financeCalculation->down_payment_amount, 0) . " ر.س\n";
//     $message .= "مدة التمويل: " . $financeCalculation->loan_term_months . " شهر\n";
//     $message .= "القسط الشهري : *" . number_format($financeCalculation->monthly_installment_with_insurance, 0) . " ر.س*\n";
//     $message .= "الدفعة الأخيرة: " . number_format($financeCalculation->final_payment_amount, 0) . " ر.س\n\n";
//     $message .= "الإجمالي الكلي: " . number_format($financeCalculation->grand_total, 0) . " ر.س\n\n";
    
//     // $message .= "مبلغ التمويل: " . number_format($financeCalculation->financed_amount, 0) . " ر.س\n";
//    // $message .= "📈 *التكاليف:*\n";
//     // $message .= "إجمالي الربح: " . number_format($financeCalculation->total_profit, 0) . " ر.س\n";
//     // $message .= "إجمالي التأمين: " . number_format($financeCalculation->total_insurance, 0) . " ر.س\n\n";
    
//    // $message .= "💰 *النتائج النهائية:*\n";
//     //$message .= "القسط الشهري (بدون تأمين): " . number_format($financeCalculation->monthly_installment_without_insurance, 0) . " ر.س\n";
    
//     //$message .= "📊 *ملخص النسب:*\n";
//     // $message .= "نسبة هامش الربح: " . $financeCalculation->profit_margin_percentage . "%\n";
//     // $message .= "نسبة التأمين: " . $financeCalculation->insurance_rate_percentage . "%\n";
//     // $message .= "نسبة الدفعة الأولى: " . $financeCalculation->down_payment_percentage . "%\n";
//     // $message .= "نسبة الدفعة الأخيرة: " . $financeCalculation->final_payment_percentage . "%\n\n";
    
//     // $message .= "🔗 *رابط النتائج الكاملة:*\n";
//     // $message .= route('finance.show', ['id' => $financeCalculation->id]) . "\n\n";
//    $message .= "\n─────────────────────\n";
//     $message .= "العنوان: خميس مشيط حي المعارض";
    
//     return $message;
// }

//بنك الراجحي

private function prepareWhatsAppMessage($financeCalculation)
{
    $message = "📊 *شركة العربه الفريده للسيارات*\n";
    $message .= "─────────────────────\n";
    
    $message .= "\n🚗 *معلومات السيارة:*\n";
    $message .= "🚙 نوع السيارة: " . ($financeCalculation->car_brand ?? 'غير محدد') . "\n";
    $message .= "📅 الموديل: " . ($financeCalculation->carModel->model_year ?? 'غير محدد') . "\n";
    
    $message .= "\n⚠️ *هذه النتائج تقريبية*\n";
    $message .= "─────────────────────\n";
    
    $message .= "💰 الدفعة الأولى: " . number_format($financeCalculation->down_payment_amount, 0) . " ر.س\n";
    $message .= "⏳ مدة التمويل: " . $financeCalculation->loan_term_months . " شهر\n";
    $message .= "💳 القسط الشهري: *" . number_format($financeCalculation->monthly_installment_with_insurance, 0) . " ر.س*\n";
    $message .= "🔚 الدفعة الأخيرة: " . number_format($financeCalculation->final_payment_amount, 0) . " ر.س\n";
    $message .= "🏷️ الإجمالي الكلي: " . number_format($financeCalculation->grand_total, 0) . " ر.س\n";
    
    $message .= "\n─────────────────────\n";
    $message .= "📍 *موقعنا:* خميس مشيط حي المعارض\n";
    // $message .= "🗺️ رابط الخريطة:\n";
    // $message .= "https://maps.app.goo.gl/2MiE1RG5YpMzymEd6";
    
    return $message;
}

  public function destroy($id)
    {
        $calculation = FinanceCalculation::where('user_id', Auth::id())
            ->findOrFail($id);
        
        // حذف التقسيطات المرتبطة
        $calculation->installments()->delete();
        
        // حذف الحساب
        $calculation->delete();
        
        return redirect()->route('finance.history')
            ->with('success', 'تم حذف الحساب بنجاح');
    }
}