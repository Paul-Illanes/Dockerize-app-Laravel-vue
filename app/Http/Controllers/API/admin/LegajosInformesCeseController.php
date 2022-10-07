<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LegajosInformesCese;
use App\Models\Persona;
use App\Models\PersonalBaja;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;
use Illuminate\Support\Facades\Storage;
use App\Models\LegajosLicencias;

class LegajosInformesCeseController extends Controller
{
    public function index()
    {
        $data = LegajosInformesCese::latest('created_at')->get();
        return response()->json($data);
    }

    public function getusers()
    {
        // $personalInformesCese = new PersonalInformesCese();

        $personalInformesCese = new LegajosInformesCese();

        // array with DNI for PersonalInformesCeses
        $array_dni = LegajosInformesCese::pluck('dni');

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
    public function select(Request $request)
    {
        // $grupo_ocupacional_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('grupo-ocupacional');
        // $regimen_laboral_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('regimen-laboral');
        // $regimen_pensionario_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('regimen-pensionario');
        // $linea_carrera_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('linea-carrera');
        // $condicion_laboral_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('condicion-laboral');
        // $modalidad_contrato_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('modalidad-contrato');
        // $dependencia_list = app('App\Http\Controllers\API\ParameterController')->parameter_pluck('depedencia');
        $grupo_ocupacional_list = $this->parameter_pluck('grupo-ocupacional');
        $regimen_laboral_list = $this->parameter_pluck('regimen-laboral');
        $regimen_pensionario_list = $this->parameter_pluck('regimen-pensionario');
        $linea_carrera_list = $this->parameter_pluck('linea-carrera');
        $condicion_laboral_list = $this->parameter_pluck('condicion-laboral');
        $modalidad_contrato_list = $this->parameter_pluck('modalidad-contrato');
        $dependencia_list = $this->parameter_pluck('depedencia');

        $data = [
            'grupo_ocupacional' => $grupo_ocupacional_list,
            'regimen_laboral' => $regimen_laboral_list,
            'regimen_pensionario' => $regimen_pensionario_list,
            'linea_carrera' => $linea_carrera_list,
            'condicion_laboral' => $condicion_laboral_list,
            'modalidad_contrato' => $modalidad_contrato_list,
            'dependencia' => $dependencia_list,
        ];
        // var_dump($data);
        return response()->json($data, 200);
    }
    public function parameter_pluck($group_alias, $parent_id = null)
    {
        $datos = CmsParameter::select('alias AS name', 'id')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('id')
            ->get();
        return $datos;
    }
    public function create(Request $request)
    {
        $user_id = $request->user()->id;

        $baja = PersonalBaja::find($request->baja_id); // id de baja
        // $persona = Persona::find($request->dni);
        $persona = Persona::find($baja->dni);
        $legajoInformesCese = LegajosInformesCese::create(
            [
                'nombre' => $persona->fullname,
                'dni' => $persona->dni,
                'fecha_ingreso' => $persona->fecha_ingreso,
                'fecha_cese' => $baja->fecha_cese,
                'ultimo_dia_labor' => $baja->fecha_ultimo_dia,
                'motivo_cese' => $baja->motivoBaja->baja,
                'created_by' => $user_id,
                'updated_by' => $user_id,
            ] + $request->all()
        );
        $id = $legajoInformesCese->id;
        $motivo = $request->lincencia_array;
        foreach ($motivo as $val) {
            $licencias = new LegajosLicencias();
            $licencias->legajos_informe_cese_id = $id;
            $licencias->parameter_id = $val['licencia']['id'];
            $licencias->fecha_inicio = $val['fecha_inicio'];
            $licencias->fecha_termino = $val['fecha_termino'];
            $licencias->save();
        }
        $user = $request->user();
        notificarAdd($user, 'Legajo Inforne de cese', $legajoInformesCese->id);
        return response()->json(200);
    }

    public function getDetail($id)
    {
        $legajo = LegajosInformesCese::FindOrFail($id);
        $licencias = LegajosLicencias::where('legajos_informe_cese_id', '=', $id)->get();
        // $persona = Persona::select('nombres')->where('dni', '=', $informe->dni)->get();
        // $informe->nombres = $persona[0]->nombres;
        $data = [
            'legajo' => $legajo,
            'licen' => $licencias
        ];
        return response()->json($data);
    }
    public function modal(Request $request)
    {
        $regimen_laboral = CmsParameter::select('id', 'value as name')->where('id', '=', $request->regimen_laboral)->get();
        $grupo_ocupacional = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->grupo_ocupacional)->get();
        $regimen_pensionario = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->regimen_pensionario)->get();
        $linea_carrera = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->linea_carrera)->get();
        $condicion_laboral = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->condicion_laboral)->get();
        $modalidad_contrato = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->modalidad_contrato)->get();
        $dependencia = CmsParameter::select('id', 'alias as name')->where('id', '=', $request->dependencia)->get();
        $data = [
            'regimen_laboral' => $regimen_laboral,
            'grupo_ocupacional' => $grupo_ocupacional,
            'regimen_pensionario' => $regimen_pensionario,
            'linea_carrera' => $linea_carrera,
            'condicion_laboral' => $condicion_laboral,
            'modalidad_contrato' => $modalidad_contrato,
            'dependencia' => $dependencia,
        ];
        return response()->json($data);
    }
    public function modalName(Request $request)
    {
        $regimen_laboral = CmsParameter::where('id', '=', $request->regimen_laboral)->pluck('value');
        $grupo_ocupacional = CmsParameter::where('id', '=', $request->grupo_ocupacional)->pluck('value');
        $regimen_pensionario = CmsParameter::where('id', '=', $request->regimen_pensionario)->pluck('value');
        $linea_carrera = CmsParameter::where('id', '=', $request->linea_carrera)->pluck('value');
        $condicion_laboral = CmsParameter::where('id', '=', $request->condicion_laboral)->pluck('value');
        $modalidad_contrato = CmsParameter::where('id', '=', $request->modalidad_contrato)->pluck('value');
        $dependencia = CmsParameter::where('id', '=', $request->dependencia)->pluck('value');

        $data = [
            'regimen_laboral' => $regimen_laboral,
            'grupo_ocupacional' => $grupo_ocupacional,
            'regimen_pensionario' => $regimen_pensionario,
            'linea_carrera' => $linea_carrera,
            'condicion_laboral' => $condicion_laboral,
            'modalidad_contrato' => $modalidad_contrato,
            'dependencia' => $dependencia,
        ];
        return response()->json($data);
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
        $user_id = $request->user()->id;

        $baja = PersonalBaja::find($request->baja_id); // id de baja
        $persona = Persona::find($baja->dni);
        $legajoInformesCese = LegajosInformesCese::findOrFail($id);
        $legajoInformesCese->baja_id = $request->baja_id;
        $legajoInformesCese->nombre = $persona->fullname;
        $legajoInformesCese->dni = $persona->dni;
        $legajoInformesCese->nit = $request->nit;
        $legajoInformesCese->numero_informe = $request->numero_informe;
        $legajoInformesCese->fecha_informe = $request->fecha_informe;
        $legajoInformesCese->fecha_ingreso = $persona->fecha_ingreso;
        $legajoInformesCese->codigo_planilla = $request->codigo_planilla;
        $legajoInformesCese->fecha_nacimiento = $request->fecha_nacimiento;
        $legajoInformesCese->regimen_laboral = $request->regimen_laboral;
        $legajoInformesCese->grupo_ocupacional = $request->grupo_ocupacional;
        $legajoInformesCese->numero_documento_cese = $request->numero_documento_cese;
        $legajoInformesCese->regimen_pensionario = $request->regimen_pensionario;
        $legajoInformesCese->linea_carrera = $request->linea_carrera;
        $legajoInformesCese->condicion_laboral = $request->condicion_laboral;
        $legajoInformesCese->modalidad_contrato = $request->modalidad_contrato;
        $legajoInformesCese->dependencia = $request->dependencia;
        $legajoInformesCese->licencia_sg_haber = $request->licencia_sg_haber;
        $legajoInformesCese->sancion_disciplinaria = $request->sancion_disciplinaria;
        $legajoInformesCese->licencia_covid = $request->licencia_covid;
        $legajoInformesCese->permisos_particulares = $request->permisos_particulares;
        $legajoInformesCese->acuenta_vacaciones = $request->acuenta_vacaciones;
        $legajoInformesCese->tiempo_servicio = $request->tiempo_servicio;
        $legajoInformesCese->total_tpo_deducible = $request->total_tpo_deducible;
        $legajoInformesCese->total_tpo_essalud = $baja->total_tpo_essalud;
        $legajoInformesCese->motivo_cese = $baja->motivo_cese;
        $legajoInformesCese->path_informe = "";
        $legajoInformesCese->updated_by = $user_id;
        $legajoInformesCese->save();
        //licencias
        // var_dump($request->lincencia_array);
        $id = $legajoInformesCese->id;
        $motivo = $request->lincencia_array;
        foreach ($motivo as $val) {
            $licencias = LegajosLicencias::find($val['id']);
            if ($licencias) {
                $licencias->legajos_informe_cese_id = $id;
                $licencias->parameter_id = $val['licencia']['id'];
                $licencias->fecha_inicio = $val['fecha_inicio'];
                $licencias->fecha_termino = $val['fecha_termino'];
                $licencias->save();
            } else {
                $licencias = new LegajosLicencias();
                $licencias->legajos_informe_cese_id = $id;
                $licencias->parameter_id = $val['licencia']['id'];
                $licencias->fecha_inicio = $val['fecha_inicio'];
                $licencias->fecha_termino = $val['fecha_termino'];
                $licencias->save();
            }
        }
        $user = $request->user();
        notificarEdit($user, 'Legajo Informe de Cese', $legajoInformesCese->id);
        return response()->json(['msg' => 'actualizado correctamente']);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    // public function destroy($id)
    // {
    //     $personalInformesCese = PersonalInformesCese::find($id)->delete();

    //     return redirect()->route('personal-informes-ceses.index')
    //         ->with('status', 'PersonalInformesCese eliminado con éxito');
    // }

    /**
     * return data personal
     */


    public function generarInforme(Request $request)
    {
        // set_time_limit(300);
        $informe_id = $request->informe_id;

        $informe = LegajosInformesCese::find($informe_id);
        $dni = $informe->dni;


        $fecha_generacion = date('d-m-Y');
        $file_name = strtoupper("informe-cese-legajos-$dni-$fecha_generacion") . '.pdf';

        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('legajos-informes-cese.informe-pdf', compact('informe'))
            ->save(\storage_path('app/public/legajos-informes-cese/') . $file_name);

        // update status
        LegajosInformesCese::where('id', $informe_id)->update([
            'path_informe' => $file_name,
        ]);
        // dd($request->all());
        return response()->json('hola', 200);
    }
    public function getfile($name)
    {
        $path = 'public/legajos-informes-cese/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
    public function delete($id)
    {
        $informe = LegajosInformesCese::FindOrFail($id);
        $informe->delete();
    }
    public function delete_licencia($id)
    {
        $licencia = LegajosLicencias::FindOrFail($id);
        $licencia->delete();
    }
}
