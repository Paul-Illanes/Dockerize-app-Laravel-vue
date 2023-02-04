<?php

namespace App\Http\Controllers\API\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Papeleta;

class ClientePapeletaController extends Controller
{
    public function index(Request $request)
    {
        $dni = $request->user()->username;
        // var_dump($request->user());
        $papeletas = Papeleta::join('tipo_permisos', 'tipo_permisos.id', '=', 'papeletas.tipo_permiso_id')
            ->select('papeletas.id', 'papeletas.anio', 'papeletas.fecha', 'papeletas.nro_papeleta', 'papeletas.dni', 'personas.nombres', 'personas.dependencia', 'papeletas.fecha_salida', 'papeletas.hora_salida', 'papeletas.fecha_retorno', 'papeletas.hora_retorno', 'papeletas.status', 'tipo_permisos.tipo_permiso')
            // ->vinculo('papeletas.id')
            ->join('personas', 'personas.dni', '=', 'papeletas.dni')
            ->where('papeletas.dni', $dni)
            // ->supestructura($user_id)
            ->get();
        return response()->json($papeletas);
    }
}
