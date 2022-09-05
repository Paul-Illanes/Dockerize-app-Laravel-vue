<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;

class PersonaControllers extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $personas = Persona::latest()
            ->supestructura($user_id)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return response()->json($personas);
    }
    public function personList()
    {
        $empleados_query = Persona::select('nombres as name', 'dni as id')->where('status', '!=', 2)->get();
        return response()->json($empleados_query);
    }
}
