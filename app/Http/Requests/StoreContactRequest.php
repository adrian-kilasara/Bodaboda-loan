<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'        => ['required', 'string', 'max:150'],
            'phone'            => ['required', 'string', 'max:20'],
            'alternate_phone'  => ['nullable', 'string', 'max:20'],
            'relationship'     => ['required', Rule::in(['guarantor', 'next_of_kin', 'reference', 'other'])],
            'contract_id'      => ['nullable', 'exists:contracts,id'],
            'driver_id'        => ['nullable', 'exists:users,id'],
            'national_id'      => ['nullable', 'string', 'max:50'],
            'physical_address' => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ];
    }
}
