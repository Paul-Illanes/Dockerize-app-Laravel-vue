<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Supestructura;

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
    public function estructura()
    {
        // $supestructura_list = Supestructura::all()->pluck('descripcion', 'id');
        $supestructura_list = Supestructura::select('descripcion as name', 'id')->get();
        return response()->json($supestructura_list);
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->id;
        $persona = Persona::create([
            'status' => 0,
            'forma_registro' => 'CRUD',
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ] + $request->all());
        $persona->save();
        return response()->json(200);
    }
    public function getDetail(Request $request, $id)
    {

        $data = Persona::find($id);
        return response()->json($data);
    }
    public function update(Request $request, $id)
    {
        $persona  = Persona::findOrFail($id);
        $persona->nombres = $request->nombres;
        $persona->dni = $request->dni;
        $persona->sub_estructura = $request->sub_estructura;
        $persona->estructura = $request->estructura;
        $persona->cargo = $request->cargo;
        $persona->cod_planilla = $request->cod_planilla;
        $persona->fecha_ingreso = $request->fecha_ingreso;
        $persona->fecha_nacimiento = $request->fecha_nacimiento;
        $persona->save();
    }
    public function delete($id)
    {
        $persona = Persona::FindOrFail($id);
        $persona->delete();
    }
    public function update_estado(Request $request, $id)
    {
        $persona = Persona::FindOrFail($id);
        $persona->status = $request->status;
        $persona->save();
    }
}
