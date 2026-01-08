<?php

namespace App\Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // SKU uniqueness handled in Action (ignore current product) or via Rule::unique with route binding.
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'sku' => ['sometimes', 'string', 'max:60'],
            'description' => ['nullable', 'string'],

            'price' => ['sometimes', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
