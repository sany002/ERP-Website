<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'bay_id' => ['nullable', 'exists:bays,id'],
            'assigned_mechanic_id' => ['nullable', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'priority' => [Rule::in(['low', 'normal', 'high', 'urgent'])],
            'odometer_reading' => ['nullable', 'integer', 'min:0'],
            'complaint_description' => ['nullable', 'string'],
            'estimated_completion_at' => ['nullable', 'date'],

            'items' => ['nullable', 'array'],
            'items.*.type' => ['required_with:items', Rule::in(['labor', 'part'])],
            'items.*.name' => ['required_with:items', 'string', 'max:150'],
            'items.*.quantity' => ['required_with:items', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required_with:items', 'numeric', 'min:0'],
            'items.*.mechanic_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
