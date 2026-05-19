<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConstructionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'volume' => ['nullable','numeric'],
            'date' => ['nullable','date'],
            'notes' => ['nullable','string'],
        ];
    }
}
