<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsuranceRate;
use App\Models\Brand;
use App\Models\AgeBracket;
use App\Models\CarSegment;

class InsuranceController extends Controller
{
    /**
     * حساب سعر التأمين بناءً على المدخلات
     */
    public function calculateRate(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:male,female',
            'age' => 'required|string',
            'brand' => 'required|string'
        ]);

        try {
            // البحث عن العلامة التجارية
            $brand = Brand::where('name', 'like', '%' . $validated['brand'] . '%')->first();
            
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'العلامة التجارية غير موجودة في قاعدة البيانات'
                ], 404);
            }

            // البحث عن الفئة العمرية
            $ageBracket = AgeBracket::where('name', $validated['age'])->first();
            
            if (!$ageBracket) {
                return response()->json([
                    'success' => false,
                    'message' => 'الفئة العمرية غير موجودة'
                ], 404);
            }

            // البحث عن سعر التأمين
            $insuranceRate = InsuranceRate::where([
                'gender' => $validated['gender'],
                'age_bracket_id' => $ageBracket->id,
                'car_segment_id' => $brand->carSegment->id
            ])->first();

            if (!$insuranceRate) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يوجد سعر تأمين متوفر لهذه المعطيات'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'insurance_rate' => $insuranceRate->rate,
                    'brand' => $brand->name,
                    'car_segment' => $brand->carSegment->segment,
                    'segment_description' => $brand->carSegment->description,
                    'age_bracket' => $ageBracket->name,
                    'gender' => $validated['gender'] == 'male' ? 'ذكر' : 'أنثى'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الخادم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على جميع العلامات التجارية لقسم معين
     */
    public function getBrandsBySegment($segment)
    {
        try {
            $carSegment = CarSegment::where('segment', strtoupper($segment))->first();

            if (!$carSegment) {
                return response()->json([
                    'success' => false,
                    'message' => 'القسم غير موجود'
                ], 404);
            }

            $brands = $carSegment->brands;

            return response()->json([
                'success' => true,
                'data' => [
                    'segment' => $carSegment->segment,
                    'description' => $carSegment->description,
                    'brands' => $brands
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الخادم'
            ], 500);
        }
    }

    /**
     * الحصول على جميع الأسعار لفئة عمرية وجنس معين
     */
    public function getRatesByAgeAndGender($age, $gender)
    {
        try {
            $ageBracket = AgeBracket::where('name', $age)->first();

            if (!$ageBracket) {
                return response()->json([
                    'success' => false,
                    'message' => 'الفئة العمرية غير موجودة'
                ], 404);
            }

            $rates = InsuranceRate::with('carSegment')
                ->where('gender', $gender)
                ->where('age_bracket_id', $ageBracket->id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'age_bracket' => $ageBracket->name,
                    'gender' => $gender == 'male' ? 'ذكر' : 'أنثى',
                    'rates' => $rates
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الخادم'
            ], 500);
        }
    }

    /**
     * البحث عن العلامات التجارية
     */
    public function searchBrands(Request $request)
    {
        $query = $request->input('query', '');

        $brands = Brand::with('carSegment')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    /**
     * الحصول على جميع الفئات العمرية
     */
    public function getAgeBrackets()
    {
        $ageBrackets = AgeBracket::all();

        return response()->json([
            'success' => true,
            'data' => $ageBrackets
        ]);
    }

    /**
     * الحصول على جميع أقسام السيارات
     */
    public function getCarSegments()
    {
        $carSegments = CarSegment::all();

        return response()->json([
            'success' => true,
            'data' => $carSegments
        ]);
    }
}