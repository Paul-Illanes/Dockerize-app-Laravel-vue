<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contratos;
use App\Models\Persona;

class ContratosController extends Controller
{
    public function index()
    {
        $contratos = Contratos::join('personas', 'personas.dni', '=', 'contratos.empleado_dni')
            ->select('personas.nombres', 'contratos.id', 'contratos.tipo_contrato', 'contratos.regimen_laboral', 'contratos.empleado_dni', 'contratos.empleado_ruc', 'contratos.empleado_direccion', 'contratos.cargo', 'contratos.lugar_prestacion_servicio', 'contratos.remuneracion', 'contratos.fecha_inicio', 'contratos.fecha_termino', 'contratos.fecha_firma_contrato', 'contratos.status_firma', 'contratos.status_contrato')
            ->get();
        return response()->json($contratos);
    }
    public function getPersona($id)
    {
        $persona = Persona::find($id);
        return response()->json($persona);
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->id;
        $contratos = Contratos::create(
            [
                'status_contrato' => 0,
                'status_firma' => 0,
                'created_by' => $user_id,
                'updated_by' => $user_id,
            ] + $request->all()
        );
        $user = $request->user();
        // notificarAdd($user, 'Contrato', $contratos->id);
        return response()->json($contratos);
    }
    public function getDetail(Request $request, $id)
    {
        $contratos = Contratos::find($id);
        $dni = $contratos->empleado_dni;
        $nombre = Persona::where('dni', '=', $dni)->pluck('nombres');
        $contratos->nombres = $nombre[0];
        return response()->json($contratos);
    }
    public function Update(Request $request, $id)
    {
        $user_id = $request->user()->id;
        $contrato = Contratos::find($id);
        $contrato->tipo_contrato = $request->tipo_contrato;
        $contrato->regimen_laboral = $request->regimen_laboral;
        $contrato->empleado_dni = $request->empleado_dni;
        $contrato->empleado_ruc = $request->empleado_ruc;
        $contrato->empleado_direccion = $request->empleado_direccion;
        $contrato->cargo = $request->cargo;
        $contrato->lugar_prestacion_servicio = $request->lugar_prestacion_servicio;
        $contrato->remuneracion = $request->remuneracion;
        $contrato->fecha_inicio = $request->fecha_inicio;
        $contrato->fecha_termino = $request->fecha_termino;
        $contrato->fecha_firma_contrato = $request->fecha_firma_contrato;
        $contrato->updated_by = $user_id;
        $contrato->save();
        $user = $request->user();
        notificarEdit($user, 'Contrato', $contrato->id);
        return response()->json(200);
    }
    public function status_firma(Request $request, $id)
    {
        $contratos = Contratos::FindOrFail($id);
        $contratos->status_firma = $request->status;
        $contratos->save();
    }
    public function status_contrato(Request $request, $id)
    {
        $contratos = Contratos::FindOrFail($id);
        $contratos->status_contrato = $request->status;
        $contratos->save();
    }
    public function delete(Request $request, $id)
    {
        $contrato = Contratos::find($id);
        $contrato->delete();
    }
}
