<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GateLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'registration_number' => ['required_without:vehicle_id', 'string', 'max:50'],
            'job_card_id' => ['nullable', 'exists:job_cards,id'],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'odometer_reading' => ['nullable', 'integer', 'min:0'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
