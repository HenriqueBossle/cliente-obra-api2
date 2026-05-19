<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'construction_name' => $this->construction_name,
            'builder_name' => $this->builder_name,
            'builder_phone' => $this->builder_phone,
            'cpf_cnpj' => $this->cpf_cnpj,
            'sitemanager_name' => $this->sitemanager_name,
            'sitemanager_phone' => $this->sitemanager_phone,
            'address' => $this->address,
            'type' => $this->type,
            'status' => $this->status,
            'volume' => $this->volume,
            'date' => $this->date,
            'notes' => $this->notes,
            'user_id' => $this->user_id
        ];
    }
}
