<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConstructionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
    * Prepare input data before validation.
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => $this->input('startDate') ?? $this->input('start_date'),
            'finish_date' => $this->input('finishDate') ?? $this->input('finish_date'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'construction_name' => ['nullable','string', 'max:255'],
            'builder_name' => ['nullable','string', 'max:255'],
            'builder_phone' => ['nullable','string'],
            'cpf_cnpj' => ['nullable','string'],
            'sitemanager_name' => ['nullable','string'],
            'sitemanager_phone' => ['nullable','string'],
            'address' => ['nullable','string'],
            'type' => ['nullable','string'],
            'status' => ['nullable','string'],
            'concrete_volume' => ['nullable','numeric'],
            'mortar_volume' => ['nullable', 'numeric'], 
            'start_date' => ['nullable','date'],
            'finish_date' => ['nullable','date'],
            'notes' => ['nullable','string'],
        ];
    }
}
