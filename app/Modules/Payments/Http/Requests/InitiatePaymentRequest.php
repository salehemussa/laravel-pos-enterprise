<?php

namespace App\Modules\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sale_id' => ['required', 'integer', 'exists:sales,id'],
            'provider' => ['required', 'in:cash,card,mpesa,tigopesa,airtelmoney'],
            'amount' => ['required', 'numeric', 'min:0.01'],

            // optional: phone number for mobile money
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
