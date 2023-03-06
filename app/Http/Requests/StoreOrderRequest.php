<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'nullable|string|min:11|max:20',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'order_sum' => 'required|numeric|gt:3000',
            'selected_products' => 'required'
        ];
    }
}
