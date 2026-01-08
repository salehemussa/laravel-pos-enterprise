<?php

namespace App\Modules\Reports\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DateRangeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // ISO date: 2026-01-01
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],

            // Optional pagination size
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:200'],
        ];
    }
}
