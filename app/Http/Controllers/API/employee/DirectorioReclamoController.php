<?php

namespace App\Http\Controllers\API\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DirectorioReclamo;
use App\Models\User;

class DirectorioReclamoController extends Controller
{
    function getList()
    {
        $soporte = DirectorioReclamo::where('show_ayuda', 1)
            ->Join('users', 'users.id', '=', 'directorio_reclamos.user_id')->get();
        return response()->json($soporte);
    }
}
