<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],

            // Set stock to an absolute value (e.g., after physical count)
            'new_quantity' => ['required', 'integer', 'min:0'],

            'reason' => ['nullable', 'string', 'max:190'],
        ];
    }
}
