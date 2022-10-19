<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalArea;
use App\Models\Servicio;
use App\Models\Persona;
use DB;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\PersonalGrupo;

class PersonalAreaController extends Controller
{
    public function index(Request $request)
    {
        $supestructura = parameter_alias_value('sup-estructura');
        return response()->json($supestructura);
    }
    public function getDependencia(Request $request, $cod)
    {
        $dependencias = CmsParameter::select('alias as name', 'value')->where('metadata->cod_supestructura', '=', $cod)->get();
        return response()->json($dependencias);
    }
    public function get_empleados(Request $request, $id)
    {
        $principal = PersonalGrupo::join('personal_area', 'personal_area.id', 'personal_grupo.personal_area_id')
            ->select('personal_grupo.id', 'dependencia.alias as dependencia', 'supestructura.alias as supestructura', 'personal_area.area', 'personas.nombres', 'personas.cargo', 'personas.dni', 'personal_area.supestructura_id', 'personal_area.dependencia_id')
            ->where('personal_grupo.personal_area_id', '=', $id)
            ->where('personal_grupo.area_servicio', 1)
            ->join('personas', 'personas.dni', 'personal_grupo.dni')
            ->join('cms_parameters as supestructura', 'supestructura.value', 'personal_area.supestructura_id')
            ->join('cms_parameters as dependencia', 'dependencia.value', 'personal_area.dependencia_id')
            ->get();
        return response()->json($principal, 200);
    }
    public function search_grupo(Request $request)
    {
        $de = $request->dependencia;
        $sup = $request->supestructura;
        $area = $request->area;
        $principal = PersonalArea::where('dependencia_id', $de)
            ->where('supestructura_id', $sup)
            ->where('area', $area)->first();
        if (!empty($principal)) {
            $supestructura = CmsParameter::select('alias')->where('value', '=', $sup)->get();
            $dependencia = CmsParameter::select('alias')->where('value', '=', $de)->get();
            $principal->dependencia = $dependencia[0]['alias'];
            $principal->supestructura = $supestructura[0]['alias'];
            return response()->json($principal, 200);
        }
        return response()->json();
    }
    public function search_areas(Request $request)
    {
        $de = $request->dependencia;
        $sup = $request->supestructura;
        $area = $request->area;
        $areas = PersonalArea::select('area as name', 'id')
            ->where('dependencia_id', $de)
            ->where('supestructura_id', $sup)
            ->where('area', '!=', $area)
            ->get();

        return response()->json($areas);
    }
    public function create(Request $request)
    {
        $user = $request->user();
        $superstructura = $user->supestructuras;
        $status = 1;
        foreach ($request->items as $value) {
            if (!isset($value['id'])) {
                $dni = $value['dni'];
                $principal = PersonalArea::select('dependencia.alias as dependencia', 'supestructura.alias as supestructura', 'personal_area.area', 'personas.nombres')->where('persona_dni', '=', $dni)
                    ->join('personas', 'personas.dni', 'personal_area.persona_dni')
                    ->join('cms_parameters as supestructura', 'supestrucura.id', 'personal_area.supestructura_id')
                    ->join('cms_parameters as dependencia', 'dependencia.id', 'personal_area.dependencia_id')
                    ->first();
                if (!empty($principal)) {
                    $status = 0;
                    $personalArea = new PersonalArea();
                    $personalArea->persona_dni = $value['dni'];
                    $personalArea->servicio_id = $value['servicio_id'];
                    $personalArea->dependencia_id = $value['dependencia_id'];
                    $personalArea->superstructura_id = $superstructura[0]['id'];
                    $personalArea->area_principal = $status;
                    $personalArea->area = $request->area;
                    $personalArea->save();
                    return response()->json($principal, 202);
                }
                $personalArea = new PersonalArea();
                $personalArea->persona_dni = $value['dni'];
                $personalArea->servicio_id = $value['servicio_id'];
                $personalArea->dependencia_id = $value['dependencia_id'];
                $personalArea->superstructura_id = $superstructura[0]['id'];
                $personalArea->area_principal = $status;
                $personalArea->area = $request->area;
                $personalArea->save();
            }
        }
    }
    public function get_personal(Request $request, $id)
    {
        $personal = PersonalGrupo::where('personal_area_id', $id);
        $array = $personal->pluck('dni');
        $empleados_query = Persona::select('nombres as name', 'dni')->where('status', '!=', 2)
            ->whereNotIn('dni', $array)
            ->get();
        return response()->json($empleados_query);
    }
    public function get_grupo(Request $request, $id)
    {
        $area = PersonalArea::find($id);
        $supestructura = CmsParameter::select('alias')->where('value', '=', $area->supestructura_id)->get();
        $dependencia = CmsParameter::select('alias')->where('value', '=', '022401000000')->get();
        $area->dependencia = $dependencia[0]['alias'];
        $area->supestructura = $supestructura[0]['alias'];
        return response()->json($area);
    }
    public function create_personal(Request $request)
    {
        $status = 1;
        $dni = $request->dni;
        $validate = PersonalGrupo::where('dni', $dni)->first();
        if (!empty($validate)) {

            $status = 0;
            $personalGrupo = new PersonalGrupo();
            $personalGrupo->personal_area_id = $request->personal_area_id;
            $personalGrupo->dni = $request->dni;
            $personalGrupo->area_servicio = $status;
            $personalGrupo->save();
            //obtener datos
            $id_personal_area = $validate->personal_area_id;
            $principal = PersonalArea::select('personal_area.id', 'supestructura.alias as supestructura', 'dependencia.alias as dependencia', 'personal_area.area',)
                ->join('cms_parameters as supestructura', 'supestructura.value', 'personal_area.supestructura_id')
                ->join('cms_parameters as dependencia', 'dependencia.value', 'personal_area.dependencia_id')
                ->where('personal_area.id', '=', $id_personal_area)
                ->first();
            $persona = Persona::find($dni);
            $principal->nombres = $persona['nombres'];
            return response()->json($principal, 202);
        }
        $personalGrupo = new PersonalGrupo();
        $personalGrupo->personal_area_id = $request->personal_area_id;
        $personalGrupo->dni = $request->dni;
        $personalGrupo->area_servicio = $status;
        $personalGrupo->save();
        return response()->json($personalGrupo);
    }
    public function create_group(Request $request)
    {
        $personalArea = new PersonalArea();
        $personalArea->dependencia_id = $request->dependencia;
        $personalArea->supestructura_id = $request->supestructura;
        $personalArea->area = $request->area;
        $personalArea->save();
        return response()->json(['id' => $personalArea->id]);
    }
    public function delete($id)
    {
        $personal = PersonalGrupo::FindOrFail($id);
        $personal->delete();
    }
}
