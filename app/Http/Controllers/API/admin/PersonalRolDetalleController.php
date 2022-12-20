<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PersonalRolService;
use App\Models\Persona;
use App\Models\PersonalRole;
use App\Models\Subactividade;
use App\Models\SubactividadHorarioServicio;
use App\Models\Servicio;

class PersonalRolDetalleController extends Controller
{
    private $personalRolService;

    /**
     * AttendanceController constructor.
     * @param PersonalRolService $personalRolService
     */
    public function __construct(PersonalRolService $personalRolService)
    {
        $this->personalRolService = $personalRolService;
    }
    public function index(Request $request)
    {
        // $personalRolDetalles = PersonalRolDetalle::paginate();

        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        $selectedYear  = now()->year;
        $selectedMonth =  now()->format('m');
        $selectedService = 1;
        $rol = PersonalRole::where('servicio_id', $selectedService)->where('mes', $selectedMonth)->where('anio', $selectedYear)->first();
        // dd($rol);    
        if ($rol == null)
            $rol = PersonalRole::create(['servicio_id' => $selectedService, 'mes' => $selectedMonth, 'anio' => $selectedYear]);

        $daysInMonth = $this->personalRolService->daysInMonth($selectedYear, $selectedMonth);

        $personal = Persona::join('personal_servicio', 'personal_servicio.persona_dni', '=', 'dni')
            ->where('active', true)
            ->where('personal_servicio.servicio_id', $selectedService)
            ->get();
        // $turnos = $this->personalRolService->getRolDetalle($rol->id);
        $servicios = Servicio::where('active', true)->get()->pluck('servicio', 'id');
        //  dd($turnos);
        $data = [
            'meses' => $meses,
            'servicios' => $servicios,
            'personal' => $personal,
            'diasxmes' => $daysInMonth,
            'rol' => $rol
        ];
        return response()->json($data);
        // return view('personal-rol-detalle.index', compact('meses', 'servicios', 'personal', 'daysInMonth', 'selectedYear', 'selectedMonth', 'selectedService', 'rol'));
        //->with('i', (request()->input('page', 1) - 1) * $personalRolDetalles->perPage());
    }
}
