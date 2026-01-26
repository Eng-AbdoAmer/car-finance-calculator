<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarFinancingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone'            => 'required|string|min:9',
            'bank_id'          => 'required|exists:banks,id',
            'car_brand_id'     => 'required|exists:car_brands,id',
            'car_model_id'     => 'required|exists:car_models,id',
            'car_price'        => 'required|numeric|min:1',
            'duration_months'  => 'required|in:12,24,36,48,60',
            'total_amount'     => 'required|numeric|min:1',
        ];
    }
}
