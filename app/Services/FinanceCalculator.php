<?php

namespace App\Services;

class FinanceCalculator
{
    // public function calculate($data)
    // {
    //     // تحقق من وجود البيانات المطلوبة
    //     $carPrice = floatval($data['car_price']);
    //     $downPaymentPercent = floatval($data['down_payment_percent']);
    //     $loanTerm = intval($data['loan_term']);
    //     $profitMargin = floatval($data['profit_margin']);
    //     $adminFeePercent = floatval($data['admin_fee_percent']);
    //     $finalPaymentPercent = floatval($data['final_payment_percent']);
    //     $gender = $data['gender'];
    //     $age = $data['age'];
    //     $carBrand = $data['car_brand'];
    //     $insuranceRates = $data['insuranceRates'];
    //     $carCategories = $data['carCategories'];

    //     // حساب القيم المشتقة
    //     $downPaymentAmount = $carPrice * ($downPaymentPercent / 100);
    //     $financeAmount = max(0, $carPrice - $downPaymentAmount);
    //     $adminFeeAmount = min(5000, $financeAmount * ($adminFeePercent / 100));
    //     $finalPaymentAmount = $carPrice * ($finalPaymentPercent / 100);

    //     // حساب فئة السيارة
    //     $carCategory = $carCategories[$carBrand] ?? 'C';

    //     // حساب معدل التأمين
    //     $genderKey = $gender === 'ذكر' ? 'male' : 'female';
    //     $insuranceRate = $insuranceRates[$genderKey][$age][$carCategory] ?? 0;

    //     // حساب مبلغ التأمين (نفس منطق Excel)
    //     $insuranceAmount = $this->calculateInsuranceAmount($carPrice, $insuranceRate, $loanTerm);

    //     // حساب القسط الشهري
    //     $monthlyRate = ($profitMargin / 100) / 12;
    //     $installment = $this->pmt($monthlyRate, $loanTerm, -$financeAmount, $finalPaymentAmount);
    //     $monthlyInsurance = $loanTerm > 0 ? $insuranceAmount / $loanTerm : 0;
    //     $monthlyWithInsurance = $installment + $monthlyInsurance;

    //     // حساب إجمالي الربح
    //     $totalProfit = $installment * $loanTerm + $finalPaymentAmount - $financeAmount;
    //     $flatProfitRate = $financeAmount > 0 ? ($totalProfit / $financeAmount / $loanTerm) * 12 : 0;

    //     // حساب IRR و APR (مبسطة)
    //     $irrAnnual = $this->calculateIRR($financeAmount, $installment, $finalPaymentAmount, $loanTerm);
    //     $aprAnnual = $this->calculateAPR($financeAmount, $installment, $finalPaymentAmount, $loanTerm, $adminFeeAmount, $insuranceAmount);

    //     // صافي الربح
    //     $netProfit = $totalProfit - $adminFeeAmount;

    //     // الإجمالي النهائي
    //     $grandTotal = $downPaymentAmount + ($monthlyWithInsurance * $loanTerm) + $finalPaymentAmount;

    //     // جدول الأقساط
    //     $schedule = $this->generateSchedule($financeAmount, $installment, $finalPaymentAmount, $loanTerm, $insuranceAmount, $profitMargin);

    //     return [
    //         'derived' => [
    //             'downPaymentAmount' => $downPaymentAmount,
    //             'financeAmount' => $financeAmount,
    //             'adminFeeAmount' => $adminFeeAmount,
    //             'finalPaymentAmount' => $finalPaymentAmount,
    //         ],
    //         'insurance' => [
    //             'carCategory' => $carCategory,
    //             'insuranceRate' => $insuranceRate,
    //             'insuranceAmount' => $insuranceAmount,
    //             'monthlyInsurance' => $monthlyInsurance,
    //         ],
    //         'results' => [
    //             'installment' => $installment,
    //             'monthlyWithInsurance' => $monthlyWithInsurance,
    //             'totalProfit' => $totalProfit,
    //             'flatProfitRate' => $flatProfitRate,
    //             'totalFees' => $adminFeeAmount,
    //             'totalInsurance' => $insuranceAmount,
    //             'remainingValue' => $finalPaymentAmount,
    //             'grandTotal' => $grandTotal,
    //             'aprAnnual' => $aprAnnual,
    //             'irrAnnual' => $irrAnnual,
    //             'netProfit' => $netProfit,
    //         ],
    //         'schedule' => $schedule,
    //     ];
    // }

