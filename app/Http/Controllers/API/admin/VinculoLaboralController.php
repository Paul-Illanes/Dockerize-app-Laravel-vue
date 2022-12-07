<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VinculoLaboral;
use App\Models\Contratos;
use Illuminate\Support\Facades\DB;

class VinculoLaboralController extends Controller
{
    public function index(Request $request)
    {

        $vinculo = VinculoLaboral::select('vinculo_laboral.id', 'vinculo_laboral.status', 'personas.dni', 'personas.nombres', 'contratos.tipo_contrato', 'contratos.fecha_inicio', 'contratos.fecha_termino', 'personas.c_l')
            ->join('contratos', 'contratos.id', 'vinculo_laboral.contrato_id')
            ->join('personas', 'personas.dni', 'contratos.empleado_dni')
            ->get();
        return response()->json($vinculo);
    }
    public function getContratos(Request $request)
    {

        $contratos = contratos::select(DB::raw("CONCAT(personas.nombres, ' - ', contratos.empleado_dni) AS name"), 'contratos.id')
            ->join('personas', 'personas.dni', 'contratos.empleado_dni')
            ->get();
        return response()->json($contratos);
    }
    public function store(Request $request)
    {

        $vinculo = new  VinculoLaboral();
        $vinculo->contrato_id = $request->contrato_id;
        $vinculo->status = $request->status;
        $vinculo->save();
        return response()->json($vinculo);
    }
    public function edit(Request $request, $id)
    {
        $vinculo = VinculoLaboral::find($id);
        $vinculo->contrato_id = $request->contrato_id;
        $vinculo->status = $request->status;
        $vinculo->save();
        return response()->json($vinculo);
    }
    public function delete(Request $request, $id)
    {
        $vinculo = VinculoLaboral::find($id);
        $vinculo->delete();
        return response()->json(200);
    }
}
