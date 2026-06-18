<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_number' => ['required', 'string', 'max:20', 'unique:motorcycles,registration_number'],
            'make'                => ['required', 'string', 'max:50'],
            'model'               => ['required', 'string', 'max:50'],
            'manufacture_year'    => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'engine_number'       => ['nullable', 'string', 'max:50', 'unique:motorcycles,engine_number'],
            'chassis_number'      => ['nullable', 'string', 'max:50', 'unique:motorcycles,chassis_number'],
            'color'               => ['nullable', 'string', 'max:30'],
            'purchase_price'      => ['required', 'numeric', 'min:1'],
            'purchase_date'       => ['nullable', 'date'],
            'notes'               => ['nullable', 'string', 'max:1000'],
        ];
    }
}
