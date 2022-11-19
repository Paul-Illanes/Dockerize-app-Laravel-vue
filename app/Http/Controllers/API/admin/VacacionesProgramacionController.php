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
use App\Models\PersonalAreasVacaciones;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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
    public function getProcesoVacaciones(Request $request)
    {
        $vac = VacacionesProgramacion::all();
        $grupos = PersonalGrupo::all();
        $data = [
            'vac' => $vac,
            'grupo' => $grupos
        ];

        return response()->json($data, 200);
    }
    public function cronogramaList(Request $request)
    {
        $group_alias = "programacion_vacaciones";
        $parent_id = null;

        $years_query = \App\Models\CmsParameter::select('alias AS name', 'value AS id', 'metadata->status AS status')
            ->whereIn('group_id', \App\Models\CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
            ->where('parent_id', $parent_id)
            ->where('active', true)
            ->orderBy('value', 'desc')
            ->get();
        $years = [];
        $i = 0;
        foreach ($years_query as $year) {
            $periodo = $year->id;
            $years[$i]['name'] = "$periodo - " . ($periodo + 1);
            $years[$i]['id'] = $year->id;
            $years[$i]['status'] = $year->status;
            $i++;
        }

        return response()->json($years, 200);
    }
    public function areaList(Request $request)
    {
        $sup = $request->sup;
        $dep = $request->dep;
        $areas = PersonalArea::select('area as name', 'id')
            ->where('supestructura_id', $sup)
            ->where('dependencia_id', $dep)
            ->get();
        return response()->json($areas, 200);
    }
    public function getCronograma(Request $request)
    {
        $area_id            = $request->area_id;
        $selectedYear       = $request->year;
        $per = PersonalGrupo::select('personal_grupos.id', 'personas.nombres', 'personas.dni', 'personas.cargo', 'personas.fecha_ingreso', 'personas.condicion_laboral_id', 'personas.fecha_nacimiento', 'personas.cod_planilla')
            ->join('personas', 'personas.dni', 'personal_grupos.dni')
            ->where('personal_grupos.personal_area_id', $area_id)
            ->get();
        $i = 0;
        foreach ($per as $value) {
            $dni = $value['dni'];
            $vp = VacacionesProgramacion::select('id', 'periodo_vacacional', 'mes_programacion_reportado', 'mes_programacion_solicitado', 'anio_programacion_reportado', 'anio_programacion_solicitado', 'estado_presentacion', 'obs_1')->where('dni', $dni)->where('periodo_vacacional', '=', $selectedYear)->first();
            if ($vp) {
                $per[$i]->id = $vp->id;
                $per[$i]->periodo_vacacional = $vp->periodo_vacacional;
                $per[$i]->mes_programacion_reportado = $vp->mes_programacion_reportado;
                $per[$i]->mes_programacion_solicitado = $vp->mes_programacion_solicitado;
                $per[$i]->anio_programacion_reportado = $vp->anio_programacion_reportado;
                $per[$i]->anio_programacion_solicitado = $vp->anio_programacion_solicitado;
                $per[$i]->estado_presentacion = $vp->estado_presentacion;
                $per[$i]->obs_1 = $vp->obs_1;
            } else {
                $per[$i]->id = null;
                $per[$i]->periodo_vacacional = null;
                $per[$i]->mes_programacion_reportado = null;
                $per[$i]->mes_programacion_solicitado = null;
                $per[$i]->anio_programacion_reportado = null;
                $per[$i]->anio_programacion_solicitado = null;
                $per[$i]->estado_presentacion = null;
                $per[$i]->obs_1 = null;
            }
            $i++;
        }
        return response()->json($per, 200);
    }
    public function getMeses(Request $request)
    {
        $currentVacationPeriod = 2023;
        $condicion_laboral_id = $request->condicion_laboral ?? "9";
        $regimen_laboral = \cl_to_regimen_laboral($condicion_laboral_id);
        $meses = get_vacation_months($request->fecha_ingreso, $currentVacationPeriod, $regimen_laboral);
        return response()->json($meses, 200);
    }
    public function jefe(Request $request)
    {
        // $vac = VacacionesProgramacion::find($request->id);
        $yearmonth = $request->mes;
        $result = explode("-", $yearmonth);

        $vacacionesProgramacione = VacacionesProgramacion::updateOrCreate(
            [
                'dni' => $request->dni,
                'periodo_vacacional' => $request->periodo_vacacional
            ],
            [
                'mes_programacion_solicitado' => $result[1],
                'anio_programacion_solicitado' => $result[0],
                'dni' => $request->dni,
                'periodo_vacacional' => $request->periodo_vacacional,
                'cod_planilla' => $request->cod_planilla,
                'nombres' => $request->nombres,
                'regimen_laboral' => cl_to_regimen_laboral($request->regimen_laboral),
                'fecha_ingreso' => $request->fecha_ingreso,
                'estado_presentacion' => $request->estado_presentacion,
                'obs_1' => $request->obs_1,
            ]
        );

        return response()->json($vacacionesProgramacione, 200);
    }
    public function delete(Request $request, $id)
    {
        $vac = VacacionesProgramacion::find($id);
        $vac->delete();
    }
    public function aprobar(Request $request)
    {

        foreach ($request->items as $value) {
            $vac = VacacionesProgramacion::find($value['id']);
            $vac->estado_presentacion = 0;
            $vac->save();
        }
    }
    public function status(Request $request)
    {
        $vac = VacacionesProgramacion::find($request->id);
        $vac->estado_presentacion = $request->status;
        $vac->save();
    }
    public function getCloseStatus(Request $request)
    {
        $id = $request->id;
        $periodo = $request->periodo;
        $vac = PersonalAreasVacaciones::select('status')->where('personal_area_id', $id)->where('periodo_vacaciones', $periodo)->first();;
        return response()->json($vac, 200);
    }
    public function cerrarProcesoJefe(Request $request)
    {
        $id = $request->id;
        $periodo = $request->periodo;
        PersonalAreasVacaciones::updateOrCreate([
            'personal_area_id' => $id,
            'periodo_vacaciones' => $periodo,

        ], [
            'personal_area_id' => $id,
            'periodo_vacaciones' => $periodo,
            'status' => 1,

        ]);
    }
    public function generar_pdf(Request $request)
    {
        $user = $request->user()->name . ' ' . $request->user()->lastname;
        $personal_area = PersonalArea::find($request->id);
        $supestructura_id = $personal_area->supestructura_id;
        // $selectedYear = $request->periodo;
        $selectedYear = $request->periodo;
        $dependencia_id = $personal_area->dependencia_id;
        $area = $personal_area->area;
        $fecha_generacion = date('dmYHis');

        $personal = Persona::join('personal_grupos', 'personal_grupos.dni', '=', 'personas.dni')
            ->join('personal_areas', 'personal_areas.id', '=', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.area_servicio', true)
            ->where('personal_areas.supestructura_id', $supestructura_id)
            ->where('personal_areas.dependencia_id', $dependencia_id)
            ->where('personal_areas.area', $area)
            //->where('personal_servicio.servicio_id',$selectedService)
            ->get();

        $vacaciones = VacacionesProgramacion::join('personal_grupos', 'personal_grupos.dni', '=', 'vacaciones_programaciones.dni')
            ->join('personal_areas', 'personal_areas.id', '=', 'personal_grupos.personal_area_id')
            ->where('personal_grupos.area_servicio', true) // valida area
            ->where('personal_areas.supestructura_id', $supestructura_id)
            ->where('personal_areas.dependencia_id', $dependencia_id)
            ->where('personal_areas.area', $area)
            ->where('periodo_vacacional', $selectedYear)
            ->get();
        $text_oficina = parameter_get_value('sup-estructura', $supestructura_id)->alias . '-' . parameter_get_value('dependencia', $dependencia_id)->alias . '-' . $area;
        $file_name = strtoupper("programacion-vacacional-$selectedYear-$supestructura_id-$dependencia_id-$area-$fecha_generacion") . '.pdf';

        $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'isPhpEnabled' => true])
            ->loadView('vacaciones-programacione.programacion-vacaciones-pdf', compact('vacaciones', 'personal', 'selectedYear', 'fecha_generacion', 'text_oficina', 'user'))
            ->save(\storage_path('app/public/programacion-vacacional/') . $file_name);

        return response()->json($file_name, 200);
    }
    public function getfile($name)
    {
        $path = 'public/programacion-vacacional/' . $name;
        $file = Storage::path($path);
        return response()->file($file);
    }
}
