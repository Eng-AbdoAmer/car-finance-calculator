<?php
// app/Http/Requests/StoreCarRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // الأرقام الفريدة
            'chassis_number' => [
                'nullable', 'string', 'max:100',
                Rule::unique('cars')->whereNull('deleted_at')
            ],
            'plate_number' => [
                'nullable', 'string', 'max:20',
                Rule::unique('cars')->whereNull('deleted_at')
            ],

            // العلاقات
            'car_brand_id'   => 'required|exists:car_brands,id',
            'car_type_id'    => 'required|exists:car_types,id',   // الموديل
            'car_trim_id'    => 'nullable|exists:car_trims,id',
            'car_category_id' => 'nullable|exists:car_categories,id',
            'car_status_id'  => 'required|exists:car_statuses,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'fuel_type_id'   => 'required|exists:fuel_types,id',
            'drive_type_id'  => 'nullable|exists:drive_types,id',

            // المواصفات
            'model_year'     => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color'          => 'required|string|max:50',
            'condition'      => 'required|in:new,used,salvage,refurbished',
            'mileage'        => 'required|integer|min:0',
            'engine_capacity' => 'nullable|integer|min:0',
            'horse_power'    => 'nullable|integer|min:0',
            'cylinders'      => 'nullable|integer|min:0',
            'doors'          => 'nullable|integer|min:2|max:10',
            'seats'          => 'nullable|integer|min:2|max:20',

            // الأسعار
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',

            // الصور
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:102400',
            'main_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',

            // نصوص
            'description'    => 'required|string|min:10',

            // التواريخ
            'purchase_date'     => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'car_brand_id.required'   => 'العلامة التجارية مطلوبة.',
            'car_type_id.required'    => 'الموديل مطلوب.',
            'car_status_id.required'  => 'حالة السيارة مطلوبة.',
            'transmission_type_id.required' => 'ناقل الحركة مطلوب.',
            'fuel_type_id.required'   => 'نوع الوقود مطلوب.',
            'model_year.required'     => 'سنة الموديل مطلوبة.',
            'color.required'          => 'لون السيارة مطلوب.',
            'condition.required'      => 'حالة السيارة (جديد/مستعمل) مطلوبة.',
            'mileage.required'        => 'الممشي مطلوب.',
            'purchase_price.required' => 'سعر الشراء مطلوب.',
            'selling_price.required'  => 'سعر البيع مطلوب.',
            'description.required'    => 'وصف السيارة مطلوب.',
            'purchase_date.required'  => 'تاريخ الشراء مطلوب.',
        ];
    }

    protected function prepareForValidation()
    {
        // تحويل التواريخ من صيغة d/m/Y إلى Y-m-d إذا وردت بهذه الصيغة
        $dateFields = ['purchase_date'];
        foreach ($dateFields as $field) {
            if ($this->has($field) && is_string($this->$field) && str_contains($this->$field, '/')) {
                $this->merge([
                    $field => Carbon::createFromFormat('d/m/Y', $this->$field)->format('Y-m-d')
                ]);
            }
        }
    }
}