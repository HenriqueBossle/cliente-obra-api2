<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use HasFactory;

     protected $fillable = [
        "id",
        "construction_name",
        "builder_name",
        "builder_phone",
        "cpf_cnpj",
        "sitemanager_name",
        "sitemanager_phone",
        "address",  
        "type",
        "status",
        "volume",
        "date",
        "notes"
    ];

}
