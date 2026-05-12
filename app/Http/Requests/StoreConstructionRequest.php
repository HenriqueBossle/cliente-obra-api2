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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'construction_name' => ['required', 'string', 'max:255'],
            'builder_name' => ['required', 'string', 'max:255'],
            'builder_phone' => ['required', 'string'],
            'cpf_cnpj' => ['required', 'string'],
            'sitemanager_name' => ['required', 'string'],
            'sitemanager_phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'type' => ['required', 'string'],
            'status' => ['required', 'string'],
            'volume' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string']
        ];
    }
}