    // private function calculateInsuranceAmount($carPrice, $insuranceRate, $loanTermMonths)
    // {
    //     // نفس منطق Excel
    //     $minPremium = 1650;
    //     $price = $carPrice;
    //     $rate = $insuranceRate;
    //     $term = $loanTermMonths;

    //     $y1 = max($price * $rate, $minPremium);
    //     $y2 = $price * 0.85 * $rate;
    //     $y3 = $price * pow(0.85, 2) * $rate;
    //     $y4 = $price * pow(0.85, 3) * $rate;
    //     $y5 = $price * pow(0.85, 4) * $rate;

    //     if ($term < 13) {
    //         $total = $y1;
    //     } elseif ($term < 25) {
    //         $total = $y1 + $y2;
    //     } elseif ($term < 37) {
    //         $total = $y1 + $y2 + $y3;
    //     } elseif ($term < 49) {
    //         $total = $y1 + $y2 + $y3 + $y4;
    //     } elseif ($term < 61) {
    //         $total = $y1 + $y2 + $y3 + $y4 + $y5;
    //     } else {
    //         $years = ceil($term / 12);
    //         $total = 0;
    //         for ($k = 0; $k < $years; $k++) {
    //             $yearly = $price * pow(0.85, $k) * $rate;
    //             $total += $k === 0 ? max($yearly, $minPremium) : $yearly;
    //         }
    //     }

    //     // تقريب إلى أقرب 0.01 (مثل دالة CEILING في Excel)
    //     return ceil($total / 0.01) * 0.01;
    // }

    // private function pmt($rate, $nper, $pv, $fv = 0, $type = 0)
    // {
    //     if ($rate == 0) {
    //         return (-$pv - $fv) / $nper;
    //     }

    //     $pvif = pow(1 + $rate, $nper);
    //     $pmt = (-$rate * ($pv * $pvif + $fv)) / ($pvif - 1);

    //     if ($type == 1) {
    //         $pmt /= (1 + $rate);
    //     }

    //     return $pmt;
    // }

    // private function calculateIRR($financeAmount, $installment, $finalPayment, $term)
    // {
    //     // حساب IRR بشكل مبسط (يمكن استبداله بحساب أكثر دقة)
    //     $totalPayments = $installment * $term + $finalPayment;
    //     $profit = $totalPayments - $financeAmount;
    //     $irr = ($profit / $financeAmount) / ($term / 12);
    //     return $irr;
    // }

    // private function calculateAPR($financeAmount, $installment, $finalPayment, $term, $adminFee, $insuranceAmount)
    // {
    //     // حساب APR مبسط
    //     $totalCost = ($installment * $term + $finalPayment + $adminFee + $insuranceAmount) - $financeAmount;
    //     $apr = ($totalCost / $financeAmount) / ($term / 12);
    //     return $apr;
    // }

    // private function generateSchedule($financeAmount, $installment, $finalPayment, $term, $insuranceAmount, $profitMargin)
    // {
    //     $schedule = [];
    //     $outstanding = $financeAmount;
    //     $monthlyInsurance = $insuranceAmount / $term;
    //     $monthlyRate = ($profitMargin / 100) / 12;

    //     for ($i = 1; $i <= $term; $i++) {
    //         $profit = $outstanding * $monthlyRate;
    //         $principal = $installment - $profit;

    //         if ($i == $term) {
    //             $principal = max(0, $outstanding - $finalPayment);
    //         }

    //         $principal = min($principal, $outstanding);
    //         $outstanding -= $principal;

    //         $cashFlow = $profit + $principal + $monthlyInsurance;

    //         $schedule[] = [
    //             'installmentNo' => $i,
    //             'year' => ceil($i / 12),
    //             'outstanding' => $outstanding,
    //             'profit' => $profit,
    //             'principal' => $principal,
    //             'insurance' => $monthlyInsurance,
    //             'cashFlow' => $cashFlow,
    //         ];
    //     }

    //     return $schedule;
    // }
}