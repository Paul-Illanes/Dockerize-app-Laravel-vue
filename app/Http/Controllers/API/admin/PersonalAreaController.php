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

class PersonalAreaController extends Controller
{
    public function index(Request $request)
    {
        // $servicios = Servicio::select('servicio as name', 'id')->where('active', true)->get();
        $servicios = parameter_get_alias('servicios');
        return response()->json($servicios);
    }
    public function getList(Request $request)
    {
        $servicio_id = $request->servicio_id;
        $dependencia_id = $request->dependencia_id;
        $area = $request->area;
        $personalServicios = PersonalArea::select('personal_area.id', 'personal_area.persona_dni as dni', 'personas.nombres as name', 'personas.cargo', 'personal_area.area', 'personal_area.active', 'servicio.alias as servicio', 'servicio.id as servicio_id', 'dependencia.alias as dependencia', 'dependencia.id as dependencia_id')->servicioFilter($servicio_id)
            ->join('personas', 'personas.dni', 'personal_area.persona_dni')
            ->join('cms_parameters as servicio', 'servicio.id', 'personal_area.servicio_id')
            ->join('cms_parameters as dependencia', 'dependencia.id', 'personal_area.dependencia_id')
            ->where('dependencia_id', $dependencia_id)
            ->where('area', $area)
            ->orderBy('personal_area.updated_at', 'desc')
            ->get();
        // $personalServicios = PersonalArea::select('personal_area.id', 'personal_area.persona_dni as dni', 'personas.nombres as name', 'personas.cargo', 'personal_area.area', 'personal_area.active', 'servicio.alias as servicio', 'servicio.id as servicio_id', 'dependencia.alias as dependencia', 'dependencia.id as dependencia_id')
        //     ->join('personas', 'personas.dni', 'personal_area.persona_dni')
        //     ->where('area', $area)
        //     ->servicio('personal_area.servicio_id', $servicio_id)
        //     ->dependencia('personal_area.dependencia_id', $dependencia_id)
        //     ->get();
        // cert to array PersonalServicio data 
        $array = $personalServicios->pluck('dni');
        // filter employees, not baja and not in array (previusly recorded) 
        $empleados_query = Persona::select('nombres as name', 'dni', 'cargo')->where('status', '!=', 2)
            ->whereNotIn('dni', $array)
            ->get();

        $empleados = [];
        foreach ($empleados_query as $empleado) {
            $empleados[$empleado->dni] = $empleado->fullname;
        }
        $dependencia = parameter_get_alias('dependencia');

        $data = [
            'empleados' => $empleados_query,
            'personal_servicios' => $personalServicios,
            'dependencia' => $dependencia
        ];
        return response()->json($data);
    }
    public function create(Request $request)
    {
        $user = $request->user();
        $superstructura = $user->supestructuras;
        $status = 1;
        foreach ($request->items as $value) {
            if (!isset($value['id'])) {
                $dni = $value['dni'];
                $principal = PersonalArea::select('dependencia.alias as dependencia', 'servicio.alias as servicio', 'personal_area.area', 'personas.nombres')->where('persona_dni', '=', $dni)
                    ->join('personas', 'personas.dni', 'personal_area.persona_dni')
                    ->join('cms_parameters as servicio', 'servicio.id', 'personal_area.servicio_id')
                    ->join('cms_parameters as dependencia', 'dependencia.id', 'personal_area.dependencia_id')
                    ->first();
                // var_dump($principal);
                if (!empty($principal)) {
                    $status = 0;
                    $personalArea = new PersonalArea();
                    $personalArea->persona_dni = $value['dni'];
                    $personalArea->servicio_id = $value['servicio_id'];
                    $personalArea->dependencia_id = $value['dependencia_id'];
                    $personalArea->superstructura_id = $superstructura[0]['id'];
                    $personalArea->area_principal = $status;
                    $personalArea->area = $request->area;
                    $personalArea->active = $value['active'];
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
                $personalArea->active = $value['active'];
                $personalArea->save();
            }
        }
    }
    public function delete($id)
    {
        $personal = PersonalArea::FindOrFail($id);
        $personal->delete();
    }
    public function status(Request $request, $id)
    {
        $personal = PersonalArea::FindOrFail($id);
        $personal->active = $request->active;
        $personal->save();
    }
}
