<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalInformesCese;
use App\Models\PersonalBaja;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;

class PersonalInformesCeseController extends Controller
{
    public function index()
    {
        $data = PersonalInformesCese::latest('created_at')->get();
        return response()->json($data);
    }

    public function getusers()
    {
        // // $personalInformesCese = new PersonalInformesCese();

        // // array with DNI for PersonalInformesCeses
        // $array_dni = PersonalInformesCese::pluck('dni');

        // // $empleados_query =Persona::where('status',2)->get(); // empleados dados de baja
        // $empleados_query = PersonalBaja::where('status', 1)
        //     ->whereNotIn('dni', $array_dni)
        //     ->get(); // empleados dados de baja

        // $empleados = [];
        // $i = 0;
        // foreach ($empleados_query as $empleado) {
        //     $empleados[$i]['dni'] = $empleado->persona->dni;
        //     $empleados[$i]['name'] = $empleado->persona->fullname;
        //     $i++;
        // }
        $personalInformesCese = new PersonalInformesCese();

        // array with DNI for PersonalInformesCeses
        $array_dni = PersonalInformesCese::pluck('dni');

        // $empleados_query =Persona::where('status',2)->get(); // empleados dados de baja
        $empleados_query = PersonalBaja::where('status', 1)
            ->whereNotIn('dni', $array_dni)
            ->get(); // empleados dados de baja

        //list to bajas foreach empleado
        $baja_empleados = [];
        $i = 0;
        foreach ($empleados_query as $empleado) {
            $baja_empleados[$i]['id'] = $empleado->id;
            $baja_empleados[$i]['name'] = $empleado->persona->fullname;
            $i++;
        }
        return response()->json($baja_empleados);
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->id;
        //request()->validate(PersonalInformesCese::$rules);

        // $baja = PersonalBaja::where('dni', $request->dni)->first();
        $baja = PersonalBaja::find($request->baja_id); // id de baja
        // $persona = Persona::find($request->dni);
        $persona = Persona::find($baja->dni);

        $personalInformesCese = PersonalInformesCese::create(
            [
                'dni'           => $persona->dni,
                'nombre'        => $persona->fullname,
                'fecha_ingreso' => $persona->fecha_ingreso,
                'fecha_cese'    => $baja->fecha_cese,
                'motivo_cese'   => $baja->motivoBaja->baja,
                'created_by'    => $user_id,
                'updated_by'    => $user_id,
            ] + $request->all()
        );
        // $user_id = $request->user()->id;

        // $persona = Persona::find($request->dni);
        // $baja = PersonalBaja::where('dni', $request->dni)->first();

        // $personalInformesCese = PersonalInformesCese::create(
        //     [
        //         'nombre' => $persona->fullname,
        //         'fecha_ingreso' => $persona->fecha_ingreso,
        //         'fecha_cese' => $baja->fecha_cese,
        //         'motivo_cese' => $baja->motivoBaja->baja,
        //         'created_by' => $user_id,
        //         'updated_by' => $user_id,
        //     ] + $request->all()
        // );
        return response()->json(['msg' => 'registrado correctamente']);
    }

    public function getDetail($id)
    {
        $informe = PersonalInformesCese::FindOrFail($id);
        // $persona = Persona::select('nombres')->where('dni', '=', $informe->dni)->get();
        // $informe->nombres = $persona[0]->nombres;
        return response()->json($informe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PersonalInformesCese $personalInformesCese
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $baja = PersonalBaja::find($request->baja_id); // id de baja
        $persona = Persona::find($baja->dni);
        $user_id = $request->user()->id;

        $personalInformesCese = PersonalInformesCese::findOrFail($id);
        $personalInformesCese->baja_id = $request->baja_id;
        $personalInformesCese->nombre = $persona->fullname;
        $personalInformesCese->dni = $persona->dni;
        $personalInformesCese->nit = $request->nit;
        $personalInformesCese->numero_informe = $request->numero_informe;
        $personalInformesCese->fecha_informe = $request->fecha_informe;
        $personalInformesCese->faltas = $request->faltas;
        $personalInformesCese->tardanzas = $request->tardanzas;
        $personalInformesCese->licencias = $request->licencias;
        $personalInformesCese->permisos_particulares = $request->permisos_particulares;
        $personalInformesCese->sancion_disciplinaria = $request->sancion_disciplinaria;
        $personalInformesCese->huelga = $request->huelga;
        $personalInformesCese->licencia_covid = $request->licencia_covid;
        $personalInformesCese->vacaciones_no_gozadas = $request->vacaciones_no_gozadas;
        $personalInformesCese->bono_pago = $request->bono_pago;
        $personalInformesCese->descuento_bono_pago = $request->descuento_bono_pago;
        $personalInformesCese->guardias = $request->guardias;
        $personalInformesCese->horas_extras = $request->horas_extras;
        $personalInformesCese->horas_rpct = $request->horas_rpct;
        $personalInformesCese->pct = $request->pct;
        $personalInformesCese->notas_adicionales = $request->notas_adicionales;
        $personalInformesCese->zona_menor_desarrollo = $request->zona_menor_desarrollo;
        $personalInformesCese->fecha_ingreso = $persona->fecha_ingreso;
        $personalInformesCese->fecha_cese = $baja->fecha_cese;
        $personalInformesCese->motivo_cese = $baja->motivo_cese;
        $personalInformesCese->updated_by = $user_id;
        $personalInformesCese->path_informe = "";
        $personalInformesCese->save();

        return response()->json(['msg' => 'actualizado correctamente']);
    }

    public function detalle($id)
    {
        $baja = PersonalBaja::find($id); // id de baja
        $persona = Persona::find($baja->dni);
        $data = [
            'nombre' => $persona->fullname,
            'dni' => $persona->dni,
            'codigo_planilla' => $persona->cod_planilla,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'fecha_ingreso' => $persona->fecha_ingreso,
            'cargo' => $persona->cargo,
            'regimen_laboral' => $persona->c_l,
        ];
        return response()->json($data, 200);
    }
    public function generarInforme(Request $request)
    {
        // dd($request->all());
        set_time_limit(300);
        $informe_id = $request->informe_id;

        $informe = PersonalInformesCese::find($informe_id);
        $dni = $informe->dni;


        $fecha_generacion = date('d-m-Y');
        $file_name = strtoupper("informe-cese-control-personal-$dni-$fecha_generacion") . '.pdf';

        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('personal-informes-cese.informe-pdf', compact('informe'))
            ->save(\storage_path('app/public/personal-informes-cese/') . $file_name);

        // update status
        PersonalInformesCese::where('id', $informe_id)->update([
            'path_informe' => $file_name,
        ]);

        return response()->json('hola', 200);
    }
    public function getfile($name)
    {
        $path = 'public/personal-informes-cese/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
    public function delete($id)
    {
        $informe = PersonalInformesCese::FindOrFail($id);
        $informe->delete();
    }
}
