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
use App\Models\User;

class PersonalAreaController extends Controller
{
    public function list(Request $request)
    {
        // $list = PersonalArea::select('personal_areas.id', 'personal_areas.dependencia_id', 'dependencia.alias as dependencia', 'personal_areas.supestructura_id', 'supestructura.alias as supestructura', 'personal_areas.area')
        //     ->join('cms_parameters as supestructura', 'supestructura.id', 'personal_areas.supestructura_id')
        //     ->join('cms_parameters as dependencia', 'dependencia.id', 'personal_areas.dependencia_id')
        //     ->get();
        $list = PersonalArea::select('personal_areas.id', 'personal_areas.dependencia_id', 'personal_areas.supestructura_id', 'personal_areas.area', 'dependencia.alias AS dependencia', 'supestructura.alias AS supestructura')
            ->join('cms_parameters AS supestructura', 'supestructura.value', 'personal_areas.supestructura_id')
            ->join('cms_parameters AS dependencia', 'dependencia.value', 'personal_areas.dependencia_id')
            ->get();
        return response()->json($list);
    }
    public function index(Request $request)
    {
        $supestructura = parameter_alias_value('sup-estructura');
        return response()->json($supestructura);
    }
    public function getDependencia(Request $request)
    {
        $supEstructura_id = $request->cod; // 'A010'
        $groupId = "dependencia";  // 'dependencia'
        $parentId = 12;
        $dependencias = CmsParameter::select('alias AS name', 'value')
            ->whereIn('group_id', CmsParameterGroup::where('alias', $groupId)->pluck('id'))
            ->where('parent_id', $parentId)
            ->where('metadata->cod_supestructura', $supEstructura_id)
            ->where('active', true)
            ->orderBy('name')->get();

        // $cod = $request->cod;
        // $dependencias = CmsParameter::select('alias as name', 'value')->where('metadata->cod_supestructura', $cod)->get();
        return response()->json($dependencias);
    }
    public function get_empleados(Request $request, $id)
    {
        $principal = PersonalGrupo::join('personal_areas', 'personal_areas.id', 'personal_grupos.personal_area_id')
            ->select('personal_grupos.id', 'dependencia.alias as dependencia', 'supestructura.alias as supestructura', 'personal_areas.area', 'personas.nombres', 'personas.cargo', 'personas.dni', 'personal_areas.supestructura_id', 'personal_areas.dependencia_id', 'personal_grupos.area_servicio', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.personal_area_id', '=', $id)
            ->where('personal_grupos.area_servicio', 1)
            ->join('personas', 'personas.dni', 'personal_grupos.dni')
            ->join('cms_parameters as supestructura', 'supestructura.value', 'personal_areas.supestructura_id')
            ->join('cms_parameters as dependencia', 'dependencia.value', 'personal_areas.dependencia_id')
            ->get();
        return response()->json($principal, 200);
    }
    public function search_grupo(Request $request, $id)
    {
        $area = PersonalArea::find($id);
        $supestructura = CmsParameter::select('alias')->where('value', '=', $area->supestructura_id)->get();
        $dependencia = CmsParameter::select('alias')->where('value', '=', $area->dependencia_id)->get();
        $area->dependencia = $dependencia[0]['alias'];
        $area->supestructura = $supestructura[0]['alias'];
        return response()->json($area);
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
                $principal = PersonalArea::select('dependencia.alias as dependencia', 'supestructura.alias as supestructura', 'personal_areas.area', 'personas.nombres')->where('persona_dni', '=', $dni)
                    ->join('personas', 'personas.dni', 'personal_areas.persona_dni')
                    ->join('cms_parameters as supestructura', 'supestructura.id', 'personal_areas.supestructura_id')
                    ->join('cms_parameters as dependencia', 'dependencia.id', 'personal_areas.dependencia_id')
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
    public function edit_group(Request $request)
    {
        $est = $request->supestructura_id;
        $dep = $request->dependencia_id;
        $area = $request->area;
        $id = $request->id;
        $request->validate([
            'supestructura_id' => 'required',
            'dependencia_id' => 'required',
            'area' => [
                'required',
                Rule::unique('personal_areas')->where(function ($query) use ($id, $est, $dep, $area) {
                    $query->where('supestructura_id', $est);
                    $query->where('dependencia_id', $dep);
                    $query->where('area', $area);
                    $query->where('id', '!=', $id);
                })
            ],
        ]);

        $personalArea = PersonalArea::find($id);
        $personalArea->dependencia_id = $dep;
        $personalArea->supestructura_id = $est;
        $personalArea->area = $area;
        $personalArea->save();
        return response()->json(['id' => $personalArea->id]);
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
        $dependencia = CmsParameter::select('alias')->where('value', '=', $area->dependencia_id)->get();
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
            $principal = PersonalArea::select('personal_areas.id', 'supestructura.alias as supestructura', 'dependencia.alias as dependencia', 'personal_areas.area',)
                ->join('cms_parameters as supestructura', 'supestructura.value', 'personal_areas.supestructura_id')
                ->join('cms_parameters as dependencia', 'dependencia.value', 'personal_areas.dependencia_id')
                ->where('personal_areas.id', '=', $id_personal_area)
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
        $est = $request->supestructura_id;
        $dep = $request->dependencia_id;
        $area = $request->area;
        $request->validate([
            'supestructura_id' => 'required',
            'dependencia_id' => 'required',
            'area' => [
                'required',
                Rule::unique('personal_areas')->where(function ($query) use ($est, $dep, $area) {
                    $query->where('supestructura_id', $est);
                    $query->where('dependencia_id', $dep);
                    $query->where('area', $area);
                })
            ],
        ]);

        $personalArea = new PersonalArea();
        $personalArea->dependencia_id = $dep;
        $personalArea->supestructura_id = $est;
        $personalArea->area = $area;
        $personalArea->save();
        return response()->json(['id' => $personalArea->id]);
    }
    public function delete($id)
    {
        $personal = PersonalGrupo::FindOrFail($id);
        $personal->delete();
    }
    public function cambio_area(Request $request)
    {
        //ELIMINAR EXISTENTE
        $id = $request->id;
        $personal = PersonalGrupo::FindOrFail($id);
        $personal->delete();
        //REGISTRAR EN NUEVA AREA
        $area = new PersonalGrupo();
        $area->dni = $request->dni;
        $area->personal_area_id = $request->personal_area_id;
        $area->area_servicio = $request->area_servicio;
        $area->save();

        return response()->json($area);
    }
    public function audits(Request $request, $id)
    {
        $personalArea = PersonalArea::find($id);
        $updatedHistory = $personalArea->audits()
            ->whereIn('event', ['updated', 'created'])
            ->latest()->get();
        $i = 0;
        foreach ($updatedHistory as $value) {
            $id = $value->user_id;
            $user = User::find($id);
            $updatedHistory[$i]->user = $user;
            $i++;
        }
        return response()->json($updatedHistory);
    }
}
