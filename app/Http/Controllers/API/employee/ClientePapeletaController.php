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
    public function create(Request $request)
    {
        $periodo = $request->periodo;
        $persona = $request->user()->username;
        $id = $request->user()->id;
        $fecha = now();
        $year = $fecha->year;

        $ultimaPapeleta = Papeleta::where('anio', $year)
            ->orderBy('nro_papeleta', 'desc')
            ->first();

        $nroPapeleta = is_null($ultimaPapeleta) ? 0 : $ultimaPapeleta->nro_papeleta;
        $nroPapeleta++;

        $fecha_salida = is_null($request->fecha_salida) ? Carbon::now() : Carbon::parse($request->fecha_salida);
        $fecha_retorno = is_null($request->fecha_retorno) ? Carbon::now() : Carbon::parse($request->fecha_retorno);

        $hora_salida = is_null($request->hora_salida) ? Carbon::now() : Carbon::parse($request->hora_salida);
        $hora_retorno = is_null($request->hora_retorno) ? Carbon::now() : Carbon::parse($request->hora_retorno);


        $nro_dias = $fecha_retorno->diffInDays($fecha_salida);

        $nro_minutos = $hora_retorno->diffInMinutes($hora_salida);

        $dni = $request->dni;
        $vinculo_laboral = get_vinculo_laboral($dni);
        // $vinculo_laboral = Contratos::select('id')->where('empleado_dni', '=', '001307427')->get();

        // if ($vinculo_laboral == 'error') {
        //     return response()->json(['msg' => 'ocurrio un error al buscar el contrato'], 202);
        // } else {
        $papeleta = Papeleta::create([
            'anio' => $year,
            'fecha' => $fecha,
            'fecha_salida' => $fecha_salida,
            'fecha_retorno' => $fecha_retorno,
            'tdm' => $nro_minutos,
            'tdd' => $nro_dias,
            'status' => 0,
            'nro_papeleta' => $nroPapeleta,
            'created_by' => $id,
            // 'vinculo_laboral' => $vinculo_laboral
        ] + $request->all());
        // $papeleta->actividad_vinculo($vinculo_laboral);


        if (is_null($papeleta->dni))
            $papeleta->dni = $persona;

        if ($papeleta->save()) {
            if ($papeleta->tipo_permiso_id == 4) {
                $this->crearVacacionDocumento($papeleta, $periodo);
                $papeleta->vacaciones_status = 1;
                $papeleta->save();
            }
        }
        $user = $request->user();
        // notificarAdd($user, 'Papeleta', $papeleta->id);
        return response()->json(200);
    }
    public function crearVacacionDocumento($papeleta, $periodo)
    {
        $dni = $papeleta->dni;
        $vinculo_laboral = get_vinculo_laboral($dni);
        $vacacion = VacacionesDocumento::create([
            'periodo' => $periodo,
            'persona_dni' => $papeleta->dni,
            'fecha_inicio' => $papeleta->fecha_salida,
            'fecha_final' => $papeleta->fecha_retorno,
            'nro_dias' => $papeleta->tdd,
            'tipo_documento_id' => 4,
            'papeleta_id' => $papeleta->id,
            'estado_valido' => 4,
            'vinculo_laboral' => $vinculo_laboral
        ]);
        $vacacion->save();
    }
    public function getDetail(Request $request, Papeleta $papeleta)
    {
        $dni = $papeleta->dni;
        $type_id = $papeleta->tipo_permiso_id;
        $user = DB::select('SELECT CONCAT(nombres, " ",apellido_paterno," ", apellido_materno) as name, dni as id FROM personas where dni = ?', [$dni]);
        $type = DB::select('SELECT tipo_permiso as name, id FROM tipo_permisos where id = ?', [$type_id]);
        $data = [
            'papeleta' => $papeleta,
            'user' => $user[0],
            'type' => $type[0]
        ];
        return response()->json($data);
    }
}
