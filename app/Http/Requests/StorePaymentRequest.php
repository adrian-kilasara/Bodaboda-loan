<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'             => ['required', 'numeric', 'min:1'],
            'payment_date'       => ['required', 'date'],
            'channel'            => ['required', Rule::in(['cash', 'mpesa', 'tigopesa', 'airtel', 'halopesa', 'bank', 'other'])],
            'external_reference' => ['nullable', 'string', 'max:100'],
            'notes'              => ['nullable', 'string', 'max:500'],
        ];
    }
}
