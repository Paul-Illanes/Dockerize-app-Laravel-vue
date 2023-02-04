<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CambioTurno;
use App\Models\PersonalGrupo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalRolDetalle;
use App\Models\EssiSubactividades;

class CambioTurnoController extends Controller
{
    public function getList(Request $request)
    {
        $user_id = $request->user()->id;
        $data = CambioTurno::select('solicitante.dependencia', 'solicitante.nombres as solicitante_nombre', 'aceptante.nombres as aceptante_nombre', 'cambio_turnos.anio', 'cambio_turnos.cambio_fecha', 'cambio_turnos.cambio_ingreso', 'cambio_turnos.cambio_salida', 'cambio_turnos.cod_planilla', 'cambio_turnos.created_at', 'cambio_turnos.created_by', 'cambio_turnos.dni', 'cambio_turnos.fecha', 'cambio_turnos.id', 'cambio_turnos.motivo', 'cambio_turnos.nombre_jefe', 'cambio_turnos.nombre_servicio', 'cambio_turnos.nro_papeleta', 'cambio_turnos.numero_correlativo', 'cambio_turnos.observacion_essi_id', 'cambio_turnos.origen_fecha', 'cambio_turnos.origen_ingreso', 'cambio_turnos.origen_salida', 'cambio_turnos.registro_essi', 'cambio_turnos.solicitante_id', 'cambio_turnos.status', 'cambio_turnos.turno_cambio', 'cambio_turnos.turno_origen', 'cambio_turnos.updated_at', 'cambio_turnos.updated_by')
            ->aceptante('cambio_turnos.aceptante_id')
            ->solicitante('cambio_turnos.solicitante_id')
            ->latest('cambio_turnos.created_at')
            ->vinculo('cambio_turnos.id')
            ->supestructura($user_id)
            ->orderBy('cambio_turnos.fecha', 'DESC')
            ->get();
        return response()->json($data);
    }
    public function verificar_solicitante($dni)
    {
        $cantidad = CambioTurno::countSolicitado($dni);
        return response()->json($cantidad);
    }
    public function verificar_aceptante($dni)
    {
        $cantidad = CambioTurno::countAceptado($dni);
        return response()->json($cantidad);
    }
    public function store(Request $request)
    {

        $fecha = now();
        $year = $fecha->year;

        $ultimaPapeleta = CambioTurno::where('anio', $year)
            ->orderBy('nro_papeleta', 'desc')
            ->first();
        // validando el null
        $nroPapeleta = is_null($ultimaPapeleta) ? 0 : $ultimaPapeleta->nro_papeleta;
        $nroPapeleta++;

        $dni = $request->solicitante_id;
        $vinculo_laboral = get_vinculo_laboral($dni);
        $cambioTurno = CambioTurno::create([
            'anio' => $year,
            'fecha' => $fecha,
            'nro_papeleta' => $nroPapeleta,
            'created_by' => $request->user()->id,
            // 'vinculo_laboral' => $vinculo_laboral

        ] + $request->all());

        if (is_null($cambioTurno->solicitante_id))
            $cambioTurno->solicitante_id = $request->user()->username;

        if ($cambioTurno->save()) {
            $id_rol_detalle_aceptante = $request->rol_aceptante;
            $id_rol_detalle_solicitante = $request->rol_solicitante;
            //cambiar horarios 
            $aceptante = PersonalRolDetalle::find($id_rol_detalle_aceptante);
            $aceptante->persona_dni = $request->aceptante_id;
            $aceptante->cambio_turno = 1;
            $aceptante->save();
            //solicitante
            $solicitante = PersonalRolDetalle::find($id_rol_detalle_solicitante);
            $solicitante->persona_dni = $request->solicitante_id;
            $solicitante->cambio_turno = 1;
            $solicitante->save();
        }

        // $user = $request->user();
        // notificarAdd($user, 'Cambio de turno', $cambioTurno->id);
        return response()->json(['msg' => 'registrado correctamente']);
    }
    public function getDetail(Request $request, $id)
    {
        $data = CambioTurno::find($id);
        $solicitante_name = DB::select('SELECT nombres as name FROM personas where dni = ?', [$data->solicitante_id]);
        $aceptante_name = DB::select('SELECT nombres as name FROM personas where dni = ?', [$data->aceptante_id]);
        // var_dump($data->id);
        $data->nombre_aceptante = $aceptante_name[0]->name;
        $data->nombre_solicitante = $solicitante_name[0]->name;
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {
        $turno = CambioTurno::FindOrFail($id);
        $turno->nombre_jefe = $request->nombre_jefe;
        $turno->nombre_servicio = $request->nombre_servicio;
        $turno->solicitante_id = $request->solicitante_id;
        $turno->aceptante_id = $request->aceptante_id;;
        $turno->turno_origen = $request->turno_origen;
        $turno->turno_cambio = $request->turno_cambio;
        $turno->origen_fecha = $request->origen_fecha;
        $turno->origen_ingreso = $request->origen_ingreso;
        $turno->origen_salida = $request->origen_salida;
        $turno->cambio_fecha = $request->cambio_fecha;
        $turno->cambio_ingreso = $request->cambio_ingreso;
        $turno->cambio_salida = $request->cambio_salida;
        $turno->motivo = $request->motivo;
        $turno->updated_by = $request->user()->id;
        $turno->save();
        $user = $request->user();
        notificarEdit($user, 'Cambio de turno', $turno->id);
    }
    public function observar(Request $request, $id)
    {
        $turno = CambioTurno::FindOrFail($id);
        $turno->status = 2;
        $turno->obs = $request->observacion_id;
        $turno->updated_by = $request->user()->id;
        $turno->save();
    }
    public function update_estado(Request $request, $id)
    {
        $turno = CambioTurno::FindOrFail($id);
        $turno->status = $request->status;
        $turno->save();
    }
    public function delete($id)
    {
        $turno = CambioTurno::FindOrFail($id);
        $turno->delete();
    }
    public function getRol(Request $request, $id)
    {
        $area = PersonalGrupo::select('personal_areas.id', 'personal_grupos.dni')
            ->join('personal_areas', 'personal_areas.id', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.dni', $id)
            ->where('personal_grupos.area_servicio', 1)
            ->first();

        $rol_detalle = PersonalRolDetalle::select('essi_subactividades.id AS id', DB::raw('CONCAT(essi_subactividades.actividad," - ", personal_rol_detalles.fecha_turno) AS name'), 'essi_subactividades.actividad', 'personal_rol_detalles.fecha_turno', 'personal_rol_detalles.hora_inicio', 'personal_rol_detalles.hora_fin', 'personal_rol_detalles.cantidad_horas')
            ->join('essi_subactividades', 'essi_subactividades.id', 'personal_rol_detalles.actividad_id')
            ->where('personal_rol_detalles.area_id', $area->id)
            ->where('personal_rol_detalles.persona_dni', $area->dni)
            ->get();

        // $rol = PersonalRolDetalle::join('essi_subactividades', 'essi_subactividades.id', 'personal_rol_detalles.actividad_id')->where('fecha_turno', $fechaxactual)->where('persona_dni', $dni)->first();
        return response()->json($rol_detalle);
    }
    public function getActividades(Request $request)
    {
        $acti = EssiSubactividades::select('actividad AS name', 'id')->get();
        return response()->json($acti);
    }
    public function getFechas(Request $request)
    {
        $dni = $request->id;
        $actividad = $request->actividad;
        // var_dump($request->all());
        $area = PersonalGrupo::select('personal_areas.id', 'personal_grupos.dni')
            ->join('personal_areas', 'personal_areas.id', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.dni', $dni)
            ->where('personal_grupos.area_servicio', 1)
            ->first();
        $rol_detalle = PersonalRolDetalle::select('personal_rol_detalles.id AS id', 'essi_subactividades.id AS actividad_id', 'personal_rol_detalles.fecha_turno AS name', 'essi_subactividades.actividad', 'personal_rol_detalles.fecha_turno', 'personal_rol_detalles.hora_inicio', 'personal_rol_detalles.hora_fin', 'personal_rol_detalles.cantidad_horas', 'personal_rol_detalles.persona_dni')
            ->join('essi_subactividades', 'essi_subactividades.id', 'personal_rol_detalles.actividad_id')
            ->where('personal_rol_detalles.area_id', $area->id)
            ->where('personal_rol_detalles.persona_dni', $dni)
            ->where('personal_rol_detalles.actividad_id', $actividad)
            ->get();
        return response()->json($rol_detalle);
    }
}
