<?php

namespace App\Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Policy check is done in controller (clean separation)
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:60', 'unique:products,sku'],
            'description' => ['nullable', 'string'],

            // Accept money as "price" (e.g. 2500) then convert to cents in action.
            // If you prefer already-cents, rename to price_cents.
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
