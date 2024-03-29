<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PersonalRolService;
use App\Models\Persona;
use App\Models\PersonalArea;
use App\Models\PersonalGrupo;
use App\Models\PersonalRole;
use App\Models\Subactividade;
use App\Models\SubactividadHorarioServicio;
use App\Models\Servicio;
use App\Models\actividad_area;
use App\Models\CmsParameter;
use App\Models\CmsParameterGroup;
use App\Models\rolesArea;
use App\Models\PersonalRolDetalle;
use App\Models\EssiSubactividades;
use App\Models\Subactividad_horario;
use App\Models\Horarios;

class PersonalRolDetalleController extends Controller
{
    private $personalRolService;

    /**
     * AttendanceController constructor.
     * @param PersonalRolService $personalRolService
     */
    // public function __construct(PersonalRolService $personalRolService)
    // {
    //     $this->personalRolService = $personalRolService;
    // }
    public function inicio(Request $request)
    {
        $meses[0]['id'] = '01';
        $meses[0]['name'] = 'Enero';
        $meses[1]['id'] = '02';
        $meses[1]['name'] = 'Febrero';
        $meses[2]['id'] = '03';
        $meses[2]['name'] = 'Marzo';
        $meses[3]['id'] = '04';
        $meses[3]['name'] = 'Abril';
        $meses[4]['id'] = '05';
        $meses[4]['name'] = 'Mayo';
        $meses[5]['id'] = '06';
        $meses[5]['name'] = 'Junio';
        $meses[6]['id'] = '07';
        $meses[6]['name'] = 'Julio';
        $meses[7]['id'] = '08';
        $meses[7]['name'] = 'Agosto';
        $meses[8]['id'] = '09';
        $meses[8]['name'] = 'Septiembre';
        $meses[9]['id'] = '10';
        $meses[9]['name'] = 'Octubre';
        $meses[10]['id'] = '11';
        $meses[10]['name'] = 'Noviembre';
        $meses[11]['id'] = '12';
        $meses[11]['name'] = 'Diciembre';
        $servicios = PersonalArea::select('area as name', 'id')->get();
        //  dd($turnos);
        $data = [
            'meses' => $meses,
            'servicios' => $servicios,
        ];
        return response()->json($data);
    }
    public function index(Request $request)
    {

        $selectedYear  = $request->anio;
        $selectedMonth =  $request->mes;
        $selectedService = $request->area_id;

        $rol = RolesArea::where('area_id', $selectedService)->where('mes', $selectedMonth)->where('anio', $selectedYear)->first();

        if ($rol == null)
            $rol = RolesArea::create(['area_id' => $selectedService, 'mes' => $selectedMonth, 'anio' => $selectedYear]);

        $daysInMonth = $this->daysInMonth($selectedYear, $selectedMonth);
        $dias = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            setlocale(LC_TIME, "spanish");
            $fecha = now()->setYear($selectedYear)->setMonth($selectedMonth)->setDay($i)->format('Y-m-d');
            $dia = strftime("%a", strtotime($fecha));
            $num = date('d', strtotime($fecha));
            $group_alias = 'feriados';
            $parent_id = null;
            $feriado = CmsParameter::select('value')
                ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
                ->where('value', $fecha)->first();

            if ($feriado) {
                $dias[$i]['feriado'] = 1;
                $dias[$i]['dia'] = $num . '-' . strtoupper(substr($dia, 0, 1));
                $dias[$i]['num'] = 'D' . $num;
            } else {
                $dias[$i]['feriado'] = 0;
                $dias[$i]['dia'] = $num . '-' . strtoupper(substr($dia, 0, 1));
                $dias[$i]['num'] = 'D' . $num;
            }

            // $dias[$i]['status'] = $num ? ;
        }
        $area = PersonalGrupo::select('personal_grupos.id', 'personas.nombres', 'personas.dni')
            ->join('personas', 'personas.dni', 'personal_grupos.dni')
            ->where('personal_grupos.personal_area_id', $selectedService)
            ->get();

