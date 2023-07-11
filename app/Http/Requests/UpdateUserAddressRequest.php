<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAddressRequest extends FormRequest
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
            'user_id' => 'nullable',
            'house_number' => 'nullable|max:25',
            'street' => 'nullable|max:75',
            'city' => 'nullable|max:75',
            'post_code' => 'nullable|max:25',
            'state' => 'nullable|max:75',
            'landmark' => 'nullable|max:255',
            'default' => 'nullable|boolean',
        ];
    }
}
