<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supestructura;

class SuperstructuraController extends Controller
{
    function getList()
    {
        $superstructuras = Supestructura::all();

        return response()->json($superstructuras);
    }
}
