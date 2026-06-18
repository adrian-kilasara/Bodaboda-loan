<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motorcycle_id'          => ['required', 'exists:motorcycles,id'],
            'principal_amount'       => ['required', 'numeric', 'min:1'],
            'markup_amount'          => ['required', 'numeric', 'min:0'],
            'down_payment'           => ['nullable', 'numeric', 'min:0'],
            'installment_amount'     => ['required', 'numeric', 'min:1'],
            'installment_frequency'  => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'number_of_installments' => ['required', 'integer', 'min:1', 'max:1000'],
            'penalty_type'           => ['required', Rule::in(['none', 'fixed', 'percentage'])],
            'penalty_amount'         => ['nullable', 'numeric', 'min:0'],
            'grace_period_days'      => ['nullable', 'integer', 'min:0'],
            'start_date'             => ['required', 'date'],
            'notes'                  => ['nullable', 'string', 'max:1000'],
        ];
    }
}
