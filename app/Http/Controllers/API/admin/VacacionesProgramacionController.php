<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VacacionesProgramacion;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;
use App\Models\PersonalArea;
use App\Models\VacacionesDocumento;
use App\Models\Persona;
use App\Models\PersonalGrupo;

class VacacionesProgramacionController extends Controller
{
    public function index(Request $request)
    {
        // $data = VacacionesProgramacion::all();
        $supestructura = parameter_alias_value('sup-estructura');
        $years = [];
        $years_query = VacacionesProgramacion::select('periodo_vacacional')
            ->orderBy('periodo_vacacional', 'desc')
            ->groupBy('periodo_vacacional')
            ->having('periodo_vacacional', '>', 0)
            ->pluck('periodo_vacacional', 'periodo_vacacional');

        $i = 0;
        foreach ($years_query as $year) {
            $years[$i]['name'] = "$year - " . ($year + 1);
            $years[$i]['id'] = $year;
            $i++;
        }
        $data = [
            'supestructura' => $supestructura,
            'years' => $years
        ];
        return response()->json($data);
    }
    public function months($selectedYear)
    {
        $monthsYear = 24;
        $xd = [];
        for ($i = 1; $i <= $monthsYear; $i++) {
            setlocale(LC_TIME, "spanish");
            $j = $i > 12 ? $i - 12 : $i;
            $fecha = $i > 12 ? now()->setYear($selectedYear + 1)->setMonth($j) : now()->setYear($selectedYear)->setMonth($j);
            $dia = strftime("%b %y", strtotime($fecha));
            $dia_m = strtoupper(substr($dia, 0, 3));
            array_push($xd, $dia);
        }
        return response()->json($xd);
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

        return response()->json($dependencias);
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
    public function getPersonal(Request $request)
    {
        $from = '2022';
        $to = '2023';
        // $vacaciones = PersonalGrupo::with([('vacaciones')->where('periodo_vacacional', $from)])->get();
        $de = $request->dependencia;
        $sup = $request->supestructura;
        $area = $request->area;
        $personal = PersonalGrupo::leftjoin('personas', 'personas.dni', '=', 'personal_grupos.dni')
            ->select('personas.nombres', 'personas.dni')
            // ->leftjoin('vacaciones_programaciones', 'vacaciones_programaciones.dni', 'personal_grupos.dni')
            ->join('personal_areas', 'personal_areas.id', '=', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.area_servicio', 1)
            ->where('personal_areas.supestructura_id', $sup)
            ->where('personal_areas.dependencia_id', $de)
            ->where('personal_areas.area', $area)
            //->where('personal_servicio.servicio_id',$selectedService)
            ->get();
        // $personal = PersonalGrupo::all()->load('vacaciones');
        $i = 0;
        foreach ($personal as $empleado) {
            $dni = $empleado->dni;
            //periodo 1
            $vac = VacacionesProgramacion::select('mes_programacion_reportado')->where('dni', $dni)->where('periodo_vacacional', $from)->get();
            $personal[$i]->vac1 = $vac;
            //periodo 2
            $vac2 = VacacionesProgramacion::select('mes_programacion_reportado')->where('dni', $dni)->where('periodo_vacacional', $to)->get();
            $personal[$i]->vac2 = $vac2;
            $i++;
        }
        return response()->json($personal);
    }
    public function getVacaciones(Request $request)
    {
        $dni = $request->dni;
        $mes = $request->mes;
        $periodo = $request->periodo;
        $vacacion = VacacionesProgramacion::select('id', 'mes_programacion_reportado')
            ->where('mes_programacion_reportado', $mes)
            ->where('dni', $dni)
            ->where('periodo_vacacional', $periodo)
            ->first();

        return response()->json($vacacion);
    }
}