        $i = 0;
        foreach ($area as $value) {
            $horas = 0;
            $array = [];
            $dni = $value['dni'];
            foreach ($dias as $item) {
                $xdia = substr($item['num'], 1);
                $fechaxactual = date('Y-m-d', strtotime($selectedYear . '-' . $selectedMonth . '-' . $xdia));
                $rol = PersonalRolDetalle::join('essi_subactividades', 'essi_subactividades.id', 'personal_rol_detalles.actividad_id', 'personal_rol_detalles.cambio_turno')->where('fecha_turno', $fechaxactual)->where('persona_dni', $dni)->first();
                if ($rol != null) {
                    $day = $item['num'];
                    $abr = 'A' . substr($item['num'], 1);
                    $data = [
                        'name' => $rol['abreviacion'],
                        'id' => $rol['actividad_id'],
                        'cambio_turno' => $rol['cambio_turno'],
                    ];
                    // $data = [
                    //     $day => $rol['abreviacion'],
                    //     $abr => $rol['actividad_id']
                    // ];
                    // $area[$i][$day] = $data;
                    $area[$i][$day] = $rol['actividad_id'];
                    $area[$i][$abr] = $data;
                    $h = date('H', strtotime($rol['cantidad_horas']));
                    $horas = $horas + $h;
                    array_push($array, $data);
                }
            }
            $area[$i]->horas = $horas;
            $area[$i]->obj = $array;
            $i++;
        }

        $data = [
            'personal' => $area,
            'diasxmes' => $dias,
            'rol' => $rol
        ];
        // $i = 20;
        // $fecha = now()->setYear($selectedYear)->setMonth($selectedMonth)->setDay($i)->format('Y-m-d');
        // $group_alias = 'feriados';
        // $parent_id = null;
        // $feriado = CmsParameter::select('value')
        //     ->whereIn('group_id', CmsParameterGroup::where('alias', $group_alias)->pluck('id'))
        //     ->where('value', $fecha)->first();
        // if ($feriado)
        //     return response()->json(['msg' => 'si']);

        return response()->json($data);
    }
    public function daysInMonth(int $year, int $month)
    {
        return now()->setYear($year)
            ->setMonth($month)
            ->daysInMonth;
    }
    public function getActividades(Request $request)
    {
        $id = $request->area_id;
        // $request->area
        $actividad = actividad_area::select('essi_subactividades.id', 'essi_subactividades.abreviacion')
            ->join('essi_subactividades', 'essi_subactividades.id', 'actividad_area.actividad_id')
            ->where('area_id', $id)
            ->where('essi_subactividades.active', 1)
            ->pluck('essi_subactividades.abreviacion', 'essi_subactividades.id');
        return response()->json($actividad);
    }
    public function getRoles(Request $request)
    {
        $id = $request->area_id;
        $anio = $request->anio;
        $mes = $request->mes;
        // $request->area
        $role = RolesArea::where('anio', $anio)->where('mes', $mes)->where('area_id', $id)->first();
        return response()->json($role);
    }
    public function store(Request $request)
    {
        $user = $request->user()->id;
        $dia = substr($request->dia, 1);
        $fecha = date('Y-m-d', strtotime($request->anio . '-' . $request->mes . '-' . $dia));
        $actividad_id = $request->actividad_id;
        //eliminar si ya existe un registro
        $ifexist = PersonalRolDetalle::where('fecha_turno', $fecha)->where('persona_dni', $request->persona_dni);
        if ($ifexist)
            $ifexist->delete();
        //procedimiento registrar
        $horario = Subactividad_horario::select('subactividad_horario.actividad_id', 'horarios.metadata')
            ->join('cms_parameters as horarios', 'horarios.id', 'subactividad_horario.horario_id')
            ->where('subactividad_horario.actividad_id', '=', $actividad_id)
            ->first();
        $metadata = json_decode($horario->metadata, true);
        $rol = new PersonalRolDetalle();
        $rol->persona_dni = $request->persona_dni;
        $rol->area_id = $request->area_id;
        $rol->actividad_id = $request->actividad_id;
        $rol->personal_rol_id = $request->personal_rol_id;
        $rol->fecha_turno = $fecha;
        $rol->hora_inicio = date('H:i:s', strtotime($metadata['hora_inicio']));
        $rol->hora_fin = date('H:i:s', strtotime($metadata['hora_fin']));
        $rol->cantidad_horas = $metadata['cantidad_horas'];
        $rol->estado_trabajo = 1;
        $rol->created_by = $user;
        $rol->save();

        return response()->json($horario);
    }
    public function delete(Request $request)
    {
        $dia = substr($request->dia, 1);
        $fecha = date('Y-m-d', strtotime($request->anio . '-' . $request->mes . '-' . $dia));
        $rol = PersonalRolDetalle::where('fecha_turno', $fecha)->where('persona_dni', $request->persona_dni);
        if ($rol)
            $rol->delete();

        return response()->json(['msg' => 'existe']);
    }
}
