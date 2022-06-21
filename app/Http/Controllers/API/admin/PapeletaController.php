<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Papeleta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PapeletaController extends Controller
{
    public function getList(Request $request)
    {
        $user_id = $request->user()->id;
        $papeletas = Papeleta::join('tipo_permisos', 'tipo_permisos.id', '=', 'papeletas.tipo_permiso_id')
            // ->join('personas', 'personas.dni', '=', 'papeletas.dni')
            ->select('papeletas.id', 'papeletas.anio', 'papeletas.fecha', 'papeletas.nro_papeleta', 'papeletas.dni', 'personas.nombres', 'personas.dependencia', 'papeletas.fecha_salida', 'papeletas.hora_salida', 'papeletas.fecha_retorno', 'papeletas.hora_retorno', 'papeletas.status', 'tipo_permisos.tipo_permiso')
            // ->where('papeletas.status', '1')
            ->supestructura($user_id)
            ->get();

        // $papeletas = Papeleta::all();
        $data = [
            'status' => 200,
            'data' => $papeletas
        ];
        return response()->json($data);
    }
    public function update_estado(Request $request, Papeleta $papeleta)
    {
        $papeleta->update([
            'status' => $request->status,
        ]);
        return response()->json(['status' => 200]);
    }

    public function create(Request $request)
    {
        $persona = $request->user()->username;
        $id = $request->user()->id;
        $fecha = now();
        $year = $fecha->year;

        $ultimaPapeleta = Papeleta::where('anio', $year)
            ->orderBy('nro_papeleta', 'desc')
            ->first();

        // validando si la papaleta es null
        $nroPapeleta = is_null($ultimaPapeleta) ? 0 : $ultimaPapeleta->nro_papeleta;
        $nroPapeleta++;

        $fecha_salida = is_null($request->fecha_salida) ? Carbon::now() : Carbon::parse($request->fecha_salida);
        $fecha_retorno = is_null($request->fecha_retorno) ? Carbon::now() : Carbon::parse($request->fecha_retorno);

        $hora_salida = is_null($request->hora_salida) ? Carbon::now() : Carbon::parse($request->hora_salida);
        $hora_retorno = is_null($request->hora_retorno) ? Carbon::now() : Carbon::parse($request->hora_retorno);
        // dd($hora_salida);
        // dd($hora_retorno);
        // dd($fecha_salida);
        //obtener dias /horas

        $nro_dias = $fecha_retorno->diffInDays($fecha_salida);

        $nro_minutos = $hora_retorno->diffInMinutes($hora_salida);
        // dd($dias);
        // dd($nro_minutos);


        $papeleta = Papeleta::create([
            'anio' => $year,
            'fecha' => $fecha,
            'fecha_salida' => $fecha_salida,
            'fecha_retorno' => $fecha_retorno,
            'tdm' => $nro_minutos,
            'tdd' => $nro_dias,
            'status' => 0,
            'nro_papeleta' => $nroPapeleta,
            'created_by' => $id

        ] + $request->all());

        if (is_null($papeleta->dni))
            $papeleta->dni = $persona;

        $papeleta->save();
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
    public function update(Request $request, Papeleta $papeleta)
    {
        $papeleta->update($request->all());
        $papeleta->updated_by = $request->user()->id; // set user id whose changed

        // $papeleta->fecha_salida = is_null($request->fecha_salida) ? now() : $request->fecha_salida;
        // $papeleta->fecha_retorno = is_null($request->fecha_retorno) ? now() : $request->fecha_retorno;

        $fecha_salida = is_null($request->fecha_salida) ? Carbon::now() : Carbon::parse($request->fecha_salida);
        $fecha_retorno = is_null($request->fecha_retorno) ? Carbon::now() : Carbon::parse($request->fecha_retorno);

        $hora_salida = is_null($request->hora_salida) ? Carbon::now() : Carbon::parse($request->hora_salida);
        $hora_retorno = is_null($request->hora_retorno) ? Carbon::now() : Carbon::parse($request->hora_retorno);

        $nro_dias = $fecha_retorno->diffInDays($fecha_salida);
        $nro_minutos = $hora_retorno->diffInMinutes($hora_salida);

        // actualizando data
        $papeleta->fecha_salida = $fecha_salida;
        $papeleta->fecha_retorno = $fecha_retorno;

        $papeleta->hora_salida = $hora_salida;
        $papeleta->hora_retorno = $hora_retorno;
        $papeleta->tdm = $nro_minutos;
        $papeleta->tdd = $nro_dias;

        // dd($papeleta);

        $papeleta->save();
    }
    public function delete(Papeleta $papeleta)
    {
        $papeleta->delete();
    }
}
