<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'meal_id' => 'nullable',
            'order_id' => 'nullable',
            'user_id' => 'nullable',
            'amount_due' => 'nullable|numeric',
            'quantity_ordered' => 'nullable|numeric',
        ];
    }
}
