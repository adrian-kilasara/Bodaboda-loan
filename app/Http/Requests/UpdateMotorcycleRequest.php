<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $motorcycle = $this->route('motorcycle');
        return [
            'registration_number' => ['required', 'string', 'max:20', Rule::unique('motorcycles', 'registration_number')->ignore($motorcycle->id)],
            'make'                => ['required', 'string', 'max:50'],
            'model'               => ['required', 'string', 'max:50'],
            'manufacture_year'    => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'engine_number'       => ['nullable', 'string', 'max:50', Rule::unique('motorcycles', 'engine_number')->ignore($motorcycle->id)],
            'chassis_number'      => ['nullable', 'string', 'max:50', Rule::unique('motorcycles', 'chassis_number')->ignore($motorcycle->id)],
            'color'               => ['nullable', 'string', 'max:30'],
            'purchase_price'      => ['required', 'numeric', 'min:1'],
            'purchase_date'       => ['nullable', 'date'],
            'status'              => ['required', 'in:available,on_loan,repossessed,sold,maintenance'],
            'notes'               => ['nullable', 'string', 'max:1000'],
        ];
    }
}
