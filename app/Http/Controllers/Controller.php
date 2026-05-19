<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController; // Importe a classe base de rotas


abstract class Controller extends BaseController
{
    use AuthorizesRequests;
}
