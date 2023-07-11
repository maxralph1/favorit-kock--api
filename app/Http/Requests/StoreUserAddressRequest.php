<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressRequest extends FormRequest
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
            'user_id' => 'required',
            'house_number' => 'required|max:25',
            'street' => 'required|max:75',
            'city' => 'required|max:75',
            'post_code' => 'required|max:25',
            'state' => 'required|max:75',
            'landmark' => 'required|max:255',
            'default' => 'nullable|boolean',
        ];
    }
}
