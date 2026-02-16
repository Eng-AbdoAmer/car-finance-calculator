<?php
// app/Http/Requests/UpdateCarRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getCarId(): ?int
    {
        $car = $this->route('car');
        if (is_object($car) && method_exists($car, 'getKey')) {
            return $car->getKey();
        }
        if (is_numeric($car)) {
            return (int) $car;
        }
        return $this->input('car_id') ? (int) $this->input('car_id') : null;
    }

    public function rules(): array
    {
        $carId = $this->getCarId();

        return [
            // الأرقام الفريدة - تتجاهل السيارة الحالية
            'chassis_number' => [
                'nullable', 'string', 'max:50',
                Rule::unique('cars', 'chassis_number')
                    ->ignore($carId, 'id')
                    ->whereNull('deleted_at')
            ],
            'plate_number' => [
                'nullable', 'string', 'max:20',
                Rule::unique('cars', 'plate_number')
                    ->ignore($carId, 'id')
                    ->whereNull('deleted_at')
            ],

            // العلاقات
            'car_brand_id'   => 'sometimes|required|exists:car_brands,id',
            'car_type_id'    => 'sometimes|required|exists:car_types,id',
            'car_trim_id'    => 'nullable|exists:car_trims,id',
            'car_category_id' => 'nullable|exists:car_categories,id',
            'car_status_id'  => 'sometimes|required|exists:car_statuses,id',
            'transmission_type_id' => 'sometimes|required|exists:transmission_types,id',
            'fuel_type_id'   => 'sometimes|required|exists:fuel_types,id',
            'drive_type_id'  => 'nullable|exists:drive_types,id',

            // المواصفات
            'model_year'     => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'color'          => 'sometimes|required|string|max:50',
            'condition'      => 'sometimes|required|in:new,used,salvage,refurbished',
            'mileage'        => 'sometimes|required|integer|min:0',
            'engine_capacity' => 'nullable|integer|min:0',
            'horse_power'    => 'nullable|integer|min:0',
            'cylinders'      => 'nullable|integer|min:0',
            'doors'          => 'nullable|integer|min:2|max:10',
            'seats'          => 'nullable|integer|min:2|max:20',
            'manufacturing_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),

            // الأسعار
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'selling_price'  => 'sometimes|required|numeric|min:0',

            // الصور
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'main_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',

            // النصوص
            'description'    => 'sometimes|required|string|min:10',
            'notes'          => 'nullable|string',

            // التواريخ
            'entry_date'     => 'sometimes|required|date',
            'purchase_date'  => 'nullable|date',

            // الكود - يتجاهل السيارة الحالية
            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('cars', 'code')
                    ->ignore($carId, 'id')
                    ->whereNull('deleted_at')
            ],

            // الخيارات
            'is_negotiable'    => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'is_financeable'   => 'nullable|boolean',
            'has_ownership'    => 'nullable|boolean',
            'has_insurance'    => 'nullable|boolean',
            'has_registration' => 'nullable|boolean',
            'has_maintenance_record' => 'nullable|boolean',

            'price_change_reason' => 'nullable|string|max:255',
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
            'color.required'          => 'اللون مطلوب.',
            'condition.required'      => 'الحالة (جديد/مستعمل) مطلوبة.',
            'mileage.required'        => 'عدد الكيلومترات مطلوب.',
            'purchase_price.required' => 'سعر الشراء مطلوب.',
            'selling_price.required'  => 'سعر البيع مطلوب.',
            'description.required'    => 'الوصف مطلوب.',
            'entry_date.required'     => 'تاريخ الإدخال مطلوب.',
            'code.unique'             => 'كود السيارة مستخدم بالفعل.',
            'chassis_number.unique'   => 'رقم الشاصي مستخدم بالفعل.',
            'plate_number.unique'     => 'رقم اللوحة مستخدم بالفعل.',
        ];
    }

    protected function prepareForValidation()
    {
        // تحويل التواريخ من صيغة d/m/Y إلى Y-m-d
        $dateFields = ['entry_date', 'purchase_date', 'inspection_date', 'registration_date', 'sale_date'];
        foreach ($dateFields as $field) {
            if ($this->has($field) && is_string($this->$field) && str_contains($this->$field, '/')) {
                $this->merge([
                    $field => Carbon::createFromFormat('d/m/Y', $this->$field)->format('Y-m-d')
                ]);
            }
        }

        // تحويل القيم المنطقية
        $booleans = ['is_negotiable', 'is_featured', 'is_financeable', 'has_ownership', 'has_insurance', 'has_registration', 'has_maintenance_record'];
        foreach ($booleans as $field) {
            $this->merge([
                $field => $this->boolean($field)
            ]);
        }
    }
}